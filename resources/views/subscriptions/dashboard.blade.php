<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Suscripción - IslaControl</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <div class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-emerald-600 hover:text-emerald-700">
                        <i class='bx bx-arrow-back text-xl'></i>
                        <span class="font-semibold">Volver al Dashboard</span>
                    </a>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Mi Suscripción</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex items-center">
                <i class='bx bx-check-circle text-green-500 text-2xl mr-3'></i>
                <p class="text-green-800 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-center">
                <i class='bx bx-error text-red-500 text-2xl mr-3'></i>
                <p class="text-red-800 font-semibold">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex items-center">
                <i class='bx bx-info-circle text-blue-500 text-2xl mr-3'></i>
                <p class="text-blue-800 font-semibold">{{ session('info') }}</p>
            </div>
        </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <!-- Current Plan Card -->
            <div class="md:col-span-2 bg-white rounded-2xl shadow-lg p-6 card">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Plan Actual</h2>
                    @if($subscription->onTrial())
                    <span class="bg-blue-100 text-blue-700 text-sm font-bold px-4 py-2 rounded-full">
                        <i class='bx bx-time-five'></i> EN PRUEBA
                    </span>
                    @elseif($subscription->canceled())
                    <span class="bg-yellow-100 text-yellow-700 text-sm font-bold px-4 py-2 rounded-full">
                        <i class='bx bx-info-circle'></i> CANCELADO
                    </span>
                    @else
                    <span class="bg-green-100 text-green-700 text-sm font-bold px-4 py-2 rounded-full">
                        <i class='bx bx-check-circle'></i> ACTIVO
                    </span>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="text-3xl font-bold text-emerald-600 mb-2">{{ $planConfig['name'] }}</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-gray-800">${{ $planConfig['price'] }}</span>
                        <span class="text-gray-600">/mes</span>
                    </div>
                </div>

                <!-- Plan Features -->
                <div class="grid md:grid-cols-2 gap-3 mb-6">
                    @foreach($planConfig['features'] as $feature)
                    <div class="flex items-center gap-2">
                        <i class='bx bx-check-circle text-emerald-500'></i>
                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-6 space-y-3">
                    @if($subscription->onTrial())
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-blue-900">Período de prueba</p>
                            <p class="text-sm text-blue-700">Termina el {{ $subscription->trial_ends_at->format('d/m/Y') }}</p>
                        </div>
                        <i class='bx bx-gift text-blue-500 text-3xl'></i>
                    </div>
                    @endif

                    @if($subscription->canceled())
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-yellow-900">Suscripción cancelada</p>
                            <p class="text-sm text-yellow-700">Activa hasta el {{ $subscription->ends_at->format('d/m/Y') }}</p>
                        </div>
                        <i class='bx bx-time text-yellow-500 text-3xl'></i>
                    </div>
                    @else
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-900">Próximo cobro</p>
                            <p class="text-sm text-gray-700">
                                @if($subscription->onTrial())
                                    {{ $subscription->trial_ends_at->format('d/m/Y') }}
                                @else
                                    {{ now()->addMonth()->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <i class='bx bx-calendar text-gray-500 text-3xl'></i>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 card">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Acciones</h3>

                <div class="space-y-3">
                    <!-- View Invoices -->
                    <a href="{{ route('subscription.invoices') }}" class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all group">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-receipt text-2xl text-gray-600 group-hover:text-emerald-600'></i>
                            <span class="font-semibold text-gray-700 group-hover:text-emerald-700">Ver Facturas</span>
                        </div>
                        <i class='bx bx-chevron-right text-gray-400 group-hover:text-emerald-500'></i>
                    </a>

                    <!-- Update Payment Method -->
                    <button onclick="alert('Función en desarrollo')" class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-credit-card text-2xl text-gray-600 group-hover:text-blue-600'></i>
                            <span class="font-semibold text-gray-700 group-hover:text-blue-700">Actualizar Tarjeta</span>
                        </div>
                        <i class='bx bx-chevron-right text-gray-400 group-hover:text-blue-500'></i>
                    </button>

                    @if($subscription->canceled())
                    <!-- Resume Subscription -->
                    <form action="{{ route('subscription.resume') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-4 border-2 border-green-500 bg-green-50 rounded-xl hover:bg-green-100 transition-all group">
                            <div class="flex items-center gap-3">
                                <i class='bx bx-play-circle text-2xl text-green-600'></i>
                                <span class="font-semibold text-green-700">Reanudar Suscripción</span>
                            </div>
                            <i class='bx bx-chevron-right text-green-500'></i>
                        </button>
                    </form>
                    @else
                    <!-- Cancel Subscription -->
                    <button onclick="confirmCancel()" class="w-full flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-red-500 hover:bg-red-50 transition-all group">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-x-circle text-2xl text-gray-600 group-hover:text-red-600'></i>
                            <span class="font-semibold text-gray-700 group-hover:text-red-700">Cancelar Suscripción</span>
                        </div>
                        <i class='bx bx-chevron-right text-gray-400 group-hover:text-red-500'></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Change Plan Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Cambiar de Plan</h2>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $allPlans = config('plans');
                @endphp

                @foreach($allPlans as $planKey => $planData)
                <div class="border-2 rounded-xl p-6 transition-all {{ $plan === $planKey ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-300' }}">

                    @if($plan === $planKey)
                    <div class="flex justify-between items-center mb-4">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            PLAN ACTUAL
                        </span>
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $planData['name'] }}</h3>

                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-800">${{ $planData['price'] }}</span>
                        <span class="text-gray-600">/mes</span>
                    </div>

                    <div class="space-y-2 mb-6">
                        @foreach(array_slice($planData['features'], 0, 4) as $feature)
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check text-emerald-500'></i>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                        @if(count($planData['features']) > 4)
                        <p class="text-xs text-gray-500 mt-2">+ {{ count($planData['features']) - 4 }} más...</p>
                        @endif
                    </div>

                    @if($plan === $planKey)
                    <button disabled class="w-full bg-gray-300 text-gray-600 px-4 py-3 rounded-lg font-semibold cursor-not-allowed">
                        Plan Actual
                    </button>
                    @else
                    <form action="{{ route('subscription.swap', $planKey) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full gradient-bg text-white px-4 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            @if($planData['price'] > $planConfig['price'])
                                Mejorar a este plan
                            @else
                                Cambiar a este plan
                            @endif
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class='bx bx-info-circle text-blue-600 text-xl'></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Sobre los cambios de plan</p>
                        <p>• Si mejoras tu plan, se hace un cargo prorrateado inmediato.</p>
                        <p>• Si reduces tu plan, el cambio se aplica al final del período actual.</p>
                        <p>• No perderás acceso a tus datos al cambiar de plan.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Cancel Confirmation Modal -->
    <div id="cancel-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-error text-red-500 text-4xl'></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">¿Cancelar suscripción?</h3>
                <p class="text-gray-600">Tu suscripción seguirá activa hasta el final del período actual.</p>
            </div>

            <div class="space-y-3">
                <form action="{{ route('subscription.cancel') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600 transition">
                        Sí, cancelar suscripción
                    </button>
                </form>
                <button onclick="closeModal()" class="w-full bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                    No, mantener suscripción
                </button>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel() {
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('cancel-modal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('cancel-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

</body>
</html>
