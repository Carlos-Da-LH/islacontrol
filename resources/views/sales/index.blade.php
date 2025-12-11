<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Ventas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/dark-mode.css">
    <script src="/js/dark-mode.js"></script>
    <style>
        /* Reset completo - eliminar franja blanca */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Rotación del ícono de configuración */
        .rotate-45 {
            transform: rotate(45deg);
        }

        /* Alpine.js x-cloak - ocultar hasta que cargue */
        [x-cloak] {
            display: none !important;
        }


        /* IMPRESIÓN: Solo el ticket */
        @media print {

            /* Ocultar TODA la interfaz */
            body * {
                visibility: hidden !important;
            }

            /* Ocultar el modal overlay y fondo */
            .modal-overlay,
            #ticket-modal {
                background-color: white !important;
                position: static !important;
            }

            /* Ocultar header, botones, configuración */
            .flex.items-center.justify-between,
            button,
            .border-t,
            #settings-panel,
            [x-show],
            nav,
            aside,
            header,
            footer,
            .sidebar {
                display: none !important;
                visibility: hidden !important;
            }

            /* Mostrar SOLO el ticket */
            #ticket-print-content,
            #ticket-print-content * {
                visibility: visible !important;
            }

            #ticket-print-content {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }

            /* Ocultar scrollbar */
            ::-webkit-scrollbar {
                display: none !important;
            }
        }

        .modal-overlay {
            display: none !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: none;
            z-index: 9999 !important;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-overlay.active {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 20px !important;
        }

        .modal-content {
            border-radius: 12px;
            max-width: 1100px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease-out;
            position: relative !important;
            z-index: 10000 !important;
        }

        html:not(.dark) .modal-content {
            background: white;
            color: #111827;
        }

        html.dark .modal-content {
            background: #1f2937;
            color: #f3f4f6;
        }

        .ticket-modal-content {
            max-width: 320px;
            max-height: 80vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .ticket-container {
            background-color: #fff;
            padding: 12px;
            font-size: 0.8em;
            line-height: 1.3;
            color: #34495e;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 0.9em;
        }

        .product-qty-price {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-size: 0.75em;
            color: #7f8c8d;
        }

        .ticket-line {
            border-bottom: 1px dashed #dcdde1;
            margin: 10px 0;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dcdde1;
            position: relative;
        }

        .total-box {
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #c8e6c9;
        }

        /* Logo del ticket - más pequeño */
        .logo {
            max-width: 80px !important;
            max-height: 80px !important;
            width: 64px;
            height: 64px;
            object-fit: contain;
        }

        @media print {

            /* Configuración de página */
            @page {
                size: 80mm auto;
                margin: 0;
            }

            /* Resetear todo el body y html */
            html,
            body {
                width: 80mm !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
                background: white !important;
            }

            /* OCULTAR TODO primero */
            body * {
                visibility: hidden !important;
                display: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Mostrar SOLO el ticket */
            #ticket-print-content {
                visibility: visible !important;
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            #ticket-print-content * {
                visibility: visible !important;
            }

            /* Restaurar display para elementos específicos */
            #ticket-print-content div,
            #ticket-print-content p,
            #ticket-print-content h2,
            #ticket-print-content h3,
            #ticket-print-content h4,
            #ticket-print-content span,
            #ticket-print-content img {
                display: block !important;
            }

            /* Mantener flex donde es necesario */
            #ticket-print-content .product-item,
            #ticket-print-content [style*="display: flex"],
            #ticket-print-content [style*="display:flex"] {
                display: flex !important;
            }

            /* OCULTAR todo lo que NO sea el ticket */
            .no-print,
            #settings-panel,
            button,
            nav,
            aside,
            header,
            footer,
            .sidebar,
            input,
            label,
            [type="file"],
            [type="text"],
            [type="checkbox"],
            [x-data],
            [class*="modal-overlay"],
            .modal-overlay,
            [class*="settings"],
            .border-b,
            .flex.items-center.justify-between {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                width: 0 !important;
                opacity: 0 !important;
                overflow: hidden !important;
            }

            /* Asegurar que SOLO se imprima el ticket */
            #ticket-print-content {
                display: block !important;
                visibility: visible !important;
            }

            #ticket-print-content .no-print,
            #ticket-print-content button {
                display: none !important;
            }

            /* Estilos compactos para el ticket */
            .ticket-container {
                width: 80mm !important;
                padding: 5px !important;
                font-size: 10px !important;
                line-height: 1.2 !important;
                background-color: white !important;
                color: black !important;
            }

            /* Logo MÁS PEQUEÑO para impresión */
            .logo {
                max-width: 20px !important;
                max-height: 20px !important;
                margin: 2px auto !important;
                display: block !important;
            }

            .logo-container {
                margin-bottom: 5px !important;
                padding-bottom: 5px !important;
                position: relative !important;
            }

            /* Reducir todos los espacios */
            #ticket-print-content h2,
            #ticket-print-content h3,
            #ticket-print-content h4 {
                margin: 2px 0 !important;
                padding: 0 !important;
                font-size: 12px !important;
            }

            #ticket-print-content p {
                margin: 1px 0 !important;
                padding: 0 !important;
                font-size: 10px !important;
            }

            .ticket-line {
                margin: 3px 0 !important;
                padding: 0 !important;
            }

            .total-box {
                padding: 3px !important;
                margin: 3px 0 !important;
            }

            /* QR Code pequeño */
            #qrcode-container {
                width: 50px !important;
                height: 50px !important;
                margin: 5px auto !important;
            }

            #qrcode-container img,
            #qrcode-container canvas {
                width: 50px !important;
                height: 50px !important;
                max-width: 50px !important;
                max-height: 50px !important;
            }

            /* Reducir tamaño de productos */
            .product-item {
                margin: 2px 0 !important;
                padding: 1px 0 !important;
                font-size: 9px !important;
            }

            .product-qty-price {
                font-size: 8px !important;
            }

            /* Reducir espacios en impresión */
            h2,
            h3,
            h4 {
                margin: 5px 0 !important;
            }

            p {
                margin: 2px 0 !important;
            }
        }

        /* ============================================
   MODO OSCURO - VENTAS
   ============================================ */

        /* Títulos */
        html.dark h1,
        html.dark h2 {
            color: #F9FAFB !important;
        }

        /* Tabla - Header con fondo claro */
        html.dark table thead tr th {
            color: #1F2937 !important;
            background-color: #F3F4F6 !important;
            font-weight: 700 !important;
        }

        /* Tabla - Cuerpo con fondo oscuro */
        html.dark table tbody {
            background-color: #1F2937 !important;
        }

        /* Tabla - TODO el texto en BLANCO */
        html.dark tbody tr td {
            color: #FFFFFF !important;
            background-color: transparent !important;
        }

        html.dark tbody td,
        html.dark tbody td *,
        html.dark tbody td span,
        html.dark tbody td div {
            color: #FFFFFF !important;
        }

        /* Colores especiales - Monto en verde */
        html.dark tbody td.text-teal-600,
        html.dark tbody .text-teal-600 {
            color: #5EEAD4 !important;
        }

        /* Hover en filas */
        html.dark table tbody tr:hover {
            background-color: rgba(20, 184, 166, 0.2) !important;
        }

        html.dark table tbody tr:hover td,
        html.dark table tbody tr:hover td * {
            color: #FFFFFF !important;
        }

        /* Estado vacío */
        html.dark #empty-state-row td {
            color: #9CA3AF !important;
        }

        html.dark #empty-state-row svg {
            color: #4B5563 !important;
        }

        /* Búsqueda */
        html.dark .bg-white {
            background-color: #1F2937 !important;
        }

        html.dark input#sales-search-input {
            color: #F9FAFB !important;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 h-screen overflow-hidden m-0 p-0">

    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8 h-full overflow-y-auto pt-0 mt-0">

        <!-- Header -->
        <div class="mb-8 print:hidden pt-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-teal-600 dark:text-teal-400 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <path d="M14 2v6h6" />
                        <path d="M8 13h8" />
                        <path d="M8 17h8" />
                    </svg>
                    <h1 id="sales-title" class="text-3xl font-bold text-gray-900 dark:text-white">Gestión de Ventas</h1>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 print:hidden">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium uppercase tracking-wider">Total de Ventas</p>
                        <p id="total-ventas-count" class="text-4xl font-bold mt-2">{{ count($sales) }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase tracking-wider">Monto Total Acumulado</p>
                        <p class="text-4xl font-bold mt-2">$<span id="monto-total-acumulado">0.00</span></p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase tracking-wider">Productos Vendidos</p>
                        <p id="total-products-sold" class="text-4xl font-bold mt-2">0</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase tracking-wider">Promedio por Venta</p>
                        <p class="text-4xl font-bold mt-2">$<span id="average-sale">0.00</span></p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-orange-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Botón Crear Nueva Venta -->
        <div class="mb-6 print:hidden">
            <button onclick="openCreateModal()" class="w-full md:w-auto px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold shadow-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Nueva Venta
            </button>
        </div>

        <!-- Búsqueda -->
        <!-- Búsqueda del lado del cliente -->
        <div class="mb-4 print:hidden">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        id="sales-search-input"
                        placeholder="Buscar por cliente, producto, monto, fecha..."
                        class="flex-1 bg-transparent text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none text-sm">
                </div>
            </div>
        </div>
        <!-- Lista de Ventas -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Lista de Ventas</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Productos</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Notas</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="sales-table-body">
                        @forelse ($sales as $sale)
                        <tr class="hover:bg-gray-50 transition-colors sales-row" data-sale-id="{{ $sale->id }}" data-sale-amount="{{ $sale->amount }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">{{ $sale->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-white">{{ $sale->customer->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="max-w-xs">
                                    @foreach($sale->saleItems as $item)
                                    <div class="text-xs">
                                        {{ $item->product->name ?? 'N/A' }} ({{ $item->quantity }})
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-teal-600">${{ number_format($sale->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="max-w-xs truncate" title="{{ $sale->notes }}">
                                    {{ $sale->notes ? Str::limit($sale->notes, 30) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <button type="button" onclick="viewSale({{ $sale->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors mr-2 text-xs font-medium">
                                    Ver
                                </button>
                                <button type="button" onclick="window.openTicketModal({{ $sale->id }})" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors mr-2 text-xs font-medium">
                                    Ticket
                                </button>
                                <button type="button" onclick="editSale({{ $sale->id }})" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors mr-2 text-xs font-medium">
                                    Editar
                                </button>
                                <button type="button" onclick="deleteSale({{ $sale->id }}, '{{ e($sale->customer->name) }}')" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-xs font-medium">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-state-row">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium">No hay ventas registradas</p>
                                <p class="text-sm text-gray-400">Comienza creando tu primera venta</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Componente de paginación -->


        </div>

        <!-- Modal Crear/Editar Venta -->
        <div id="sale-modal" class="modal-overlay" style="display: none !important; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 999999 !important; background-color: rgba(0, 0, 0, 0.5) !important;">
            <div class="modal-content bg-white dark:bg-gray-800 w-full max-w-4xl mx-4 rounded-lg shadow-2xl" style="max-width: 900px !important; max-height: 75vh !important; overflow-y: auto !important; display: flex !important; flex-direction: column !important;">
                <div class="p-4 sm:p-6 md:p-8" style="overflow-y: auto; flex: 1;"> <!-- Header mejorado con icono -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-teal-100 dark:bg-teal-900/20">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 id="modal-title" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Nueva Venta</h3>
                        </div>
                        <button type="button" onclick="window.closeSaleModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="sale-form" class="space-y-4 sm:space-y-6">
                        <input type="hidden" id="sale-id" name="sale_id">
                        <input type="hidden" id="form-method" value="POST">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cliente *</label>
                                <select id="customer_id" name="customer_id" required class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                        {{ $customer->name == 'Público General' ? 'selected' : '' }}>
                                        ({{ $customer->name === 'Público General' ? '1' : $customer->id }}) {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de Venta *</label>
                                <input type="date" id="sale_date" name="sale_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <!-- Productos Header -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 sm:pt-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Detalle de Productos</h3>
                                <button type="button" id="add-item-button" class="w-full sm:w-auto px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm">
                                    + Añadir Producto
                                </button>
                            </div>

                            <div class="hidden sm:grid grid-cols-12 gap-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 px-2">
                                <div class="col-span-5">Producto</div>
                                <div class="col-span-2 text-center">Cantidad</div>
                                <div class="col-span-2 text-right">Precio</div>
                                <div class="col-span-2 text-right">Subtotal</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div id="sale-items-container" class="space-y-3"></div>
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 sm:pt-6">
                            <div class="flex justify-between items-center">
                                <span class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white">TOTAL:</span>
                                <span id="total-amount" class="text-2xl sm:text-3xl font-bold text-teal-600 dark:text-teal-400">$0.00</span>
                            </div>
                        </div>

                        <!-- Método de Pago -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 sm:pt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Método de Pago *</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3">
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="efectivo" class="peer sr-only" required checked>
                                    <div class="border-2 border-gray-300 dark:border-gray-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 rounded-lg p-2 sm:p-3 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                                        <i class='bx bx-money text-2xl sm:text-3xl text-emerald-600 dark:text-emerald-400'></i>
                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-1">Efectivo</p>
                                    </div>
                                </label>
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="tarjeta_debito" class="peer sr-only" required>
                                    <div class="border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 rounded-lg p-2 sm:p-3 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                                        <i class='bx bx-credit-card text-2xl sm:text-3xl text-blue-600 dark:text-blue-400'></i>
                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-1">Débito</p>
                                    </div>
                                </label>
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="tarjeta_credito" class="peer sr-only" required>
                                    <div class="border-2 border-gray-300 dark:border-gray-600 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 rounded-lg p-2 sm:p-3 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                                        <i class='bx bx-credit-card-alt text-2xl sm:text-3xl text-purple-600 dark:text-purple-400'></i>
                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-1">Crédito</p>
                                    </div>
                                </label>
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="transferencia" class="peer sr-only" required>
                                    <div class="border-2 border-gray-300 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 rounded-lg p-2 sm:p-3 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                                        <i class='bx bx-transfer text-2xl sm:text-3xl text-indigo-600 dark:text-indigo-400'></i>
                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-1">Transfer.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Campos Condicionales para Efectivo -->
                        <div id="cash-payment-fields" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Monto Recibido (Efectivo)</label>
                                <input type="number" id="amount_received" name="amount_received" step="0.01" min="0"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="0.00">
                            </div>
                            <div id="change-display-modal" class="hidden p-4 bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-300 dark:border-yellow-600 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-base sm:text-lg font-semibold text-gray-700 dark:text-gray-300">Cambio:</span>
                                    <span id="change-value-modal" class="text-xl sm:text-2xl font-black text-yellow-600 dark:text-yellow-400">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Campo de Referencia para Tarjetas/Transferencias -->
                        <div id="reference-fields" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Referencia / Número de Transacción (Opcional)</label>
                            <input type="text" id="payment_reference" name="payment_reference" maxlength="100"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Ej: AUTH123456">
                        </div>

                        <!-- Notas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notas Adicionales</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Notas opcionales sobre esta venta..."></textarea>
                        </div>

                        <!-- Mensaje de error/éxito -->
                        <div id="message-area" class="hidden p-4 rounded-lg"></div>

                        <!-- Botones -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button type="submit" id="submit-button" class="flex-1 bg-teal-600 text-white py-2.5 sm:py-3 rounded-lg hover:bg-teal-700 transition-colors font-semibold text-base sm:text-lg shadow-lg hover:shadow-xl">
                                Guardar Venta
                            </button>
                            <button type="button" onclick="window.closeSaleModal()" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-2.5 sm:py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-semibold text-base sm:text-lg shadow-lg hover:shadow-xl">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Ver Detalles -->
        <div id="view-modal" class="modal-overlay" style="display: none !important; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 999999 !important; background-color: rgba(0, 0, 0, 0.5) !important;">
            <div class="modal-content bg-white dark:bg-gray-800 w-full max-w-4xl mx-4 rounded-lg shadow-2xl">
                <div class="p-4 sm:p-6 md:p-8">
                    <!-- Header mejorado con icono -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-blue-100 dark:bg-blue-900/20">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Detalle de Venta</h3>
                        </div>
                        <button type="button" onclick="window.closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div id="view-modal-content"></div>
                </div>
            </div>
        </div>

        <!-- Modal Ticket -->
        <div id="ticket-modal" class="modal-overlay" style="display: none !important; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 9999 !important; background-color: rgba(0, 0, 0, 0.5) !important;">
            <div class="modal-content ticket-modal-content bg-white dark:bg-gray-800 w-full max-w-lg mx-4 rounded-lg shadow-2xl" style="max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;" x-data="{ showConfig: false }">
                <!-- Header del Modal - INTEGRADO -->
                <div class="flex items-center justify-between px-4 sm:px-5 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/20">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">Ticket de Venta</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Botón de Configuración con Alpine.js -->
                        <button type="button" @click="showConfig = !showConfig" onclick="toggleTicketSettings()" id="config-toggle-btn" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group" title="Configurar Ticket">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-all duration-300" :class="{ 'rotate-45': showConfig }" id="config-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <!-- Botón Cerrar -->
                        <button type="button" onclick="closeTicketModal()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del ticket - SCROLLABLE SIN PADDING EXTRA -->
                <div style="flex: 1; overflow-y: auto; overflow-x: hidden;" class="p-4">
                    <!-- Panel de Configuración con Alpine.js - OCULTO por defecto -->
                    <div x-show="showConfig" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" id="settings-panel" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-700" style="display: none;">
                        <h4 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Configuración del Ticket
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo del Negocio</label>
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img id="logo-preview" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%2314b8a6' width='100' height='100'/%3E%3Ctext x='50' y='55' font-size='40' fill='white' text-anchor='middle' font-family='Arial'%3EMN%3C/text%3E%3C/svg%3E" class="w-16 h-16 rounded-lg border-2 border-gray-300 dark:border-gray-600 object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="logo-upload" accept="image/*" class="hidden" onchange="handleLogoUpload(event)">
                                        <button type="button" onclick="document.getElementById('logo-upload').click()" class="w-full px-3 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium">
                                            Subir Logo
                                        </button>
                                        <button type="button" onclick="resetLogo()" class="w-full px-3 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 text-xs mt-1">
                                            Restaurar Logo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Negocio</label>
                                <input type="text" id="business-name" value="MI NEGOCIO" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" oninput="updateTicketInfo()">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                                <input type="text" id="business-address" value="Calle Principal #123" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" oninput="updateTicketInfo()">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                                <input type="text" id="business-phone" value="(555) 123-4567" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" oninput="updateTicketInfo()">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RFC</label>
                                <input type="text" id="business-rfc" value="ABC123456XYZ" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" oninput="updateTicketInfo()">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje del pie</label>
                                <input type="text" id="footer-message" value="Este ticket no es válido como factura fiscal" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" oninput="updateTicketInfo()">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje adicional (opcional)</label>
                                <input type="text" id="extra-message" value="" placeholder="Ej: Solicite su factura en..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white" oninput="updateTicketInfo()">
                            </div>
                            <div>
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" id="show-logo" class="mr-2" onchange="updateTicketInfo()" checked>
                                    Mostrar Logo
                                </label>
                            </div>
                            <!-- Botón Guardar Configuración -->
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" onclick="saveBusinessSettings()" id="save-settings-btn" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    <span>Guardar Configuración</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket de Venta - Contenido para Imprimir -->
                    <div id="ticket-print-content">
                        <div class="ticket-container">
                            <div class="logo-container">
                                <!-- Logo con clases Tailwind - TAMAÑO CONTROLADO -->
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%2314b8a6' width='100' height='100'/%3E%3Ctext x='50' y='55' font-size='40' fill='white' text-anchor='middle' font-family='Arial'%3EMN%3C/text%3E%3C/svg%3E" alt="Logo" class="w-16 h-16 max-w-[80px] mx-auto object-contain rounded-lg" id="ticket-logo">

                                <h2 id="ticket-business-name" class="text-center font-bold text-gray-800 dark:text-white mt-2 mb-1">MI NEGOCIO</h2>
                                <p id="ticket-business-address" class="text-center text-xs text-gray-600 dark:text-gray-400">Calle Principal #123</p>
                                <p id="ticket-business-phone" class="text-center text-xs text-gray-600 dark:text-gray-400">Tel: (555) 123-4567</p>
                                <p id="ticket-business-rfc" class="text-center text-xs text-gray-600 dark:text-gray-400">RFC: ABC123456XYZ</p>
                            </div>

                            <div style="text-align: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px dashed #dcdde1;">
                                <p style="margin: 3px 0; font-size: 1em; font-weight: 600;">TICKET DE VENTA</p>
                                <p style="margin: 2px 0; font-size: 0.85em;">Folio: <span id="ticket-folio" style="font-weight: 700;">#0001</span></p>
                                <p style="margin: 2px 0; font-size: 0.75em; color: #7f8c8d;">Fecha: <span id="ticket-date">12/11/2025 14:30</span></p>
                            </div>

                            <div style="margin-bottom: 10px; padding-bottom: 8px;">
                                <p style="font-weight: 600; margin-bottom: 3px; font-size: 0.85em;">Cliente:</p>
                                <p id="ticket-customer" style="margin: 0; font-size: 0.85em; color: #34495e;">-</p>
                            </div>

                            <div class="ticket-line"></div>

                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 5px; font-size: 0.75em; color: #7f8c8d; text-transform: uppercase;">
                                    <span>Producto</span>
                                    <span>Total</span>
                                </div>
                                <div id="ticket-products"></div>
                            </div>

                            <div class="ticket-line"></div>

                            <div class="total-box" style="margin-bottom: 12px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: 700; font-size: 1.1em; color: #2c3e50;">TOTAL:</span>
                                    <span id="ticket-total" style="font-weight: 900; font-size: 2em; color: #27ae60;">$0.00</span>
                                </div>
                            </div>

                            <div style="text-align: center; font-size: 0.7em; color: #7f8c8d; line-height: 1.5; border-top: 1px dashed #dcdde1; padding-top: 10px;">
                                <p style="margin: 3px 0; font-weight: 600;">¡GRACIAS POR SU COMPRA!</p>
                                <p id="ticket-footer-message" style="margin: 3px 0;">Este ticket no es válido como factura fiscal</p>
                                <p id="ticket-extra-message" style="margin: 3px 0; font-size: 0.9em;"></p>
                                <div id="qrcode-container" style="display: flex; justify-content: center; margin-top: 8px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción - FIXED en la parte inferior -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 p-4 sm:p-5 border-t border-gray-200 dark:border-gray-700" style="flex-shrink: 0;">
                    <button type="button" onclick="printTicket()" class="flex-1 bg-teal-600 text-white py-2.5 sm:py-3 rounded-lg hover:bg-teal-700 transition-colors font-semibold flex items-center justify-center text-sm sm:text-base shadow-lg hover:shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimir Ticket
                    </button>
                    <button type="button" onclick="closeTicketModal()" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-2.5 sm:py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-semibold text-sm sm:text-base shadow-lg hover:shadow-xl">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Notification Container -->
        <div id="notification-container"></div>

        <script>
            // CÓDIGO JAVASCRIPT COMPLETO - VERSION 8.1 - FIXED TOTALS CALCULATION
            (function() {
                'use strict';

                console.log('🚀 Iniciando módulo de ventas... VERSION 8.1 [FIXED TOTALS CALCULATION]');

                // ==========================================
                // CORRECCIÓN PRINCIPAL: Extraer datos del objeto paginado
                // ==========================================
                let salesData = @json($sales);

                // Verificar si salesData tiene la estructura de paginación de Laravel
                if (salesData && typeof salesData === 'object' && salesData.data && Array.isArray(salesData.data)) {
                    // Extraer el array real de ventas
                    salesData = salesData.data;
                    console.log('✅ Sales data extraída del objeto paginado:', salesData.length, 'ventas');
                } else if (Array.isArray(salesData)) {
                    console.log('✅ Sales data ya es un array:', salesData.length, 'ventas');
                } else {
                    console.warn('⚠️ Sales data no está en formato esperado, inicializando array vacío');
                    salesData = [];
                }

                const productsData = @json($products);
                const customersData = @json($customers);

                // Normalizar salesData - MEJORADO
                salesData = salesData.map(sale => {
                    const normalizedSale = {
                        id: sale.id,
                        customer_id: sale.customer_id,
                        sale_date: sale.sale_date,
                        amount: parseFloat(sale.amount) || 0,
                        notes: sale.notes || '',
                        sale_items: []
                    };

                    // Normalizar sale_items (puede venir como "sale_items" o "saleItems")
                    const items = sale.sale_items || sale.saleItems || [];

                    if (items && Array.isArray(items)) {
                        normalizedSale.sale_items = items.map(item => ({
                            id: item.id,
                            product_id: item.product_id,
                            product_name: item.product?.name || item.product_name || 'Producto',
                            quantity: parseInt(item.quantity) || 0,
                            price: parseFloat(item.price) || 0
                        }));
                    }

                    return normalizedSale;
                });

                console.log('📦 Sales data normalized:', salesData.length, 'ventas');
                console.log('📊 Sample sale data:', salesData[0]); // Ver estructura de la primera venta

                const formatCurrency = (number) => {
                    try {
                        return number.toLocaleString('es-MX', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    } catch (e) {
                        console.error('Error formatting currency:', e);
                        return '0.00';
                    }
                };

                // ==========================================
                // FUNCIÓN PRINCIPAL: Actualizar totales - CORREGIDA
                // ==========================================
                const updateTableTotals = () => {
                    try {
                        console.log('🔄 Actualizando totales...', salesData.length, 'ventas en salesData');

                        let totalAmount = 0;
                        let totalCount = 0;
                        let totalProducts = 0;

                        if (salesData && Array.isArray(salesData) && salesData.length > 0) {
                            salesData.forEach((sale, index) => {
                                // Validar que la venta tenga amount
                                const saleAmount = parseFloat(sale.amount);

                                if (!isNaN(saleAmount) && saleAmount > 0) {
                                    totalAmount += saleAmount;
                                    totalCount++;
                                    console.log(`  Venta ${index + 1} (ID: ${sale.id}): $${saleAmount.toFixed(2)}`);
                                }

                                // Contar productos vendidos
                                if (sale.sale_items && Array.isArray(sale.sale_items)) {
                                    sale.sale_items.forEach(item => {
                                        const qty = parseInt(item.quantity);
                                        if (!isNaN(qty) && qty > 0) {
                                            totalProducts += qty;
                                        }
                                    });
                                }
                            });
                        } else {
                            console.warn('⚠️ salesData está vacío o no es un array válido');
                        }

                        const average = totalCount > 0 ? totalAmount / totalCount : 0;

                        console.log('📊 Totales calculados:', {
                            totalCount,
                            totalAmount: totalAmount.toFixed(2),
                            totalProducts,
                            average: average.toFixed(2)
                        });

                        // Actualizar el DOM
                        const totalVentasEl = document.getElementById('total-ventas-count');
                        const montoTotalEl = document.getElementById('monto-total-acumulado');
                        const totalProductsEl = document.getElementById('total-products-sold');
                        const averageSaleEl = document.getElementById('average-sale');

                        if (totalVentasEl) {
                            totalVentasEl.textContent = totalCount;
                            console.log('✅ Total ventas actualizado:', totalCount);
                        }
                        if (montoTotalEl) {
                            montoTotalEl.textContent = formatCurrency(totalAmount);
                            console.log('✅ Monto total actualizado:', formatCurrency(totalAmount));
                        }
                        if (totalProductsEl) {
                            totalProductsEl.textContent = totalProducts;
                            console.log('✅ Total productos actualizado:', totalProducts);
                        }
                        if (averageSaleEl) {
                            averageSaleEl.textContent = formatCurrency(average);
                            console.log('✅ Promedio actualizado:', formatCurrency(average));
                        }

                        console.log('✅ Totales actualizados en el DOM exitosamente');
                    } catch (e) {
                        console.error('❌ Error updating totals:', e);
                        console.error('Stack trace:', e.stack);
                    }
                };

                // Función de filtrado
                const filterTableSales = () => {
                    try {
                        const input = document.getElementById('sales-search-input');
                        if (!input) return;

                        const filter = input.value.toLowerCase().trim();
                        const rows = document.querySelectorAll('#sales-table-body .sales-row');

                        if (!filter) {
                            rows.forEach(row => row.style.display = '');
                            updateTableTotals();
                            return;
                        }

                        let visibleCount = 0;
                        let visibleAmount = 0;
                        let visibleProducts = 0;
                        const visibleSaleIds = [];

                        rows.forEach(row => {
                            const id = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                            const customer = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                            const products = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                            const amount = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                            const date = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';
                            const notes = row.querySelector('td:nth-child(6)')?.textContent.toLowerCase() || '';

                            const matches = id.includes(filter) || customer.includes(filter) ||
                                products.includes(filter) || amount.includes(filter) ||
                                date.includes(filter) || notes.includes(filter);

                            row.style.display = matches ? '' : 'none';

                            if (matches) {
                                visibleCount++;
                                const saleId = parseInt(row.getAttribute('data-sale-id'));
                                visibleSaleIds.push(saleId);
                                const saleAmount = parseFloat(row.getAttribute('data-sale-amount'));
                                if (!isNaN(saleAmount)) {
                                    visibleAmount += saleAmount;
                                }
                            }
                        });

                        salesData.forEach(sale => {
                            if (visibleSaleIds.includes(sale.id) && sale.sale_items && Array.isArray(sale.sale_items)) {
                                sale.sale_items.forEach(item => {
                                    visibleProducts += parseInt(item.quantity) || 0;
                                });
                            }
                        });

                        const average = visibleCount > 0 ? visibleAmount / visibleCount : 0;

                        const totalVentasEl = document.getElementById('total-ventas-count');
                        const montoTotalEl = document.getElementById('monto-total-acumulado');
                        const totalProductsEl = document.getElementById('total-products-sold');
                        const averageSaleEl = document.getElementById('average-sale');

                        if (totalVentasEl) totalVentasEl.textContent = visibleCount;
                        if (montoTotalEl) montoTotalEl.textContent = formatCurrency(visibleAmount);
                        if (totalProductsEl) totalProductsEl.textContent = visibleProducts;
                        if (averageSaleEl) averageSaleEl.textContent = formatCurrency(average);
                    } catch (e) {
                        console.error('Error filtering table:', e);
                    }
                };

                // Productos HTML options
                const productOptionsHtml = `
            <option value="">Selecciona un producto</option>
            ${productsData.map(p => `<option value="${p.id}" data-price="${p.price || 0}" data-name="${p.name}">${p.name} ($${parseFloat(p.price || 0).toFixed(2)})</option>`).join('')}
        `;

                let itemIndex = 0;

                function updateTotals() {
                    try {
                        let total = 0;
                        document.querySelectorAll('.item-row').forEach(row => {
                            const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value) || 0;
                            const price = parseFloat(row.querySelector('input[name*="[price]"]')?.value) || 0;
                            const subtotal = quantity * price;
                            const subtotalEl = row.querySelector('.subtotal');
                            if (subtotalEl) {
                                subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
                            }
                            total += subtotal;
                        });
                        const totalEl = document.getElementById('total-amount');
                        if (totalEl) {
                            totalEl.textContent = `$${formatCurrency(total)}`;
                        }
                    } catch (e) {
                        console.error('Error updating totals:', e);
                    }
                }

                function handleProductChange(event) {
                    try {
                        const selectElement = event.target;
                        const itemRow = selectElement.closest('.item-row');
                        const priceInput = itemRow.querySelector('input[name*="[price]"]');
                        const selectedOption = selectElement.options[selectElement.selectedIndex];
                        const price = selectedOption.dataset.price || '0.00';
                        priceInput.value = parseFloat(price).toFixed(2);
                        updateTotals();
                    } catch (e) {
                        console.error('Error handling product change:', e);
                    }
                }

                function addItem(productId = '', quantity = 1, price = 0) {
                    try {
                        const container = document.getElementById('sale-items-container');
                        if (!container) return;

                        const newItem = document.createElement('div');
                        newItem.classList.add('grid', 'grid-cols-12', 'gap-3', 'items-center', 'p-3', 'bg-gray-50', 'rounded-lg', 'border', 'border-gray-200', 'item-row');

                        newItem.innerHTML = `
                    <div class="col-span-5">
                        <select name="sale_items[${itemIndex}][product_id]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 product-select">
                            ${productOptionsHtml}
                        </select>
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="sale_items[${itemIndex}][quantity]" required min="1" value="${quantity}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="sale_items[${itemIndex}][price]" required step="0.01" min="0" value="${parseFloat(price).toFixed(2)}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-right focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="col-span-2 text-right">
                        <span class="subtotal text-sm font-semibold text-teal-600">$0.00</span>
                    </div>
                    <div class="col-span-1 text-center">
                        <button type="button" class="remove-item-button text-red-600 hover:text-red-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                `;

                        container.appendChild(newItem);

                        if (productId) {
                            const select = newItem.querySelector('.product-select');
                            if (select) select.value = productId;
                        }

                        newItem.querySelector('.product-select')?.addEventListener('change', handleProductChange);
                        newItem.querySelector('input[name*="[quantity]"]')?.addEventListener('input', updateTotals);
                        newItem.querySelector('input[name*="[price]"]')?.addEventListener('input', updateTotals);
                        newItem.querySelector('.remove-item-button')?.addEventListener('click', function() {
                            newItem.remove();
                            updateTotals();
                        });

                        itemIndex++;
                        updateTotals();
                    } catch (e) {
                        console.error('Error adding item:', e);
                    }
                }

                window.showNotification = function(message, type = 'success') {
                    try {
                        const container = document.getElementById('notification-container');
                        if (!container) return;

                        const notification = document.createElement('div');
                        notification.className = `notification px-6 py-4 rounded-lg shadow-lg text-white font-medium ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                        notification.textContent = message;
                        container.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                    } catch (e) {
                        console.error('Error showing notification:', e);
                    }
                };

                function showMessage(message, type = 'error') {
                    try {
                        const messageArea = document.getElementById('message-area');
                        if (!messageArea) return;

                        messageArea.textContent = message;
                        messageArea.className = `p-4 rounded-lg ${type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
                        messageArea.classList.remove('hidden');
                        setTimeout(() => messageArea.classList.add('hidden'), 5000);
                    } catch (e) {
                        console.error('Error showing message:', e);
                    }
                }

                window.openCreateModal = function() {
                    try {
                        const modal = document.getElementById('sale-modal');
                        if (!modal) return;

                        document.getElementById('modal-title').textContent = 'Nueva Venta';
                        document.getElementById('submit-button').textContent = 'Guardar Venta';
                        document.getElementById('sale-form').reset();
                        document.getElementById('sale-id').value = '';
                        document.getElementById('form-method').value = 'POST';
                        document.getElementById('sale_date').value = new Date().toISOString().split('T')[0];
                        document.getElementById('sale-items-container').innerHTML = '';
                        document.getElementById('message-area')?.classList.add('hidden');
                        itemIndex = 0;
                        addItem();
                        modal.classList.add('active');
                        modal.style.display = 'flex';
                        modal.style.alignItems = 'center';
                        modal.style.justifyContent = 'center';
                    } catch (e) {
                        console.error('Error opening create modal:', e);
                    }
                };

                window.closeSaleModal = function() {
                    try {
                        const modal = document.getElementById('sale-modal');
                        if (modal) {
                            modal.classList.remove('active');
                            modal.style.display = 'none';
                        }
                    } catch (e) {
                        console.error('Error closing sale modal:', e);
                    }
                };

                window.editSale = async function(id) {
                    try {
                        console.log('✏️ Editing sale:', id);

                        const response = await fetch(`/sales/${id}/edit-data`);
                        const data = await response.json();

                        if (data.success) {
                            const sale = data.sale;

                            document.getElementById('modal-title').textContent = `Editar Venta #${sale.id}`;
                            document.getElementById('submit-button').textContent = 'Guardar Cambios';
                            document.getElementById('sale-id').value = sale.id;
                            document.getElementById('form-method').value = 'PUT';
                            document.getElementById('customer_id').value = sale.customer_id;
                            document.getElementById('sale_date').value = sale.sale_date;
                            document.getElementById('notes').value = sale.notes || '';
                            document.getElementById('message-area')?.classList.add('hidden');

                            document.getElementById('sale-items-container').innerHTML = '';
                            itemIndex = 0;

                            sale.sale_items.forEach(item => {
                                addItem(item.product_id, item.quantity, item.price);
                            });

                            const modal = document.getElementById('sale-modal');
                            modal.classList.add('active');
                            modal.style.display = 'flex';
                            modal.style.alignItems = 'center';
                            modal.style.justifyContent = 'center';
                        } else {
                            window.showNotification('Error al cargar la venta', 'error');
                        }
                    } catch (error) {
                        console.error('Error editing sale:', error);
                        window.showNotification('Error de conexión', 'error');
                    }
                };

                window.viewSale = async function(id) {
                    try {
                        console.log('👁️ Viewing sale:', id);

                        const response = await fetch(`/sales/${id}/view-data`);
                        const data = await response.json();

                        if (data.success) {
                            const sale = data.sale;

                            let itemsHTML = sale.sale_items.map(item => `
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100">${item.product_name}</td>
                            <td class="py-3 px-4 text-sm text-center text-gray-900 dark:text-gray-100">${item.quantity}</td>
                            <td class="py-3 px-4 text-sm text-right text-gray-900 dark:text-gray-100">$${parseFloat(item.price).toFixed(2)}</td>
                            <td class="py-3 px-4 text-sm text-right font-semibold text-teal-600 dark:text-teal-400">$${(item.quantity * item.price).toFixed(2)}</td>
                        </tr>
                    `).join('');

                            const viewContent = document.getElementById('view-modal-content');
                            if (viewContent) {
                                const isDark = document.documentElement.classList.contains('dark');

                                const colors = isDark ? {
                                    cardBg: '#334155',
                                    cardText: '#e2e8f0',
                                    cardLabel: '#cbd5e1',
                                    cardBorder: '#475569',
                                    tableBg: '#475569',
                                    tableHeadBg: '#64748b',
                                    tableBodyBg: '#334155',
                                    tableFootBg: '#475569',
                                    tableText: '#ffffff',
                                    tableHeaderText: '#ffffff',
                                    totalText: '#5eead4',
                                    notesBg: '#334155',
                                    notesText: '#e2e8f0'
                                } : {
                                    cardBg: '#f3f4f6',
                                    cardText: '#111827',
                                    cardLabel: '#6b7280',
                                    cardBorder: '#d1d5db',
                                    tableBg: '#ffffff',
                                    tableHeadBg: '#e5e7eb',
                                    tableBodyBg: '#ffffff',
                                    tableFootBg: '#f3f4f6',
                                    tableText: '#374151',
                                    tableHeaderText: '#374151',
                                    totalText: '#0d9488',
                                    notesBg: '#f9fafb',
                                    notesText: '#1f2937'
                                };

                                viewContent.innerHTML = `
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div style="background-color: ${colors.cardBg}; border: 1px solid ${colors.cardBorder};" class="rounded-lg p-4">
                                        <p style="color: ${colors.cardLabel};" class="text-sm font-medium mb-1">Cliente</p>
                                        <p style="color: ${colors.cardText};" class="text-lg font-bold">${sale.customer_name}</p>
                                    </div>

                                    <div style="background-color: ${colors.cardBg}; border: 1px solid ${colors.cardBorder};" class="rounded-lg p-4">
                                        <p style="color: ${colors.cardLabel};" class="text-sm font-medium mb-1">Fecha de Venta</p>
                                        <p style="color: ${colors.cardText};" class="text-lg font-bold">${new Date(sale.sale_date).toLocaleDateString('es-MX')}</p>
                                    </div>

                                    <div style="background-color: ${colors.cardBg}; border: 2px solid #14b8a6;" class="rounded-lg p-4">
                                        <p style="color: ${colors.cardLabel};" class="text-sm font-medium mb-1">Total Pagado</p>
                                        <p style="color: ${colors.totalText};" class="text-2xl font-bold">$${parseFloat(sale.amount).toFixed(2)}</p>
                                    </div>
                                </div>

                                <div class="border-t pt-6" style="border-color: ${colors.cardBorder};">
                                    <h3 style="color: ${colors.cardText};" class="text-lg font-semibold mb-4">Productos Vendidos</h3>
                                    <div style="background-color: ${colors.tableBg}; border: 1px solid ${colors.cardBorder};" class="overflow-x-auto rounded-lg">
                                        <table class="min-w-full">
                                            <thead style="background-color: ${colors.tableHeadBg}; border-bottom: 2px solid ${colors.cardBorder};">
                                                <tr>
                                                    <th style="color: ${colors.tableHeaderText};" class="px-4 py-3 text-left text-xs font-bold uppercase">Producto</th>
                                                    <th style="color: ${colors.tableHeaderText};" class="px-4 py-3 text-center text-xs font-bold uppercase">Cantidad</th>
                                                    <th style="color: ${colors.tableHeaderText};" class="px-4 py-3 text-right text-xs font-bold uppercase">Precio Unit.</th>
                                                    <th style="color: ${colors.tableHeaderText};" class="px-4 py-3 text-right text-xs font-bold uppercase">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody style="background-color: ${colors.tableBodyBg};">
                                                ${itemsHTML}
                                            </tbody>
                                            <tfoot style="background-color: ${colors.tableFootBg}; border-top: 2px solid ${colors.cardBorder};">
                                                <tr>
                                                    <td colspan="3" style="color: ${colors.tableText};" class="px-4 py-4 text-right text-base font-bold">TOTAL:</td>
                                                    <td style="color: ${colors.totalText};" class="px-4 py-4 text-right text-xl font-bold">$${parseFloat(sale.amount).toFixed(2)}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                ${sale.notes ? `
                                    <div class="border-t pt-6" style="border-color: ${colors.cardBorder};">
                                        <h3 style="color: ${colors.cardText};" class="text-lg font-semibold mb-3">Notas Adicionales</h3>
                                        <div style="background-color: ${colors.notesBg}; border: 1px solid ${colors.cardBorder};" class="rounded-lg p-4">
                                            <p style="color: ${colors.notesText};" class="text-base whitespace-pre-wrap">${sale.notes}</p>
                                        </div>
                                    </div>
                                ` : ''}

                                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                    <button type="button" onclick="window.closeViewModal(); window.editSale(${sale.id})" class="flex-1 px-4 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                                        Editar Venta
                                    </button>
                                </div>
                            </div>
                        `;
                            }

                            const viewModal = document.getElementById('view-modal');
                            viewModal.classList.add('active');
                            viewModal.style.display = 'flex';
                            viewModal.style.alignItems = 'center';
                            viewModal.style.justifyContent = 'center';
                        } else {
                            window.showNotification('Error al cargar la venta', 'error');
                        }
                    } catch (error) {
                        console.error('Error viewing sale:', error);
                        window.showNotification('Error de conexión', 'error');
                    }
                };

                window.closeViewModal = function() {
                    try {
                        const modal = document.getElementById('view-modal');
                        if (modal) {
                            modal.classList.remove('active');
                            modal.style.display = 'none';
                        }
                    } catch (e) {
                        console.error('Error closing view modal:', e);
                    }
                };

                window.deleteSale = async function(id, customerName) {
                    try {
                        const existingModal = document.getElementById('delete-confirm-modal');
                        if (existingModal) {
                            existingModal.remove();
                        }

                        const modalHtml = `
                    <div id="delete-confirm-modal" class="modal-overlay" style="display: flex !important; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 999999 !important; background-color: rgba(0, 0, 0, 0.5) !important; align-items: center !important; justify-content: center !important;">
                        <div class="modal-content bg-white dark:bg-gray-800 rounded-lg shadow-xl" style="max-width: 400px; width: 90%; margin: 1rem;">
                            <div class="p-4 sm:p-6">
                                <div class="text-center mb-4">
                                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-2">Confirmar Eliminación</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">¿Estás seguro de eliminar la venta #${id}?</p>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 font-semibold mt-1">Cliente: ${customerName}</p>
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-2">Esta acción no se puede deshacer</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <button type="button" id="confirm-delete-btn" class="flex-1 bg-red-600 text-white py-2 sm:py-2.5 px-4 rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm shadow-lg hover:shadow-xl">
                                        Eliminar
                                    </button>
                                    <button type="button" id="cancel-delete-btn" class="flex-1 bg-gray-300 dark:bg-gray-gray-600 text-gray-700 dark:text-gray-200 py-2 sm:py-2.5 px-4 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-semibold text-sm shadow-lg hover:shadow-xl">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                        document.body.insertAdjacentHTML('beforeend', modalHtml);

                        const modal = document.getElementById('delete-confirm-modal');
                        const confirmBtn = document.getElementById('confirm-delete-btn');
                        const cancelBtn = document.getElementById('cancel-delete-btn');

                        const closeModal = () => {
                            if (modal) modal.remove();
                        };

                        cancelBtn?.addEventListener('click', closeModal);
                        modal?.addEventListener('click', (e) => {
                            if (e.target === modal) closeModal();
                        });

                        confirmBtn?.addEventListener('click', async () => {
                            if (confirmBtn) {
                                confirmBtn.disabled = true;
                                confirmBtn.textContent = 'Eliminando...';
                            }

                            try {
                                const response = await fetch(`/sales/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                });

                                const data = await response.json();

                                if (data.success) {
                                    closeModal();
                                    window.showNotification('Venta eliminada exitosamente', 'success');

                                    const saleIndex = salesData.findIndex(s => s.id === id);
                                    if (saleIndex !== -1) {
                                        salesData.splice(saleIndex, 1);
                                    }

                                    const row = document.querySelector(`tr[data-sale-id="${id}"]`);
                                    if (row) {
                                        row.style.transition = 'opacity 0.3s';
                                        row.style.opacity = '0';
                                        setTimeout(() => {
                                            row.remove();
                                            updateTableTotals();

                                            if (document.querySelectorAll('#sales-table-body .sales-row').length === 0) {
                                                document.getElementById('sales-table-body').innerHTML = `
                                            <tr id="empty-state-row">
                                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-lg font-medium">No hay ventas registradas</p>
                                                    <p class="text-sm text-gray-400">Comienza creando tu primera venta</p>
                                                </td>
                                            </tr>
                                        `;
                                            }
                                        }, 300);
                                    }
                                } else {
                                    closeModal();
                                    window.showNotification(data.message || 'Error al eliminar la venta', 'error');
                                }
                            } catch (error) {
                                closeModal();
                                window.showNotification('Error de conexión: ' + error.message, 'error');
                                console.error('Error:', error);
                            }
                        });
                    } catch (e) {
                        console.error('Error in deleteSale:', e);
                    }
                };

                // ============================================================
                //  FUNCIONES DEL TICKET DE VENTA
                // ============================================================

                const defaultLogo = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%2314b8a6' width='100' height='100'/%3E%3Ctext x='50' y='55' font-size='40' fill='white' text-anchor='middle' font-family='Arial'%3EMN%3C/text%3E%3C/svg%3E";
                let currentLogoUrl = defaultLogo;

                function toggleSettings() {
                    const panel = document.getElementById('settings-panel');
                    panel.classList.toggle('active');
                }

                function resetLogo() {
                    currentLogoUrl = defaultLogo;
                    const logoPreview = document.getElementById('logo-preview');
                    const ticketLogo = document.getElementById('ticket-logo');
                    const logoUpload = document.getElementById('logo-upload');

                    if (logoPreview) logoPreview.src = defaultLogo;
                    if (ticketLogo) ticketLogo.src = defaultLogo;
                    if (logoUpload) logoUpload.value = '';

                    window.showNotification('Logo restaurado correctamente', 'success');
                }

                function handleLogoUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    if (!file.type.startsWith('image/')) {
                        window.showNotification('Por favor selecciona un archivo de imagen válido', 'error');
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        window.showNotification('La imagen es muy grande. Máximo 2MB', 'error');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = new Image();
                        img.onload = function() {
                            try {
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');
                                let width = img.width;
                                let height = img.height;
                                const maxSize = 300;

                                if (width > height) {
                                    if (width > maxSize) {
                                        height *= maxSize / width;
                                        width = maxSize;
                                    }
                                } else {
                                    if (height > maxSize) {
                                        width *= maxSize / height;
                                        height = maxSize;
                                    }
                                }

                                canvas.width = width;
                                canvas.height = height;
                                ctx.drawImage(img, 0, 0, width, height);
                                const dataUrl = canvas.toDataURL('image/png');
                                currentLogoUrl = dataUrl;

                                const logoPreview = document.getElementById('logo-preview');
                                const ticketLogo = document.getElementById('ticket-logo');
                                if (logoPreview) logoPreview.src = dataUrl;
                                if (ticketLogo) ticketLogo.src = dataUrl;

                                uploadLogoToServer(file);
                                window.showNotification('Logo actualizado correctamente', 'success');
                            } catch (error) {
                                console.error('Error processing image:', error);
                                window.showNotification('Error al procesar la imagen', 'error');
                            }
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }

                function updateTicketInfo() {
                    const businessName = document.getElementById('business-name').value;
                    const businessAddress = document.getElementById('business-address').value;
                    const businessPhone = document.getElementById('business-phone').value;
                    const businessRfc = document.getElementById('business-rfc').value;
                    const footerMessage = document.getElementById('footer-message').value;
                    const extraMessage = document.getElementById('extra-message').value;
                    const showLogo = document.getElementById('show-logo').checked;

                    document.getElementById('ticket-business-name').textContent = businessName;
                    document.getElementById('ticket-business-address').textContent = businessAddress;
                    document.getElementById('ticket-business-phone').textContent = `Tel: ${businessPhone}`;
                    document.getElementById('ticket-business-rfc').textContent = `RFC: ${businessRfc}`;
                    document.getElementById('ticket-footer-message').textContent = footerMessage;
                    document.getElementById('ticket-extra-message').textContent = extraMessage;
                    document.getElementById('ticket-logo').style.display = showLogo ? 'block' : 'none';
                }

                async function loadBusinessSettings() {
                    try {
                        const response = await fetch('/business-settings');
                        const data = await response.json();

                        if (data.success && data.settings) {
                            const settings = data.settings;
                            document.getElementById('business-name').value = settings.business_name || 'MI NEGOCIO';
                            document.getElementById('business-address').value = settings.business_address || '';
                            document.getElementById('business-phone').value = settings.business_phone || '';
                            document.getElementById('business-rfc').value = settings.business_rfc || '';
                            document.getElementById('footer-message').value = settings.footer_message || 'Este ticket no es válido como factura fiscal';
                            document.getElementById('extra-message').value = settings.extra_message || '';
                            document.getElementById('show-logo').checked = settings.show_logo !== false;

                            if (settings.logo_path) {
                                const logoUrl = `/storage/${settings.logo_path}`;
                                const logoPreview = document.getElementById('logo-preview');
                                const ticketLogo = document.getElementById('ticket-logo');
                                if (logoPreview) logoPreview.src = logoUrl;
                                if (ticketLogo) ticketLogo.src = logoUrl;
                                currentLogoUrl = logoUrl;
                            }

                            updateTicketInfo();
                        }
                    } catch (error) {
                        console.error('Error al cargar configuración:', error);
                    }
                }

                async function saveBusinessSettings() {
                    try {
                        const btn = document.getElementById('save-settings-btn');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        const formData = {
                            business_name: document.getElementById('business-name').value,
                            business_address: document.getElementById('business-address').value,
                            business_phone: document.getElementById('business-phone').value,
                            business_rfc: document.getElementById('business-rfc').value,
                            footer_message: document.getElementById('footer-message').value,
                            extra_message: document.getElementById('extra-message').value,
                            show_logo: document.getElementById('show-logo').checked,
                        };

                        const response = await fetch('/business-settings', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(formData),
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.showNotification('✅ Configuración guardada exitosamente', 'success');
                        } else {
                            window.showNotification('❌ Error al guardar la configuración', 'error');
                        }

                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    } catch (error) {
                        console.error('Error al guardar configuración:', error);
                        window.showNotification('❌ Error al guardar la configuración', 'error');
                    }
                }

                async function uploadLogoToServer(file) {
                    try {
                        const formData = new FormData();
                        formData.append('logo', file);

                        const response = await fetch('/business-settings/upload-logo', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData,
                        });

                        const data = await response.json();
                        if (data.success) {
                            currentLogoUrl = data.logo_url;
                        }
                    } catch (error) {
                        console.error('Error al subir logo:', error);
                    }
                }

                window.loadBusinessSettings = loadBusinessSettings;
                window.saveBusinessSettings = saveBusinessSettings;
                window.uploadLogoToServer = uploadLogoToServer;

                window.openTicketModal = async function(saleId) {
                    try {
                        const response = await fetch(`/sales/${saleId}/view-data`);
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        const data = await response.json();

                        if (!data.success) {
                            window.showNotification('Error al cargar la venta', 'error');
                            return;
                        }

                        const sale = data.sale;
                        const folioEl = document.getElementById('ticket-folio');
                        if (folioEl) folioEl.textContent = `#${String(sale.id).padStart(4, '0')}`;

                        let saleDate;
                        try {
                            const dateStr = sale.sale_date.includes('T') ? sale.sale_date : sale.sale_date + 'T12:00:00';
                            saleDate = new Date(dateStr);
                            if (isNaN(saleDate.getTime())) saleDate = new Date();
                        } catch (e) {
                            saleDate = new Date();
                        }

                        const dateEl = document.getElementById('ticket-date');
                        if (dateEl) {
                            dateEl.textContent = saleDate.toLocaleString('es-MX', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }

                        const customerEl = document.getElementById('ticket-customer');
                        if (customerEl) customerEl.textContent = sale.customer_name || 'Cliente';

                        const productsContainer = document.getElementById('ticket-products');
                        if (productsContainer && sale.sale_items && Array.isArray(sale.sale_items)) {
                            const productsHtml = sale.sale_items.map(item => {
                                const qty = parseFloat(item.quantity) || 0;
                                const price = parseFloat(item.price) || 0;
                                const subtotal = qty * price;
                                return `
                            <div class="product-item">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">${item.product_name || 'Producto'}</div>
                                    <div class="product-qty-price">
                                        <span>${qty} x $${price.toFixed(2)}</span>
                                        <span style="font-weight: 600; color: #27ae60;">$${subtotal.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                            }).join('');
                            productsContainer.innerHTML = productsHtml;
                        }

                        const totalEl = document.getElementById('ticket-total');
                        if (totalEl) {
                            const totalAmount = parseFloat(sale.amount) || 0;
                            totalEl.textContent = `$${totalAmount.toFixed(2)}`;
                        }

                        try {
                            const qrContainer = document.getElementById('qrcode-container');
                            if (qrContainer && typeof QRCode !== 'undefined') {
                                qrContainer.innerHTML = '';
                                const qrInfo = `Venta-${sale.id}-$${parseFloat(sale.amount).toFixed(2)}`;
                                new QRCode(qrContainer, {
                                    text: qrInfo,
                                    width: 100,
                                    height: 100,
                                    colorDark: "#000000",
                                    colorLight: "#ffffff",
                                    correctLevel: QRCode.CorrectLevel.M
                                });
                            }
                        } catch (qrError) {
                            console.error('Error generating QR code:', qrError);
                        }

                        const settingsPanel = document.getElementById('settings-panel');
                        if (settingsPanel) {
                            settingsPanel.classList.remove('active');
                            settingsPanel.style.display = 'none';
                        }

                        loadBusinessSettings();

                        const modal = document.getElementById('ticket-modal');
                        if (modal) {
                            modal.classList.add('active');
                            modal.style.display = 'flex';
                            modal.style.alignItems = 'center';
                            modal.style.justifyContent = 'center';

                            setTimeout(() => {
                                if (window.Alpine) {
                                    const alpineElement = modal.querySelector('[x-data]');
                                    if (alpineElement && !alpineElement.__x && window.Alpine.initTree) {
                                        window.Alpine.initTree(modal);
                                    }
                                }
                            }, 100);
                        }
                    } catch (error) {
                        console.error('Error opening ticket:', error);
                        window.showNotification('Error al cargar el ticket: ' + error.message, 'error');
                    }
                };

                function closeTicketModal() {
                    const modal = document.getElementById('ticket-modal');
                    modal.classList.remove('active');
                    modal.style.display = 'none';
                    const settingsPanel = document.getElementById('settings-panel');
                    if (settingsPanel) settingsPanel.style.display = 'none';
                }

                window.toggleTicketSettings = function() {
                    const settingsPanel = document.getElementById('settings-panel');
                    const configIcon = document.getElementById('config-icon');
                    if (settingsPanel) {
                        const isHidden = settingsPanel.style.display === 'none' || !settingsPanel.style.display;
                        if (isHidden) {
                            settingsPanel.style.display = 'block';
                            if (configIcon) configIcon.style.transform = 'rotate(45deg)';
                        } else {
                            settingsPanel.style.display = 'none';
                            if (configIcon) configIcon.style.transform = 'rotate(0deg)';
                        }
                    }
                };

                function printTicket() {
                    window.print();
                }

                window.toggleSettings = toggleSettings;
                window.updateTicketInfo = updateTicketInfo;
                window.closeTicketModal = closeTicketModal;
                window.printTicket = printTicket;
                window.resetLogo = resetLogo;
                window.handleLogoUpload = handleLogoUpload;

                // Inicialización
                const initializePage = () => {
                    try {
                        console.log('🎯 Inicializando página de ventas...');

                        const searchInput = document.getElementById('sales-search-input');
                        if (searchInput) {
                            searchInput.addEventListener('input', filterTableSales);
                        }

                        const addItemBtn = document.getElementById('add-item-button');
                        if (addItemBtn) {
                            addItemBtn.addEventListener('click', () => addItem());
                        }

                        const logoUpload = document.getElementById('logo-upload');
                        if (logoUpload) {
                            logoUpload.addEventListener('change', handleLogoUpload);
                        }

                        const saleForm = document.getElementById('sale-form');
                        if (saleForm) {
                            saleForm.addEventListener('submit', async function(e) {
                                e.preventDefault();

                                const saleId = document.getElementById('sale-id').value;
                                const method = document.getElementById('form-method').value;
                                const isEdit = method === 'PUT';

                                const items = document.querySelectorAll('.item-row');
                                if (items.length === 0) {
                                    showMessage('Debes agregar al menos un producto', 'error');
                                    return;
                                }

                                let hasError = false;
                                items.forEach(item => {
                                    const productSelect = item.querySelector('select[name*="[product_id]"]');
                                    const quantity = item.querySelector('input[name*="[quantity]"]');
                                    if (!productSelect?.value || !quantity?.value || parseFloat(quantity.value) <= 0) {
                                        hasError = true;
                                    }
                                });

                                if (hasError) {
                                    showMessage('Todos los productos deben tener cantidad válida', 'error');
                                    return;
                                }

                                const submitButton = document.getElementById('submit-button');
                                if (submitButton) {
                                    submitButton.disabled = true;
                                    submitButton.textContent = 'Guardando...';
                                }

                                const formData = new FormData(this);

                                try {
                                    const url = isEdit ? `/sales/${saleId}` : '/sales';
                                    const response = await fetch(url, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                            'X-HTTP-Method-Override': method,
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        body: formData
                                    });

                                    const contentType = response.headers.get('content-type');
                                    if (!contentType || !contentType.includes('application/json')) {
                                        throw new Error('El servidor no devolvió una respuesta JSON válida');
                                    }

                                    const data = await response.json();

                                    if (response.ok && data.success) {
                                        window.showNotification(isEdit ? 'Venta actualizada exitosamente' : 'Venta creada exitosamente', 'success');
                                        window.closeSaleModal();
                                        setTimeout(() => window.location.reload(), 1000);
                                    } else {
                                        let errorMessage = data.message || 'Error al guardar la venta';
                                        if (data.errors) {
                                            const errorList = Object.values(data.errors).flat();
                                            errorMessage = errorList.join('\n');
                                        }
                                        showMessage(errorMessage, 'error');
                                        if (submitButton) {
                                            submitButton.disabled = false;
                                            submitButton.textContent = isEdit ? 'Guardar Cambios' : 'Guardar Venta';
                                        }
                                    }
                                } catch (error) {
                                    console.error('Error completo:', error);
                                    showMessage('Error al procesar la solicitud.', 'error');
                                    if (submitButton) {
                                        submitButton.disabled = false;
                                        submitButton.textContent = isEdit ? 'Guardar Cambios' : 'Guardar Venta';
                                    }
                                }
                            });
                        }

                        const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
                        paymentMethodInputs.forEach(input => {
                            input.addEventListener('change', function() {
                                const cashFields = document.getElementById('cash-payment-fields');
                                const referenceFields = document.getElementById('reference-fields');
                                const changeDisplay = document.getElementById('change-display-modal');

                                if (this.value === 'efectivo') {
                                    cashFields.classList.remove('hidden');
                                    referenceFields.classList.add('hidden');
                                } else {
                                    cashFields.classList.add('hidden');
                                    referenceFields.classList.remove('hidden');
                                    changeDisplay.classList.add('hidden');
                                }
                            });
                        });

                        const amountReceivedInput = document.getElementById('amount_received');
                        if (amountReceivedInput) {
                            amountReceivedInput.addEventListener('input', function() {
                                const totalText = document.getElementById('total-amount').textContent.replace('$', '').replace(',', '');
                                const total = parseFloat(totalText) || 0;
                                const received = parseFloat(this.value) || 0;

                                const changeDisplay = document.getElementById('change-display-modal');
                                const changeValue = document.getElementById('change-value-modal');

                                if (received >= total && received > 0) {
                                    const change = received - total;
                                    changeValue.textContent = `$${change.toFixed(2)}`;
                                    changeDisplay.classList.remove('hidden');
                                } else {
                                    changeDisplay.classList.add('hidden');
                                }
                            });
                        }

                        updateTableTotals();
                        console.log('✅ Página inicializada correctamente');
                    } catch (e) {
                        console.error('Error initializing page:', e);
                    }
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initializePage);
                } else {
                    initializePage();
                }

                window.addEventListener('load', function() {
                    if (typeof QRCode === 'undefined') {
                        const script = document.createElement('script');
                        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
                        document.head.appendChild(script);
                    }
                });

                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        setTimeout(() => {
                            updateTableTotals();
                            const searchInput = document.getElementById('sales-search-input');
                            if (searchInput && !searchInput.value.trim()) {
                                document.querySelectorAll('#sales-table-body .sales-row').forEach(row => {
                                    row.style.display = '';
                                });
                            }
                        }, 100);
                    }
                });

                window.addEventListener('focus', function() {
                    setTimeout(updateTableTotals, 100);
                });

                window.addEventListener('load', function() {
                    setTimeout(updateTableTotals, 200);
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        window.closeSaleModal();
                        window.closeViewModal();
                        closeTicketModal();
                    }
                });

                document.getElementById('sale-modal')?.addEventListener('click', function(e) {
                    if (e.target === this) window.closeSaleModal();
                });

                document.getElementById('view-modal')?.addEventListener('click', function(e) {
                    if (e.target === this) window.closeViewModal();
                });

                document.getElementById('ticket-modal')?.addEventListener('click', function(e) {
                    if (e.target === this) closeTicketModal();
                });

            })();

            // EMERGENCY FIX - FORCE MODAL STYLES INLINE (Z-INDEX 999999)
            (function forceModalStyles() {
                const modalIds = ['sale-modal', 'view-modal', 'ticket-modal'];

                modalIds.forEach(id => {
                    const modal = document.getElementById(id);
                    if (modal) {
                        // Forzar estilos inline para GARANTIZAR que aparezcan encima
                        modal.style.position = 'fixed';
                        modal.style.top = '0';
                        modal.style.left = '0';
                        modal.style.right = '0';
                        modal.style.bottom = '0';
                        modal.style.zIndex = '999999';
                        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';

                        console.log(`✅ Modal ${id} forzado con z-index 999999`);
                    }
                });

                console.log('🚨 EMERGENCY FIX APPLIED - Modals forced to z-index 999999');
            })();
        </script>

</body>

</html>