<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes y Precios - IslaControl</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
        }

        .plan-card {
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
                        <span class="font-semibold">Volver</span>
                    </a>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Planes y Precios</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-12">

        <!-- Header Section -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Elige el plan perfecto para tu negocio</h2>
            <p class="text-xl text-gray-600">Todos los planes incluyen Isla IA y soporte completo</p>
        </div>

        <!-- Plans Grid -->
        <div class="grid md:grid-cols-3 gap-8 mb-12">

            @foreach($plans as $key => $plan)
            <div class="plan-card bg-white rounded-2xl shadow-lg overflow-hidden {{ $key === 'pro' ? 'border-4 border-emerald-500 relative' : 'border-2 border-gray-200' }}">

                @if($key === 'pro')
                <div class="absolute top-0 left-0 right-0">
                    <div class="bg-emerald-500 text-white text-center py-2 text-sm font-bold">
                        ⭐ MÁS POPULAR
                    </div>
                </div>
                <div class="pt-12 px-6 pb-6">
                @else
                <div class="p-6">
                @endif

                    <!-- Plan Header -->
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                            {{ $key === 'basico' ? 'bg-gray-100' : ($key === 'pro' ? 'bg-emerald-100' : 'bg-purple-100') }}">
                            <i class='bx {{ $key === 'basico' ? 'bx-gift text-gray-600' : ($key === 'pro' ? 'bx-rocket text-emerald-600' : 'bx-crown text-purple-600') }} text-3xl'></i>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $plan['name'] }}</h3>

                        <div class="mb-2">
                            <span class="text-4xl font-bold text-gray-800">${{ $plan['price'] }}</span>
                            <span class="text-gray-600">/mes</span>
                        </div>

                        @if($plan['trial_days'] > 0)
                        <p class="text-sm text-emerald-600 font-semibold">
                            {{ $plan['trial_days'] }} días de prueba gratis
                        </p>
                        @endif
                    </div>

                    <!-- Features List -->
                    <div class="space-y-3 mb-6">
                        @foreach($plan['features'] as $feature)
                        <div class="flex items-start gap-3">
                            <i class='bx bx-check-circle text-emerald-500 text-xl flex-shrink-0 mt-0.5'></i>
                            <span class="text-sm text-gray-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- CTA Button -->
                    <a href="{{ route('subscription.checkout', $key) }}"
                       class="block w-full text-center px-6 py-4 rounded-xl font-bold text-lg transition-all
                       {{ $key === 'pro' ? 'gradient-bg text-white hover:shadow-xl' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Seleccionar {{ $plan['name'] }}
                    </a>

                </div>
            </div>
            @endforeach

        </div>

        <!-- Comparison Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-12">
            <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
                <h3 class="text-2xl font-bold text-white text-center">Comparación Detallada de Planes</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Característica</th>
                            @foreach($plans as $key => $plan)
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">{{ $plan['name'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Usuarios</td>
                            @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center text-sm text-gray-700">
                                {{ $plan['limits']['users'] ?? '∞' }}
                            </td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Productos</td>
                            @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center text-sm text-gray-700">
                                {{ $plan['limits']['products'] ? number_format($plan['limits']['products']) : 'Ilimitados' }}
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Clientes</td>
                            @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center text-sm text-gray-700">
                                {{ $plan['limits']['customers'] ? number_format($plan['limits']['customers']) : 'Ilimitados' }}
                            </td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Ventas por mes</td>
                            @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center text-sm text-gray-700">
                                {{ $plan['limits']['sales_per_month'] ? number_format($plan['limits']['sales_per_month']) : 'Ilimitadas' }}
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Isla IA</td>
                            @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center">
                                <i class='bx bx-check text-emerald-500 text-2xl'></i>
                            </td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Reportes</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Básicos</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Avanzados</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Premium</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Soporte</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Email</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Email</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">Prioritario</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Múltiples Sucursales</td>
                            <td class="px-6 py-4 text-center">
                                <i class='bx bx-x text-gray-400 text-2xl'></i>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <i class='bx bx-x text-gray-400 text-2xl'></i>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <i class='bx bx-check text-emerald-500 text-2xl'></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-800 text-center mb-8">Preguntas Frecuentes</h3>

            <div class="space-y-6 max-w-3xl mx-auto">
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class='bx bx-help-circle text-emerald-500'></i>
                        ¿Puedo cambiar de plan en cualquier momento?
                    </h4>
                    <p class="text-gray-600 pl-7">
                        Sí, puedes cambiar tu plan cuando quieras. Si mejoras, se hace un cargo prorrateado inmediato. Si reduces tu plan, el cambio se aplica al final del período actual.
                    </p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class='bx bx-help-circle text-emerald-500'></i>
                        ¿Cómo funciona la prueba gratuita?
                    </h4>
                    <p class="text-gray-600 pl-7">
                        El Plan Básico incluye 30 días de prueba gratis. No se te cobrará nada durante este período. Puedes cancelar en cualquier momento sin costo.
                    </p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class='bx bx-help-circle text-emerald-500'></i>
                        ¿Qué métodos de pago aceptan?
                    </h4>
                    <p class="text-gray-600 pl-7">
                        Aceptamos todas las tarjetas de crédito y débito principales a través de Stripe, nuestro procesador de pagos seguro.
                    </p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class='bx bx-help-circle text-emerald-500'></i>
                        ¿Puedo cancelar mi suscripción?
                    </h4>
                    <p class="text-gray-600 pl-7">
                        Sí, puedes cancelar tu suscripción en cualquier momento desde tu dashboard. Tu suscripción seguirá activa hasta el final del período que ya pagaste.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class='bx bx-help-circle text-emerald-500'></i>
                        ¿Qué pasa con mis datos si cancelo?
                    </h4>
                    <p class="text-gray-600 pl-7">
                        Tus datos se mantienen seguros por 30 días después de cancelar. Puedes reactivar tu cuenta en cualquier momento durante ese período sin perder información.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-xl p-8 text-center text-white">
            <h3 class="text-3xl font-bold mb-3">¿Listo para comenzar?</h3>
            <p class="text-white/90 text-lg mb-6">Únete a miles de negocios que confían en IslaControl</p>
            <a href="{{ route('subscription.checkout', 'basico') }}"
               class="inline-block bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all">
                Comenzar prueba gratuita
            </a>
        </div>

    </div>

</body>
</html>
