<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Actualizar la configuración (con respuesta JSON)
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // 1. Guardar el nombre del negocio
            $this->saveBusinessName($validated['name']);

            // 2. Guardar el logo si se subió uno nuevo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $this->saveLogo($request->file('logo'));
                Log::info('Logo guardado en: ' . $logoPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada exitosamente',
                'logo_path' => $logoPath
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar configuración: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la configuración: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener el nombre del negocio desde un archivo de configuración
     */
    private function getBusinessName()
    {
        $configPath = storage_path('app/settings/business_name.txt');
        
        if (File::exists($configPath)) {
            return trim(File::get($configPath));
        }
        
        return config('app.name', 'Mi Negocio');
    }

    /**
     * Guardar el nombre del negocio
     */
    private function saveBusinessName($name)
    {
        $configDir = storage_path('app/settings');
        
        // Crear el directorio si no existe
        if (!File::exists($configDir)) {
            File::makeDirectory($configDir, 0755, true);
        }
        
        File::put($configDir . '/business_name.txt', $name);
        
        // También actualizar en config para acceso inmediato
        config(['app.name' => $name]);
    }

    /**
     * Obtener la URL del logo
     */
    private function getLogoUrl()
    {
        // Verificar si existe un logo personalizado
        $formats = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
        
        foreach ($formats as $format) {
            if (File::exists(public_path("images/logo.{$format}"))) {
                return asset("images/logo.{$format}");
            }
        }
        
        // Logo por defecto
        return asset('images/default_logo.png');
    }

    /**
     * Guardar el logo
     */
    private function saveLogo($file)
    {
        $imageDir = public_path('images');
        
        // Crear el directorio si no existe
        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0755, true);
            Log::info('Directorio de imágenes creado: ' . $imageDir);
        }
        
        // Eliminar logos anteriores
        $oldLogos = [
            'logo.png', 'logo.jpg', 'logo.jpeg', 
            'logo.gif', 'logo.svg'
        ];
        
        foreach ($oldLogos as $oldLogo) {
            $oldPath = public_path('images/' . $oldLogo);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
                Log::info('Logo anterior eliminado: ' . $oldPath);
            }
        }
        
        // Guardar el nuevo logo
        $extension = $file->getClientOriginalExtension();
        $filename = 'logo.' . $extension;
        $destinationPath = $imageDir . '/' . $filename;
        
        $file->move($imageDir, $filename);
        
        // Verificar que el archivo se guardó correctamente
        if (File::exists($destinationPath)) {
            Log::info('Logo guardado exitosamente en: ' . $destinationPath);
            // Asegurar permisos correctos
            chmod($destinationPath, 0644);
        } else {
            Log::error('Error: El logo no se guardó en: ' . $destinationPath);
        }
        
        return asset('images/' . $filename);
    }
}