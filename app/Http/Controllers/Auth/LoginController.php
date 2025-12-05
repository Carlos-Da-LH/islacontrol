<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
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
            // Validar que se reciban los datos necesarios
            $validated = $request->validate([
                'uid' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
            ]);

            // Buscar o crear el usuario en la base de datos usando el UID de Firebase
            $user = User::where('firebase_uid', $validated['uid'])->first();

            if (!$user) {
                // Si el usuario no existe, crearlo
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'firebase_uid' => $validated['uid'],
                    'password' => bcrypt(uniqid()), // Password aleatorio (no se usa)
                ]);

                // Crear cliente por defecto "Público General" para el nuevo usuario
                Customer::create([
                    'name' => 'Público General',
                    'email' => 'publico.general.' . $user->id . '@default.com',
                    'phone_number' => 'N/A',
                    'user_id' => $user->id,
                ]);

                Log::info('Nuevo usuario creado desde Firebase con cliente por defecto', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'firebase_uid' => $user->firebase_uid
                ]);
            } else {
                Log::info('Usuario existente inició sesión', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

            // Iniciar sesión en Laravel
            Auth::login($user, true);

            // Verificar si el usuario tiene suscripción
            $hasSubscription = $user->subscribed('default');
            $needsSubscription = !$hasSubscription;

            return response()->json([
                'success' => true,
                'needs_subscription' => $needsSubscription,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in Firebase login', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Datos incompletos'], 400);
        } catch (\Exception $e) {
            Log::error('Firebase login error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Ha ocurrido un error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada exitosamente');
    }
}