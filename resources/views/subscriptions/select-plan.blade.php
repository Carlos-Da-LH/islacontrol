<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecciona tu Plan - IslaControl</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .plan-card {
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen py-12 px-4">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-4">
                    <i class='bx bx-gift text-4xl text-emerald-600'></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">¡Bienvenido a IslaControl! 🎉</h1>
                <p class="text-xl text-gray-600 mb-2">Elige tu plan y comienza con <strong>30 días gratis</strong></p>
                <p class="text-gray-500">Sin compromisos, cancela cuando quieras</p>
            </div>

            <!-- Plans Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                @php
                    $allPlans = config('plans');
                    // Excluir el plan gratuito de la visualización
                    $paidPlans = array_filter($allPlans, function($key) {
                        return $key !== 'free';
                    }, ARRAY_FILTER_USE_KEY);
                @endphp

                @foreach($paidPlans as $planKey => $planData)
                <div class="plan-card bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-200 {{ $planKey === 'pro' ? 'border-emerald-500 relative' : '' }}">

                    @if($planKey === 'pro')
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="gradient-bg text-white text-sm font-bold px-6 py-2 rounded-full shadow-lg">
                            MÁS POPULAR ⭐
                        </span>
                    </div>
                    @endif

                    <!-- Plan Name -->
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $planData['name'] }}</h3>

                        <!-- Price -->
                        <div class="mb-4">
                            <span class="text-5xl font-bold text-gray-800">${{ $planData['price'] }}</span>
                            <span class="text-gray-600">/mes</span>
                        </div>

                        <!-- Trial Badge -->
                        @if($planData['trial_days'] > 0)
                        <div class="inline-block bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-2 rounded-full">
                            🎁 {{ $planData['trial_days'] }} días gratis
                        </div>
                        @endif
                    </div>

                    <!-- Features -->
                    <div class="space-y-3 mb-8">
                        @foreach($planData['features'] as $feature)
                        <div class="flex items-start gap-3">
                            <i class='bx bx-check-circle text-emerald-500 text-xl flex-shrink-0'></i>
                            <span class="text-sm text-gray-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- CTA Button -->
                    <a href="{{ route('subscription.checkout', $planKey) }}"
                       class="block w-full text-center {{ $planKey === 'pro' ? 'gradient-bg text-white' : 'bg-gray-800 text-white' }} px-6 py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all">
                        @if($planData['trial_days'] > 0)
                            Comenzar Prueba Gratis
                        @else
                            Seleccionar Plan
                        @endif
                    </a>

                    @if($planData['trial_days'] > 0)
                    <p class="text-xs text-center text-gray-500 mt-3">
                        Luego ${{ $planData['price'] }}/mes. Cancela cuando quieras.
                    </p>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Info Section -->
            <div class="max-w-3xl mx-auto bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <i class='bx bx-info-circle text-blue-600 text-3xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-bold text-blue-900 mb-2">¿Cómo funciona la prueba gratis?</h3>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li>✅ <strong>30 días completamente gratis</strong> - Sin cargos durante el período de prueba</li>
                            <li>✅ <strong>Acceso completo</strong> - Todas las funciones de tu plan seleccionado</li>
                            <li>✅ <strong>Cancela cuando quieras</strong> - Sin compromisos, sin preguntas</li>
                            <li>✅ <strong>Aviso antes de cobrar</strong> - Te recordaremos antes de que termine tu prueba</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Free Plan Option -->
            <div class="max-w-3xl mx-auto mt-8">
                <div class="bg-gray-100 border-2 border-gray-300 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">Plan Gratuito</h3>
                            <p class="text-sm text-gray-600 mb-3">
                                Perfecto para probar IslaControl con funcionalidad limitada
                            </p>
                            <div class="text-xs text-gray-500 space-y-1">
                                <p>• 10 productos</p>
                                <p>• 5 clientes</p>
                                <p>• 20 ventas/mes</p>
                                <p>• Sin Isla IA ni Caja Express</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-semibold transition-colors whitespace-nowrap">
                            Continuar Gratis
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
