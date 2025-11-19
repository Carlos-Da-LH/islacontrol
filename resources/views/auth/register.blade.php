<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Plataforma de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .input-group input {
            padding-left: 2.5rem;
            border-color: #d1d5db;
        }

        .input-group:focus-within .icon {
            color: #5F9E74;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-teal': '#5F9E74',
                    }
                }
            }
        }
    </script>
</head>

<body class="flex items-center justify-center min-h-screen p-4 sm:p-6 md:p-8">

    <div class="relative w-full max-w-sm p-8 mx-auto form-container">
        <a href="{{ url('/') }}" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>

        <div class="flex flex-col items-center mb-6 mt-6">
            <div class="rounded-full h-20 w-20 flex items-center justify-center overflow-hidden bg-gray-200 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800">Crea tu cuenta</h2>
        </div>

        {{-- El formulario ahora llama a la función handleRegister --}}
        <form id="register-form" onsubmit="handleRegister(event)" class="space-y-4">
            @csrf

            <div class="relative input-group">
                <input id="name" type="text" placeholder="Nombre completo" name="name" required class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-custom-teal">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none icon">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>

            <div class="relative input-group">
                <input id="email" type="email" placeholder="Email" name="email" required class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-custom-teal">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none icon">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="relative input-group">
                <input id="password" type="password" placeholder="Contraseña" name="password" required
                    class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-custom-teal pl-10 pr-12">

                <!-- Ícono candado -->
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none icon">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <!-- Botón mostrar/ocultar -->
                <button type="button" onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">

                    <!-- Icono ojo -->
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                   9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <button type="submit" class="w-full mt-4 rounded-full py-3 font-bold text-white transition-all transform hover:scale-105" style="background-color: #5F9E74;">
                REGISTRARME
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            ¿Ya tienes una cuenta?
            <a href="/?modal=login" class="text-custom-teal font-medium hover:underline">Iniciar sesión
            </a>
        </p>
    </div>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyA8VguwL3jh2lIVpBSRrOvjy-c0PfmGD-4",
            authDomain: "isla-control.firebaseapp.com",
            projectId: "isla-control",
            storageBucket: "isla-control.firebasestorage.app",
            messagingSenderId: "145410754650",
            appId: "1:145410754650:web:8d590e161d280094a6f063",
            measurementId: "G-Z5RWFK99Q8"
        };

        // Inicializa Firebase
        window.app = firebase.initializeApp(firebaseConfig);
        window.auth = firebase.auth();

        // Función para manejar el registro de usuarios
        function handleRegister(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (email && password && name) {
                // Crea un nuevo usuario con email y contraseña en Firebase
                window.auth.createUserWithEmailAndPassword(email, password)
                    .then((userCredential) => {
                        const user = userCredential.user;

                        // --- Aquí está la corrección: se actualiza el perfil del usuario ---
                        return user.updateProfile({
                            displayName: name
                        });
                    })
                    .then(() => {
                        Toastify({
                            text: "¡Registro exitoso! Redirigiendo...",
                            duration: 3000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #22C55E, #4ADE80)",
                        }).showToast();

                        // Redirige al usuario a la página principal después del registro
                        window.location.href = "{{ url('/') }}";
                    })
                    .catch((error) => {
                        const errorMessage = error.message;
                        console.error("Error de registro:", errorMessage);
                        Toastify({
                            text: "Error de registro: " + errorMessage,
                            duration: 3000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                        }).showToast();
                    });
            } else {
                Toastify({
                    text: "Por favor, completa todos los campos para registrarte.",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "linear-gradient(to right, #FBBF24, #FCD34D)",
                }).showToast();
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