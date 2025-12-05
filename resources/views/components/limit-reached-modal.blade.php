@if(session('limit_reached'))
@php
    $limitData = session('limit_reached');
    $type = $limitData['type'];
    $limit = $limitData['limit'];
    $current = $limitData['current'];

    $typeNames = [
        'product' => 'productos',
        'customer' => 'clientes',
        'sale' => 'ventas este mes'
    ];

    $typeName = $typeNames[$type] ?? $type;

    // Detectar si el usuario tiene suscripción activa
    $hasSubscription = auth()->user() && auth()->user()->subscribed('default');
    $currentPlan = \App\Helpers\PlanHelper::getCurrentPlan();
    $planName = $currentPlan['name'] ?? 'Plan Gratuito';
@endphp

<script>
    console.log('🚫 LÍMITE ALCANZADO - Modal cargado', {
        type: '{{ $type }}',
        limit: {{ $limit }},
        current: {{ $current }},
        hasSubscription: {{ $hasSubscription ? 'true' : 'false' }}
    });
</script>

<div id="limit-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 animate-fadeIn" style="z-index: 9999;">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform animate-scaleIn">
        <!-- Header con gradiente -->
        <div class="bg-gradient-to-r from-orange-500 to-red-600 p-6 text-white">
            <div class="flex items-center justify-center mb-3">
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-center">
                Límite Alcanzado
            </h3>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-gray-700 dark:text-gray-300 text-lg mb-4">
                    Has alcanzado el límite de <span class="font-bold text-orange-600">{{ $limit }} {{ $typeName }}</span> de tu
                    @if($hasSubscription)
                        <span class="font-bold">{{ $planName }}</span>.
                    @else
                        <span class="font-bold">plan gratuito</span>.
                    @endif
                </p>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Uso actual: <span class="font-bold">{{ $current }} / {{ $limit }}</span>
                    </p>
                </div>
            </div>

            @if(!$hasSubscription)
                <!-- Beneficios de mejorar (Solo para usuarios sin suscripción) -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-blue-800 dark:text-blue-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Con un plan premium obtienes:
                    </h4>
                    <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Más productos, clientes y ventas</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Isla IA - Asistente inteligente</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Caja Express y reportes avanzados</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Plan Básico: 30 días de prueba GRATIS</strong></span>
                        </li>
                    </ul>
                </div>

                <!-- Botones para usuarios sin suscripción -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('subscription.plans') }}"
                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-bold text-center hover:from-blue-700 hover:to-blue-800 transition-all transform hover:scale-105 shadow-lg">
                        🎁 Ver Planes y Activar Prueba Gratis
                    </a>
                    <button onclick="document.getElementById('limit-modal').remove()"
                            class="w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cerrar
                    </button>
                </div>
            @else
                <!-- Mensaje para usuarios con suscripción activa -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-yellow-800 dark:text-yellow-300 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ¿Necesitas más capacidad?
                    </h4>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        Considera actualizar a un plan superior para obtener límites más altos o ilimitados.
                    </p>
                </div>

                <!-- Botones para usuarios con suscripción -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('subscription.plans') }}"
                       class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-lg font-bold text-center hover:from-emerald-700 hover:to-emerald-800 transition-all transform hover:scale-105 shadow-lg">
                        📈 Actualizar Plan
                    </a>
                    <button onclick="document.getElementById('limit-modal').remove()"
                            class="w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cerrar
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}

.animate-scaleIn {
    animation: scaleIn 0.3s ease-out;
}
</style>
@endif
