<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Muestra la vista del formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginView()
    {
        // Se ha cambiado la ruta de la vista para que apunte a la carpeta 'components'.
        return view('components.auth-modal');
    }

    /**
     * Maneja la solicitud de inicio de sesión con el token de Firebase.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginWithFirebase(Request $request)
    {
        try {
            $idTokenString = $request->input('idToken');
            
            // Verificar que el token no esté vacío
            if (empty($idTokenString)) {
                return response()->json(['error' => 'Token no proporcionado'], 400);
            }
            
            $verifiedIdToken = app('firebase.auth')->verifyIdToken($idTokenString);
            $uid = $verifiedIdToken->claims()->get('sub');
            
            $user = User::where('firebase_uid', $uid)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $verifiedIdToken->claims()->get('name') ?? 'Usuario',
                    'email' => $verifiedIdToken->claims()->get('email'),
                    'firebase_uid' => $uid,
                ]);
            }

            Auth::login($user);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Firebase login error: ' . $e->getMessage());
            
            // Verificar si es un error de token inválido
            if (str_contains($e->getMessage(), 'Invalid ID token') || 
                str_contains($e->getMessage(), 'The Firebase ID token is malformed') ||
                str_contains($e->getMessage(), 'InvalidIdToken')) {
                return response()->json(['error' => 'Token de Firebase inválido'], 401);
            }
            
            // Verificar si es un error específico de Firebase
            if (str_contains($e->getMessage(), 'Firebase') ||
                str_contains($e->getMessage(), 'FirebaseException')) {
                return response()->json(['error' => 'Error de Firebase'], 500);
            }
            
            return response()->json(['error' => 'Ha ocurrido un error: ' . $e->getMessage()], 500);
        }
    }
}