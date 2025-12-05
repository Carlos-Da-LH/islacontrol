<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modal de Autenticación Centrado</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .text-custom-teal {
            color: #5F9E74;
        }

        .form-checkbox:checked {
            background-color: #5F9E74;
            border-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }

        /* Asegurar que el modal esté centrado */
        #auth-modal {
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease-in-out;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            z-index: 9999 !important;
        }

        #auth-modal.hidden {
            display: none !important;
        }

        #auth-modal-content {
            animation: fadeInScale 0.3s ease-in-out;
            margin: auto;
        }

        @keyframes fadeInScale {

            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div id="auth-modal" class="hidden">
        <div id="auth-modal-content"
            class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">

            <div class="px-8 py-10 relative">

                <button onclick="hideAuthModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="rounded-full h-20 w-20 flex items-center justify-center overflow-hidden bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A7.001 7.001 0 0112 15a7.001 7.001 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Iniciar Sesión</h2>
                </div>

                <form id="login-form" class="mt-6 space-y-4">

                    <div class="relative">
                        <input id="email" type="email" placeholder="Email"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-custom-teal">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative">
                        <input id="password" type="password" placeholder="Contraseña"
                            class="w-full pl-10 pr-10 py-2 rounded-lg border border-gray-300
           focus:outline-none focus:ring-2 focus:ring-custom-teal">

                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2a4 4 0 00-4 4v3H5a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2h-1V6a4 4 0 00-4-4zm2 7V6a2 2 0 10-4 0v3h4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">

                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-between items-center text-sm mt-4">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="remember-me"
                                class="form-checkbox text-custom-teal rounded border-gray-300">
                            <label for="remember-me" class="text-gray-800">Recuérdame</label>
                        </div>

                        <a href="{{ route('password.request') }}" class="font-medium text-custom-teal hover:underline">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full mt-4 rounded-full py-3 font-bold text-white transition-all transform hover:scale-105"
                        style="background-color: #5F9E74;">
                        Ingresar
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white px-2 text-gray-500 rounded-full">o</span>
                    </div>
                </div>

                <button onclick="signInWithGoogle()"
                    class="w-full flex items-center justify-center space-x-3 border border-gray-300 py-3 rounded-full text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-5 w-5" alt="Google icon">
                    <span>Continuar con Google</span>
                </button>

                <p class="mt-4 text-center text-gray-600">
                    ¿No tienes una cuenta?
                    <a href="/register" class="font-bold text-custom-teal hover:underline">Regístrate</a>
                </p>

            </div>
        </div>
    </div>
    <div id="toast"
        class="hidden fixed top-4 left-1/2 transform -translate-x-1/2 
        bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg 
        shadow-md text-sm flex items-center gap-2 transition-all duration-300 
        opacity-0 z-[999999]">
    </div>

    <style>
        #toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        #toast.hidden {
            opacity: 0;
            transform: translateX(-50%) translateY(-10px);
        }

        #toast.error {
            border-color: #dc2626;
            color: #dc2626;
        }
    </style>


    <script>
        function showAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Cerrar al hacer clic afuera
        document.getElementById('auth-modal').addEventListener('click', (event) => {
            if (event.target.id === 'auth-modal') {
                hideAuthModal();
            }
        });

        // TOAST BONITO, PEQUEÑO Y CENTRADO ARRIBA
        function showToast(message, type = "success") {
            const toast = document.getElementById("toast");

            toast.innerHTML = `
        ${type === "success" ? "✅" : "⚠️"}
        <span>${message}</span>
    `;

            toast.classList.remove("hidden");
            toast.classList.add("show");

            if (type === "error") {
                toast.classList.add("error");
            } else {
                toast.classList.remove("error");
            }

            setTimeout(() => {
                toast.classList.remove("show");
                setTimeout(() => toast.classList.add("hidden"), 300);
            }, 2500);
        }


        // ✅ LOGIN con Firebase Email/Password
        document.getElementById('login-form').addEventListener('submit', (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const pass = document.getElementById('password').value.trim();

            if (!email || !pass) {
                return showToast("Completa todos los campos", "error");
            }

            showToast("Validando...", "success");

            if (typeof firebase !== 'undefined' && firebase.auth) {
                // Autenticar con Firebase
                firebase.auth().signInWithEmailAndPassword(email, pass)
                    .then((result) => {
                        // Obtener el token de Firebase
                        return result.user.getIdToken().then(idToken => {
                            const user = result.user;

                            // Enviar el token al backend Laravel
                            return fetch('/login/firebase', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                },
                                body: JSON.stringify({
                                    idToken: idToken,
                                    uid: user.uid,
                                    email: user.email,
                                    name: user.displayName || user.email.split('@')[0]
                                })
                            });
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast("Inicio de sesión exitoso", "success");
                            setTimeout(() => {
                                // Si el usuario necesita suscripción, redirigir a seleccionar plan
                                if (data.needs_subscription) {
                                    window.location.replace("/suscripcion/seleccionar-plan");
                                } else {
                                    window.location.replace("/dashboard");
                                }
                            }, 800);
                        } else {
                            showToast("Error: " + (data.error || "Intenta nuevamente"), "error");
                        }
                    })
                    .catch((error) => {
                        console.error('Error de autenticación:', error);
                        let errorMessage = "Error de autenticación";
                        if (error.code === 'auth/wrong-password') {
                            errorMessage = "Contraseña incorrecta";
                        } else if (error.code === 'auth/user-not-found') {
                            errorMessage = "Usuario no encontrado";
                        } else if (error.code === 'auth/invalid-email') {
                            errorMessage = "Email inválido";
                        }
                        showToast(errorMessage, "error");
                    });
            } else {
                // Fallback si Firebase no está configurado
                showToast("Firebase no configurado", "error");
            }
        });
        // Google login
        function signInWithGoogle() {
            if (typeof firebase !== 'undefined' && firebase.auth) {
                const provider = new firebase.auth.GoogleAuthProvider();
                firebase.auth().signInWithPopup(provider)
                    .then((result) => {
                        // Obtener el token de Firebase
                        return result.user.getIdToken().then(idToken => {
                            // Obtener información del usuario
                            const user = result.user;

                            // Enviar el token e información del usuario al backend Laravel
                            return fetch('/login/firebase', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                },
                                body: JSON.stringify({
                                    idToken: idToken,
                                    uid: user.uid,
                                    email: user.email,
                                    name: user.displayName || user.email.split('@')[0]
                                })
                            });
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast("Inicio de sesión exitoso", "success");
                            setTimeout(() => {
                                // Si el usuario necesita suscripción, redirigir a seleccionar plan
                                if (data.needs_subscription) {
                                    window.location.replace("/suscripcion/seleccionar-plan");
                                } else {
                                    window.location.replace("/dashboard");
                                }
                            }, 800);
                        } else {
                            showToast("Error al iniciar sesión: " + (data.error || "Intenta nuevamente"), "error");
                        }
                    })
                    .catch((error) => {
                        console.error('Error de autenticación:', error);
                        showToast("Error de autenticación", "error");
                    });
            } else {
                console.warn('Firebase no está configurado. Simulando login...');
                alert('Login con Google activado');
            }
        }
    </script>
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const eye = document.getElementById("eyeIcon");

            if (input.type === "password") {
                input.type = "text";
                eye.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.944-9.543-7 
                    1.04-3.317 3.566-5.854 6.75-6.708M10.3 6.39A9.973 9.973 0 0112 5c4.477 
                    0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.105 5.245M15 12a3 3 0 
                    11-6 0 3 3 0 016 0z"/>
                <line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" 
                    stroke-width="2" stroke-linecap="round"/>
            `;
            } else {
                input.type = "password";
                eye.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                    9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
            }
        }
    </script>


</body>

</html>