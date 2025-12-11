<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caja #{{ $cashRegister->id }} - Detalles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/css/dark-mode.css">
    <script src="/js/dark-mode.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Fondo adaptable al modo */
        body {
            background: radial-gradient(circle at center, #1f2937, #111827);
        }

        .dark body {
            background: radial-gradient(circle at center, #0f172a, #020617);
        }

        /* Adaptar colores para modo claro */
        html:not(.dark) body {
            background: linear-gradient(to bottom, #f3f4f6, #e5e7eb);
            color: #1f2937;
        }

        html:not(.dark) .text-white {
            color: #1f2937 !important;
        }

        html:not(.dark) .bg-gray-800 {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
        }

        html:not(.dark) .bg-gray-700\/50 {
            background-color: #f3f4f6 !important;
        }

        html:not(.dark) .text-gray-300,
        html:not(.dark) .text-gray-400 {
            color: #6b7280 !important;
        }

        html:not(.dark) .border-gray-600,
        html:not(.dark) .border-gray-700 {
            border-color: #d1d5db !important;
        }
    </style>
</head>
<body class="text-white dark:text-white min-h-screen p-4 pt-52 lg:pt-4">

    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-700 mb-6">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('cash-register.index') }}"
                    class="text-gray-400 hover:text-blue-400 transition-colors transform hover:scale-110 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight flex-grow text-center">
                    <i class='bx {{ $cashRegister->status === "open" ? "bx-wallet" : "bx-lock-alt" }} text-4xl {{ $cashRegister->status === "open" ? "text-emerald-500" : "text-red-500" }} mr-2'></i>
                    Caja #{{ $cashRegister->id }}
                </h1>
                <div class="w-10"></div>
            </div>

            <!-- Estado de la Caja -->
            <div class="flex items-center justify-center mb-4">
                @if($cashRegister->status === 'open')
                    <span class="px-6 py-2 bg-emerald-600 text-white rounded-full font-bold text-lg flex items-center">
                        <i class='bx bx-check-circle text-2xl mr-2'></i>
                        Caja Abierta
                    </span>
                @else
                    <span class="px-6 py-2 bg-red-600 text-white rounded-full font-bold text-lg flex items-center">
                        <i class='bx bx-lock-alt text-2xl mr-2'></i>
                        Caja Cerrada
                    </span>
                @endif
            </div>

            <!-- Información General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-700/50 p-3 rounded-lg">
                    <span class="text-gray-400">👤 Usuario:</span>
                    <p class="font-semibold text-white">{{ $cashRegister->user->name }}</p>
                </div>
                <div class="bg-gray-700/50 p-3 rounded-lg">
                    <span class="text-gray-400">📅 Apertura:</span>
                    <p class="font-semibold text-white">{{ $cashRegister->opened_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($cashRegister->status === 'closed')
                <div class="bg-gray-700/50 p-3 rounded-lg">
                    <span class="text-gray-400">🔒 Cierre:</span>
                    <p class="font-semibold text-white">{{ $cashRegister->closed_at ? $cashRegister->closed_at->format('d/m/Y H:i') : 'N/A' }}</p>
                </div>
                <div class="bg-gray-700/50 p-3 rounded-lg">
                    <span class="text-gray-400">⏱️ Duración:</span>
                    <p class="font-semibold text-white">
                        @if($cashRegister->closed_at)
                            {{ $cashRegister->opened_at->diffForHumans($cashRegister->closed_at, true) }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Fondo Inicial -->
            <div class="bg-gray-800 border-2 border-blue-600 rounded-xl p-5 shadow-lg">
                <p class="text-sm text-blue-300 mb-2 flex items-center">
                    <i class='bx bx-donate-heart text-lg mr-1'></i>
                    Fondo Inicial
                </p>
                <p class="text-3xl font-bold text-blue-200">${{ number_format($cashRegister->opening_balance, 2) }}</p>
            </div>

            <!-- Total de Ventas -->
            <div class="bg-gray-800 border-2 border-emerald-600 rounded-xl p-5 shadow-lg">
                <p class="text-sm text-emerald-300 mb-2 flex items-center">
                    <i class='bx bx-bar-chart-alt-2 text-lg mr-1'></i>
                    Total Ventas
                </p>
                <p class="text-3xl font-bold text-emerald-200">${{ number_format($cashRegister->total_sales, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $cashRegister->sales->count() }} ventas</p>
            </div>

            <!-- Efectivo Esperado -->
            <div class="bg-gray-800 border-2 border-yellow-600 rounded-xl p-5 shadow-lg">
                <p class="text-sm text-yellow-300 mb-2 flex items-center">
                    <i class='bx bx-calculator text-lg mr-1'></i>
                    Efectivo Esperado
                </p>
                <p class="text-3xl font-bold text-yellow-200">${{ number_format($cashRegister->calculateExpectedBalance(), 2) }}</p>
            </div>

            <!-- Efectivo Contado / Diferencia -->
            @if($cashRegister->status === 'closed')
                <div class="bg-gray-800 border-2 {{ $cashRegister->difference > 0 ? 'border-green-500' : ($cashRegister->difference < 0 ? 'border-red-500' : 'border-emerald-500') }} rounded-xl p-5 shadow-lg">
                    <p class="text-sm {{ $cashRegister->difference > 0 ? 'text-green-300' : ($cashRegister->difference < 0 ? 'text-red-300' : 'text-emerald-300') }} mb-2 flex items-center">
                        <i class='bx {{ $cashRegister->difference > 0 ? "bx-trending-up" : ($cashRegister->difference < 0 ? "bx-trending-down" : "bx-check-circle") }} text-lg mr-1'></i>
                        {{ $cashRegister->difference > 0 ? 'Sobrante' : ($cashRegister->difference < 0 ? 'Faltante' : 'Perfecto') }}
                    </p>
                    <p class="text-3xl font-bold {{ $cashRegister->difference > 0 ? 'text-green-400' : ($cashRegister->difference < 0 ? 'text-red-400' : 'text-emerald-400') }}">
                        {{ $cashRegister->difference > 0 ? '+' : '' }}${{ number_format($cashRegister->difference, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Contado: ${{ number_format($cashRegister->closing_balance, 2) }}</p>
                </div>
            @else
                <div class="bg-gray-800 border-2 border-gray-600 rounded-xl p-5 shadow-lg">
                    <p class="text-sm text-gray-300 mb-2 flex items-center">
                        <i class='bx bx-time text-lg mr-1'></i>
                        Estado
                    </p>
                    <p class="text-lg font-bold text-gray-400">Caja Abierta</p>
                    <p class="text-xs text-gray-500 mt-1">En operación</p>
                </div>
            @endif
        </div>

        <!-- Desglose por Método de Pago -->
        <div class="bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-700 mb-6">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <i class='bx bx-credit-card text-emerald-500 mr-2'></i>
                Desglose por Método de Pago
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-emerald-900/30 border border-emerald-600/50 rounded-xl p-4">
                    <p class="text-sm text-emerald-300 mb-1">💵 Efectivo</p>
                    <p class="text-2xl font-bold text-emerald-200">${{ number_format($cashRegister->total_cash_sales, 2) }}</p>
                </div>
                <div class="bg-blue-900/30 border border-blue-600/50 rounded-xl p-4">
                    <p class="text-sm text-blue-300 mb-1">💳 Tarjetas</p>
                    <p class="text-2xl font-bold text-blue-200">${{ number_format($cashRegister->total_card_sales, 2) }}</p>
                </div>
                <div class="bg-purple-900/30 border border-purple-600/50 rounded-xl p-4">
                    <p class="text-sm text-purple-300 mb-1">🏦 Transferencias</p>
                    <p class="text-2xl font-bold text-purple-200">${{ number_format($cashRegister->total_transfer_sales, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Notas -->
        @if($cashRegister->opening_notes || $cashRegister->closing_notes)
        <div class="bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-700 mb-6">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <i class='bx bx-note text-yellow-500 mr-2'></i>
                Notas
            </h2>
            @if($cashRegister->opening_notes)
            <div class="mb-4">
                <p class="text-sm text-gray-400 mb-1">📝 Apertura:</p>
                <p class="text-white bg-gray-700/50 p-3 rounded-lg">{{ $cashRegister->opening_notes }}</p>
            </div>
            @endif
            @if($cashRegister->closing_notes)
            <div>
                <p class="text-sm text-gray-400 mb-1">📝 Cierre:</p>
                <p class="text-white bg-gray-700/50 p-3 rounded-lg">{{ $cashRegister->closing_notes }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Lista de Ventas -->
        <div class="bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-700 mb-6">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <i class='bx bx-receipt text-blue-500 mr-2'></i>
                Ventas del Turno ({{ $cashRegister->sales->count() }})
            </h2>

            @if($cashRegister->sales->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-600">
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">#</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Cliente</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Hora</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Método</th>
                                <th class="text-right py-3 px-2 text-gray-400 font-semibold">Monto</th>
                                <th class="text-center py-3 px-2 text-gray-400 font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashRegister->sales->sortByDesc('created_at') as $sale)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition-colors">
                                <td class="py-3 px-2 text-white font-semibold">#{{ $sale->id }}</td>
                                <td class="py-3 px-2 text-white">{{ $sale->customer->name ?? 'N/A' }}</td>
                                <td class="py-3 px-2 text-gray-300">{{ $sale->created_at->format('H:i') }}</td>
                                <td class="py-3 px-2">
                                    @if($sale->payment_method === 'efectivo')
                                        <span class="px-2 py-1 bg-emerald-600 text-white rounded text-xs">💵 Efectivo</span>
                                    @elseif($sale->payment_method === 'tarjeta_debito')
                                        <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs">💳 Débito</span>
                                    @elseif($sale->payment_method === 'tarjeta_credito')
                                        <span class="px-2 py-1 bg-purple-600 text-white rounded text-xs">💳 Crédito</span>
                                    @elseif($sale->payment_method === 'transferencia')
                                        <span class="px-2 py-1 bg-indigo-600 text-white rounded text-xs">🏦 Transfer</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-right text-white font-bold">${{ number_format($sale->amount, 2) }}</td>
                                <td class="py-3 px-2 text-center">
                                    <a href="{{ route('sales.ticket', $sale->id) }}" target="_blank"
                                        class="text-blue-400 hover:text-blue-300 transition-colors">
                                        <i class='bx bx-printer text-xl'></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class='bx bx-cart-alt text-6xl mb-2'></i>
                    <p>No hay ventas registradas en esta caja</p>
                </div>
            @endif
        </div>

        <!-- Acciones -->
        <div class="flex gap-4 mb-6">
            @if($cashRegister->status === 'open')
                <a href="{{ route('cash-register.close-form', $cashRegister->id) }}"
                    class="flex-1 bg-red-600 text-white py-4 rounded-lg hover:bg-red-700 transition-colors font-bold text-lg shadow-lg text-center flex items-center justify-center">
                    <i class='bx bx-lock-alt text-2xl mr-2'></i>
                    Cerrar Caja
                </a>
            @endif
            <a href="{{ route('cash-register.index') }}"
                class="flex-1 bg-gray-600 text-white py-4 rounded-lg hover:bg-gray-700 transition-colors font-semibold text-lg shadow-lg text-center flex items-center justify-center">
                <i class='bx bx-arrow-back text-2xl mr-2'></i>
                Volver al Historial
            </a>
        </div>

    </div>

</body>
</html>
