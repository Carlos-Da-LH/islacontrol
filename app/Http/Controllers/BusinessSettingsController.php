<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{
    /**
     * Obtener la configuración del negocio del usuario autenticado
     */
    public function index()
    {
        $user = Auth::user();

        // Buscar la configuración del usuario, o crear una por defecto
        $settings = BusinessSettings::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'MI NEGOCIO',
                'business_address' => '',
                'business_phone' => '',
                'business_rfc' => '',
                'footer_message' => 'Este ticket no es válido como factura fiscal',
                'extra_message' => '',
                'show_logo' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'settings' => $settings,
        ]);
    }

    /**
     * Guardar o actualizar la configuración del negocio
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'business_phone' => 'nullable|string|max:50',
            'business_rfc' => 'nullable|string|max:50',
            'footer_message' => 'nullable|string',
            'extra_message' => 'nullable|string',
            'show_logo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        $settings = BusinessSettings::updateOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => $request->business_name,
                'business_address' => $request->business_address,
                'business_phone' => $request->business_phone,
                'business_rfc' => $request->business_rfc,
                'footer_message' => $request->footer_message,
                'extra_message' => $request->extra_message,
                'show_logo' => $request->show_logo ?? true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Configuración guardada exitosamente',
            'settings' => $settings,
        ]);
    }

    /**
     * Subir logo del negocio
     */
    public function uploadLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        // Obtener o crear la configuración
        $settings = BusinessSettings::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'MI NEGOCIO',
                'show_logo' => true,
            ]
        );

        // Eliminar logo anterior si existe
        if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
            Storage::disk('public')->delete($settings->logo_path);
        }

        // Guardar nuevo logo
        $logoPath = $request->file('logo')->store('logos', 'public');
        $settings->logo_path = $logoPath;
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Logo subido exitosamente',
            'logo_url' => Storage::url($logoPath),
            'settings' => $settings,
        ]);
    }
}
