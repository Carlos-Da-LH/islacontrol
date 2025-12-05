<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - {{ $planConfig['name'] }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        .StripeElement {
            background-color: white;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: border-color 0.2s;
        }

        .StripeElement--focus {
            border-color: #00D084;
            box-shadow: 0 0 0 3px rgba(0, 208, 132, 0.1);
        }

        .StripeElement--invalid {
            border-color: #EF4444;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 mb-4">
                    <i class='bx bx-arrow-back text-xl'></i>
                    <span class="font-semibold">Volver</span>
                </a>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Completa tu suscripción</h1>
                <p class="text-gray-600">{{ $planConfig['name'] }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Plan Details -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-emerald-600 mb-4">{{ $planConfig['name'] }}</h2>

                    <div class="mb-6">
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold text-gray-800">${{ $planConfig['price'] }}</span>
                            <span class="text-gray-600">/mes</span>
                        </div>
                        @if($planConfig['trial_days'] > 0)
                        <p class="text-sm text-emerald-600 font-semibold mt-2">
                            {{ $planConfig['trial_days'] }} días de prueba gratis
                        </p>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-800 mb-3">Incluye:</h3>
                        @foreach($planConfig['features'] as $feature)
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>

                    @if($planConfig['trial_days'] > 0)
                    <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class='bx bx-info-circle text-emerald-600 text-xl'></i>
                            <div class="text-sm text-emerald-800">
                                <p class="font-semibold mb-1">Período de prueba</p>
                                <p>No se te cobrará hasta después de {{ $planConfig['trial_days'] }} días. Puedes cancelar en cualquier momento.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Payment Form -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Información de pago</h2>

                    <!-- Login with Google (Si no está autenticado) -->
                    @guest
                    <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900 font-semibold mb-3 text-center">
                            <i class='bx bx-info-circle'></i> Primero inicia sesión para continuar
                        </p>

                        <button onclick="signInWithGoogle()" type="button"
                            class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 py-3 px-4 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 hover:border-emerald-500 transition-all mb-3">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-5 w-5" alt="Google">
                            <span>Continuar con Google</span>
                        </button>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-blue-50 px-2 text-gray-500">o</span>
                            </div>
                        </div>

                        <a href="{{ route('register', ['plan' => $plan]) }}"
                            class="block w-full text-center bg-emerald-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-emerald-600 transition-all">
                            <i class='bx bx-user-plus'></i> Crear cuenta con Email
                        </a>

                        <p class="text-xs text-center text-gray-600 mt-3">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" class="text-emerald-600 hover:underline font-semibold">Inicia sesión</a>
                        </p>
                    </div>
                    @endguest

                    <form id="payment-form" @guest style="display:none;" @endguest>
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre del titular
                            </label>
                            <input
                                type="text"
                                id="card-holder-name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="Juan Pérez"
                                required
                            >
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tarjeta de crédito o débito
                            </label>
                            <div id="card-element"></div>
                            <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                        </div>

                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <i class='bx bx-lock-alt text-gray-500'></i>
                                <span>Pago seguro procesado por Stripe</span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            id="submit-button"
                            class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-4 rounded-xl font-bold text-lg hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span id="button-text">
                                @if($planConfig['trial_days'] > 0)
                                Comenzar prueba gratis
                                @else
                                Suscribirse ahora
                                @endif
                            </span>
                            <span id="button-spinner" class="hidden">
                                <i class='bx bx-loader-alt bx-spin'></i> Procesando...
                            </span>
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        Al suscribirte aceptas nuestros
                        <a href="{{ route('legal.terms') }}" class="text-emerald-600 hover:underline">términos y condiciones</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyA8VguwL3jh2lIVpBSRrOvjy-c0PfmGD-4",
            authDomain: "isla-control.firebaseapp.com",
            projectId: "isla-control",
            storageBucket: "isla-control.firebasestorage.app",
            messagingSenderId: "145410754650",
            appId: "1:145410754650:web:8d590e161d280094a6f063",
            measurementId: "G-Z5RWFK99Q8"
        };

        if (firebase.apps.length === 0) {
            window.app = firebase.initializeApp(firebaseConfig);
            window.auth = firebase.auth();
            window.provider = new firebase.auth.GoogleAuthProvider();
        } else {
            window.auth = firebase.auth();
        }

        // Google Sign In Function
        function signInWithGoogle() {
            const provider = new firebase.auth.GoogleAuthProvider();

            Toastify({
                text: "Abriendo Google Sign In...",
                duration: 2000,
                gravity: "top",
                position: "center",
                backgroundColor: "linear-gradient(to right, #00D084, #00B372)",
            }).showToast();

            firebase.auth().signInWithPopup(provider)
                .then((result) => {
                    return result.user.getIdToken().then(idToken => {
                        const user = result.user;

                        return fetch('/login/firebase', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                        Toastify({
                            text: "Inicio de sesión exitoso! Redirigiendo...",
                            duration: 2000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #22C55E, #4ADE80)",
                        }).showToast();

                        // Recargar la página para mostrar el formulario de pago
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Toastify({
                            text: "Error al iniciar sesión: " + (data.error || "Intenta nuevamente"),
                            duration: 3000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                        }).showToast();
                    }
                })
                .catch((error) => {
                    console.error('Error de autenticación:', error);
                    Toastify({
                        text: "Error de autenticación: " + error.message,
                        duration: 3000,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                    }).showToast();
                });
        }

        const stripe = Stripe('{{ config("cashier.key") }}');
        const elements = stripe.elements();

        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#EF4444'
                }
            }
        });

        cardElement.mount('#card-element');

        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const buttonSpinner = document.getElementById('button-spinner');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const cardHolderName = document.getElementById('card-holder-name').value;

            if (!cardHolderName) {
                alert('Por favor ingresa el nombre del titular');
                return;
            }

            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonSpinner.classList.remove('hidden');

            const { setupIntent, error } = await stripe.confirmCardSetup(
                '{{ $intent->client_secret }}',
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardHolderName
                        }
                    }
                }
            );

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                buttonSpinner.classList.add('hidden');
            } else {
                const paymentMethod = setupIntent.payment_method;

                try {
                    const response = await fetch('{{ route("subscription.subscribe", ["plan" => $plan]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            payment_method: paymentMethod
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.error || 'Error al procesar la suscripción');
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonSpinner.classList.add('hidden');
                    }
                } catch (error) {
                    alert('Error de conexión. Por favor intenta de nuevo.');
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonSpinner.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>
