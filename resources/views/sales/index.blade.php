<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Ventas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 1100px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease-out;
        }

        .ticket-modal-content {
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            padding: 20px;
            font-size: 0.9em;
            line-height: 1.5;
            color: #34495e;
        }
        
        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 0.95em;
        }
        
        .product-qty-price {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-size: 0.8em;
            color: #7f8c8d;
        }
        
        .ticket-line {
            border-bottom: 1px dashed #dcdde1;
            margin: 15px 0;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dcdde1;
            position: relative;
        }
        
        .total-box {
            background-color: #e8f5e9;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #c8e6c9;
        }

        .logo {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 4px;
        }

        .settings-toggle {
            position: absolute;
            top: 0;
            right: 0;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .settings-toggle:hover {
            background-color: #f3f4f6;
        }

        .settings-panel {
            display: none;
            background-color: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .settings-panel.active {
            display: block;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #ticket-print-content,
            #ticket-print-content * {
                visibility: visible;
            }
            #ticket-print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
            }
            .no-print {
                display: none !important;
            }
            .settings-toggle {
                display: none !important;
            }
            .settings-panel {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-teal-600 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <path d="M14 2v6h6" />
                        <path d="M8 13h8" />
                        <path d="M8 17h8" />
                    </svg>
                    <h1 class="text-3xl font-bold text-gray-800">Gestión de Ventas</h1>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
        <div class="mb-6">
            <button onclick="openCreateModal()" class="w-full md:w-auto px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold shadow-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Nueva Venta
            </button>
        </div>

        <!-- Búsqueda -->
        <div class="bg-white rounded-xl shadow-md mb-6 p-4 border border-gray-200">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="sales-search-input" placeholder="Buscar por ID, Cliente, Productos, Monto, Fecha o Notas..." class="w-full bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none">
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
                    <tbody class="bg-white divide-y divide-gray-200" id="sales-table-body">
                        @forelse ($sales as $sale)
                        <tr class="hover:bg-gray-50 transition-colors sales-row" data-sale-id="{{ $sale->id }}" data-sale-amount="{{ $sale->amount }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $sale->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $sale->customer->name }}</td>
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
                                <button onclick="viewSale({{ $sale->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors mr-2 text-xs font-medium">
                                    Ver
                                </button>
                                <button onclick="window.openTicketModal({{ $sale->id }})" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors mr-2 text-xs font-medium">
                                    Ticket
                                </button>
                                <button onclick="editSale({{ $sale->id }})" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors mr-2 text-xs font-medium">
                                    Editar
                                </button>
                                <button onclick="deleteSale({{ $sale->id }}, '{{ e($sale->customer->name) }}')" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-xs font-medium">
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
        </div>

    </div>

    <!-- Modal Crear/Editar Venta -->
    <div id="sale-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="modal-title" class="text-2xl font-bold text-gray-800">Nueva Venta</h3>
                    <button onclick="window.closeSaleModal()" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="sale-form" class="space-y-6">
                    <input type="hidden" id="sale-id" name="sale_id">
                    <input type="hidden" id="form-method" value="POST">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                            <select id="customer_id" name="customer_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                <option value="">Seleccionar...</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" data-name="{{ $customer->name }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Venta *</label>
                            <input type="date" id="sale_date" name="sale_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Productos Header -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Detalle de Productos</h3>
                            <button type="button" id="add-item-button" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm">
                                + Añadir Producto
                            </button>
                        </div>

                        <div class="grid grid-cols-12 gap-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-2">
                            <div class="col-span-5">Producto</div>
                            <div class="col-span-2 text-center">Cantidad</div>
                            <div class="col-span-2 text-right">Precio</div>
                            <div class="col-span-2 text-right">Subtotal</div>
                            <div class="col-span-1"></div>
                        </div>

                        <div id="sale-items-container" class="space-y-3"></div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">TOTAL:</span>
                            <span id="total-amount" class="text-3xl font-bold text-teal-600">$0.00</span>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Notas opcionales sobre esta venta..."></textarea>
                    </div>

                    <!-- Mensaje de error/éxito -->
                    <div id="message-area" class="hidden p-4 rounded-lg"></div>

                    <!-- Botones -->
                    <div class="flex gap-4">
                        <button type="submit" id="submit-button" class="flex-1 bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 transition-colors font-semibold text-lg shadow-lg">
                            Guardar Venta
                        </button>
                        <button type="button" onclick="window.closeSaleModal()" class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 transition-colors font-semibold text-lg shadow-lg">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Detalles -->
    <div id="view-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Detalle de Venta</h3>
                    <button onclick="window.closeViewModal()" class="text-gray-400 hover:text-gray-600">
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
    <div id="ticket-modal" class="modal-overlay">
        <div class="modal-content ticket-modal-content">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6 no-print">
                    <h3 class="text-2xl font-bold text-gray-800">Ticket de Venta</h3>
                    <button onclick="closeTicketModal()" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="ticket-print-content">
                    <div class="ticket-container">
                        <div class="logo-container">
                            <div class="settings-toggle no-print" onclick="toggleSettings()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            
                            <div id="settings-panel" class="settings-panel no-print">
                                <h4 class="font-bold text-gray-800 mb-3">Configuración del Ticket</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo del Negocio</label>
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                <img id="logo-preview" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%2314b8a6' width='100' height='100'/%3E%3Ctext x='50' y='55' font-size='40' fill='white' text-anchor='middle' font-family='Arial'%3EMN%3C/text%3E%3C/svg%3E" class="w-16 h-16 rounded-lg border-2 border-gray-300 object-cover">
                                            </div>
                                            <div class="flex-1">
                                                <input type="file" id="logo-upload" accept="image/*" class="hidden">
                                                <button type="button" onclick="document.getElementById('logo-upload').click()" class="w-full px-3 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium">
                                                    Subir Logo
                                                </button>
                                                <button type="button" onclick="resetLogo()" class="w-full px-3 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-xs mt-1">
                                                    Restaurar Logo
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Negocio</label>
                                        <input type="text" id="business-name" value="MI NEGOCIO" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateTicketInfo()">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                        <input type="text" id="business-address" value="Calle Principal #123" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateTicketInfo()">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                        <input type="text" id="business-phone" value="(555) 123-4567" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateTicketInfo()">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">RFC</label>
                                        <input type="text" id="business-rfc" value="ABC123456XYZ" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateTicketInfo()">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje del pie</label>
                                        <input type="text" id="footer-message" value="Este ticket no es válido como factura fiscal" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateTicketInfo()">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje adicional (opcional)</label>
                                        <input type="text" id="extra-message" value="" placeholder="Ej: Solicite su factura en..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateTicketInfo()">
                                    </div>
                                    <div>
                                        <label class="flex items-center text-sm text-gray-700">
                                            <input type="checkbox" id="show-logo" class="mr-2" onchange="updateTicketInfo()" checked>
                                            Mostrar Logo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%2314b8a6' width='100' height='100'/%3E%3Ctext x='50' y='55' font-size='40' fill='white' text-anchor='middle' font-family='Arial'%3EMN%3C/text%3E%3C/svg%3E" alt="Logo" class="logo" id="ticket-logo">
                            
                            <h2 id="ticket-business-name" style="margin: 10px 0 5px 0; font-size: 1.4em; font-weight: 700; color: #2c3e50;">MI NEGOCIO</h2>
                            <p id="ticket-business-address" style="margin: 2px 0; font-size: 0.85em; color: #7f8c8d;">Calle Principal #123</p>
                            <p id="ticket-business-phone" style="margin: 2px 0; font-size: 0.85em; color: #7f8c8d;">Tel: (555) 123-4567</p>
                            <p id="ticket-business-rfc" style="margin: 2px 0; font-size: 0.85em; color: #7f8c8d;">RFC: ABC123456XYZ</p>
                        </div>

                        <div style="text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #dcdde1;">
                            <p style="margin: 5px 0; font-size: 1.1em; font-weight: 600;">TICKET DE VENTA</p>
                            <p style="margin: 3px 0; font-size: 0.9em;">Folio: <span id="ticket-folio" style="font-weight: 700;">#0001</span></p>
                            <p style="margin: 3px 0; font-size: 0.85em; color: #7f8c8d;">Fecha: <span id="ticket-date">12/11/2025 14:30</span></p>
                        </div>

                        <div style="margin-bottom: 15px; padding-bottom: 10px;">
                            <p style="font-weight: 600; margin-bottom: 5px; font-size: 0.95em;">Cliente:</p>
                            <p id="ticket-customer" style="margin: 0; font-size: 0.95em; color: #34495e;">-</p>
                        </div>

                        <div class="ticket-line"></div>

                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 8px; font-size: 0.85em; color: #7f8c8d; text-transform: uppercase;">
                                <span>Producto</span>
                                <span>Total</span>
                            </div>
                            <div id="ticket-products"></div>
                        </div>

                        <div class="ticket-line"></div>

                        <div class="total-box" style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 700; font-size: 1.1em; color: #2c3e50;">TOTAL:</span>
                                <span id="ticket-total" style="font-weight: 700; font-size: 1.5em; color: #27ae60;">$0.00</span>
                            </div>
                        </div>

                        <div style="text-align: center; font-size: 0.8em; color: #7f8c8d; line-height: 1.6; border-top: 1px dashed #dcdde1; padding-top: 15px;">
                            <p style="margin: 5px 0; font-weight: 600;">¡GRACIAS POR SU COMPRA!</p>
                            <p id="ticket-footer-message" style="margin: 5px 0;">Este ticket no es válido como factura fiscal</p>
                            <p id="ticket-extra-message" style="margin: 5px 0; font-size: 0.75em;"></p>
                            <div id="qrcode-container" style="display: flex; justify-content: center; margin-top: 15px;"></div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-6 no-print">
                    <button onclick="printTicket()" class="flex-1 bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 transition-colors font-semibold flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimir Ticket
                    </button>
                    <button onclick="closeTicketModal()" class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <script>
        // CÓDIGO JAVASCRIPT COMPLETO
        (function() {
            'use strict';
            
            console.log('🚀 Iniciando módulo de ventas...');
            
            // Datos del servidor
            let salesData = @json($sales);
            const productsData = @json($products);
            const customersData = @json($customers);
            
            // Normalizar salesData
            if (salesData && Array.isArray(salesData)) {
                salesData = salesData.map(sale => {
                    const normalizedSale = {
                        id: sale.id,
                        customer_id: sale.customer_id,
                        sale_date: sale.sale_date,
                        amount: parseFloat(sale.amount) || 0,
                        notes: sale.notes || '',
                        sale_items: []
                    };
                    
                    if (sale.sale_items && Array.isArray(sale.sale_items)) {
                        normalizedSale.sale_items = sale.sale_items.map(item => ({
                            id: item.id,
                            product_id: item.product_id,
                            product_name: item.product?.name || item.product_name || 'Producto',
                            quantity: parseInt(item.quantity) || 0,
                            price: parseFloat(item.price) || 0
                        }));
                    }
                    
                    return normalizedSale;
                });
            } else {
                salesData = [];
            }
            
            console.log('📦 Sales data normalized:', salesData.length, 'ventas');

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

            // Función para actualizar totales
            const updateTableTotals = () => {
                try {
                    console.log('🔄 Actualizando totales...', salesData.length, 'ventas');
                    
                    let totalAmount = 0;
                    let totalCount = 0;
                    let totalProducts = 0;
                    
                    if (salesData && Array.isArray(salesData)) {
                        salesData.forEach(sale => {
                            const saleAmount = parseFloat(sale.amount);
                            if (!isNaN(saleAmount)) {
                                totalAmount += saleAmount;
                                totalCount++;
                            }
                            
                            if (sale.sale_items && Array.isArray(sale.sale_items)) {
                                sale.sale_items.forEach(item => {
                                    const qty = parseInt(item.quantity);
                                    if (!isNaN(qty)) {
                                        totalProducts += qty;
                                    }
                                });
                            }
                        });
                    }
                    
                    const average = totalCount > 0 ? totalAmount / totalCount : 0;
                    
                    const totalVentasEl = document.getElementById('total-ventas-count');
                    const montoTotalEl = document.getElementById('monto-total-acumulado');
                    const totalProductsEl = document.getElementById('total-products-sold');
                    const averageSaleEl = document.getElementById('average-sale');
                    
                    if (totalVentasEl) totalVentasEl.textContent = totalCount;
                    if (montoTotalEl) montoTotalEl.textContent = formatCurrency(totalAmount);
                    if (totalProductsEl) totalProductsEl.textContent = totalProducts;
                    if (averageSaleEl) averageSaleEl.textContent = formatCurrency(average);
                } catch (e) {
                    console.error('❌ Error updating totals:', e);
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
                ${productsData.map(p => `<option value="${p.id}" data-price="${p.price || 0}" data-name="${p.name}">${p.name} (${parseFloat(p.price || 0).toFixed(2)})</option>`).join('')}
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
                            subtotalEl.textContent = `${subtotal.toFixed(2)}`;
                        }
                        total += subtotal;
                    });
                    const totalEl = document.getElementById('total-amount');
                    if (totalEl) {
                        totalEl.textContent = `${formatCurrency(total)}`;
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
                } catch (e) {
                    console.error('Error opening create modal:', e);
                }
            };

            window.closeSaleModal = function() {
                try {
                    const modal = document.getElementById('sale-modal');
                    if (modal) modal.classList.remove('active');
                } catch (e) {
                    console.error('Error closing sale modal:', e);
                }
            };

            window.editSale = async function(id) {
                try {
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
                        
                        document.getElementById('sale-modal').classList.add('active');
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
                    const response = await fetch(`/sales/${id}/view-data`);
                    const data = await response.json();
                    
                    if (data.success) {
                        const sale = data.sale;
                        
                        let itemsHTML = sale.sale_items.map(item => `
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-4 text-sm">${item.product_name}</td>
                                <td class="py-3 px-4 text-sm text-center">${item.quantity}</td>
                                <td class="py-3 px-4 text-sm text-right">${parseFloat(item.price).toFixed(2)}</td>
                                <td class="py-3 px-4 text-sm text-right font-semibold text-teal-600">${(item.quantity * item.price).toFixed(2)}</td>
                            </tr>
                        `).join('');
                        
                        const viewContent = document.getElementById('view-modal-content');
                        if (viewContent) {
                            viewContent.innerHTML = `
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <p class="text-sm font-medium text-gray-500 mb-1">Cliente</p>
                                            <p class="text-lg font-bold text-gray-800">${sale.customer_name}</p>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <p class="text-sm font-medium text-gray-500 mb-1">Fecha de Venta</p>
                                            <p class="text-lg font-bold text-gray-800">${new Date(sale.sale_date).toLocaleDateString('es-MX')}</p>
                                        </div>
                                        
                                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-4 border-2 border-teal-300">
                                            <p class="text-sm font-medium text-teal-700 mb-1">Total Pagado</p>
                                            <p class="text-2xl font-extrabold text-teal-600">${parseFloat(sale.amount).toFixed(2)}</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 pt-6">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos Vendidos</h3>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Producto</th>
                                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Cantidad</th>
                                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">Precio Unit.</th>
                                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${itemsHTML}
                                                </tbody>
                                                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                                    <tr>
                                                        <td colspan="3" class="px-4 py-4 text-right text-base font-bold text-gray-800">TOTAL:</td>
                                                        <td class="px-4 py-4 text-right text-xl font-extrabold text-teal-600">${parseFloat(sale.amount).toFixed(2)}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    ${sale.notes ? `
                                        <div class="border-t border-gray-200 pt-6">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Notas Adicionales</h3>
                                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                                <p class="text-gray-700 whitespace-pre-wrap">${sale.notes}</p>
                                            </div>
                                        </div>
                                    ` : ''}

                                    <div class="flex gap-4 pt-4">
                                        <button onclick="window.closeViewModal(); window.editSale(${sale.id})" class="flex-1 px-4 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                                            Editar Venta
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                        
                        document.getElementById('view-modal').classList.add('active');
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
                    if (modal) modal.classList.remove('active');
                } catch (e) {
                    console.error('Error closing view modal:', e);
                }
            };

            window.deleteSale = async function(id, customerName) {
                try {
                    const modalHtml = `
                        <div id="delete-confirm-modal" class="modal-overlay active">
                            <div class="modal-content" style="max-width: 400px;">
                                <div class="p-6">
                                    <div class="text-center mb-4">
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Confirmar Eliminación</h3>
                                        <p class="text-sm text-gray-600">¿Estás seguro de eliminar la venta #${id}?</p>
                                        <p class="text-sm text-gray-600 font-semibold mt-1">Cliente: ${customerName}</p>
                                        <p class="text-xs text-red-600 mt-2">Esta acción no se puede deshacer</p>
                                    </div>
                                    <div class="flex gap-3">
                                        <button id="confirm-delete-btn" class="flex-1 bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                                            Eliminar
                                        </button>
                                        <button id="cancel-delete-btn" class="flex-1 bg-gray-300 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
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

            // Logo por defecto
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
                
                if (!file) {
                    console.log('No file selected');
                    return;
                }
                
                console.log('File selected:', file.name, file.type, file.size);
                
                // Validar que sea una imagen
                if (!file.type.startsWith('image/')) {
                    window.showNotification('Por favor selecciona un archivo de imagen válido', 'error');
                    return;
                }
                
                // Validar tamaño (máximo 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    window.showNotification('La imagen es muy grande. Máximo 2MB', 'error');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    console.log('File loaded, creating image...');
                    const img = new Image();
                    
                    img.onload = function() {
                        console.log('Image loaded:', img.width, 'x', img.height);
                        
                        try {
                            // Crear canvas para redimensionar
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            
                            // Mantener proporción pero limitar tamaño máximo
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
                            
                            // Convertir a data URL
                            const dataUrl = canvas.toDataURL('image/png');
                            currentLogoUrl = dataUrl;
                            
                            console.log('Image processed, updating elements...');
                            
                            // Actualizar preview y ticket
                            const logoPreview = document.getElementById('logo-preview');
                            const ticketLogo = document.getElementById('ticket-logo');
                            
                            if (logoPreview) {
                                logoPreview.src = dataUrl;
                                console.log('Preview updated');
                            } else {
                                console.error('logo-preview element not found');
                            }
                            
                            if (ticketLogo) {
                                ticketLogo.src = dataUrl;
                                console.log('Ticket logo updated');
                            } else {
                                console.error('ticket-logo element not found');
                            }
                            
                            window.showNotification('Logo actualizado correctamente', 'success');
                        } catch (error) {
                            console.error('Error processing image:', error);
                            window.showNotification('Error al procesar la imagen', 'error');
                        }
                    };
                    
                    img.onerror = function() {
                        console.error('Error loading image');
                        window.showNotification('Error al cargar la imagen', 'error');
                    };
                    
                    img.src = event.target.result;
                };
                
                reader.onerror = function() {
                    console.error('Error reading file');
                    window.showNotification('Error al leer el archivo', 'error');
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

            window.openTicketModal = async function(saleId) {
                try {
                    console.log('Opening ticket for sale:', saleId);
                    
                    const response = await fetch(`/sales/${saleId}/view-data`);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Ticket data received:', data);
                    
                    if (!data.success) {
                        window.showNotification('Error al cargar la venta', 'error');
                        return;
                    }

                    const sale = data.sale;

                    // Actualizar información del ticket
                    const folioEl = document.getElementById('ticket-folio');
                    if (folioEl) folioEl.textContent = `#${String(sale.id).padStart(4, '0')}`;
                    
                    // Formatear fecha correctamente
                    let saleDate;
                    try {
                        const dateStr = sale.sale_date.includes('T') ? sale.sale_date : sale.sale_date + 'T12:00:00';
                        saleDate = new Date(dateStr);
                        
                        if (isNaN(saleDate.getTime())) {
                            saleDate = new Date();
                        }
                    } catch (e) {
                        console.error('Error parsing date:', e);
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

                    // Productos
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
                                            <span>${qty} x ${price.toFixed(2)}</span>
                                            <span style="font-weight: 600; color: #27ae60;">${subtotal.toFixed(2)}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        productsContainer.innerHTML = productsHtml;
                    }

                    // Total
                    const totalEl = document.getElementById('ticket-total');
                    if (totalEl) {
                        const totalAmount = parseFloat(sale.amount) || 0;
                        totalEl.textContent = `${totalAmount.toFixed(2)}`;
                    }

                    // Generar QR Code
                    try {
                        const qrContainer = document.getElementById('qrcode-container');
                        if (qrContainer && typeof QRCode !== 'undefined') {
                            qrContainer.innerHTML = '';
                            const qrInfo = `Venta #${sale.id} - ${sale.customer_name} - Total: ${parseFloat(sale.amount).toFixed(2)}`;
                            new QRCode(qrContainer, {
                                text: qrInfo,
                                width: 100,
                                height: 100,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        } else {
                            console.warn('QRCode library not loaded or container not found');
                        }
                    } catch (qrError) {
                        console.error('Error generating QR code:', qrError);
                    }

                    // Mostrar modal
                    const modal = document.getElementById('ticket-modal');
                    if (modal) {
                        modal.classList.add('active');
                        console.log('Ticket modal opened successfully');
                    }
                } catch (error) {
                    console.error('Error opening ticket:', error);
                    window.showNotification('Error al cargar el ticket: ' + error.message, 'error');
                }
            };

            function closeTicketModal() {
                document.getElementById('ticket-modal').classList.remove('active');
            }

            function printTicket() {
                window.print();
            }

            // Hacer funciones globales
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
                        console.log('✅ Event listener de búsqueda agregado');
                    }

                    const addItemBtn = document.getElementById('add-item-button');
                    if (addItemBtn) {
                        addItemBtn.addEventListener('click', () => addItem());
                        console.log('✅ Event listener de añadir producto agregado');
                    }

                    // Event listener para subir logo
                    const logoUpload = document.getElementById('logo-upload');
                    if (logoUpload) {
                        logoUpload.addEventListener('change', handleLogoUpload);
                        console.log('✅ Event listener de logo upload agregado');
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
                                showMessage('Error al procesar la solicitud. Por favor verifica los datos.', 'error');
                                if (submitButton) {
                                    submitButton.disabled = false;
                                    submitButton.textContent = isEdit ? 'Guardar Cambios' : 'Guardar Venta';
                                }
                            }
                        });
                        console.log('✅ Event listener del formulario agregado');
                    }

                    updateTableTotals();
                    console.log('✅ Página inicializada correctamente');
                } catch (e) {
                    console.error('Error initializing page:', e);
                }
            };

            // Ejecutar cuando el DOM esté completamente cargado
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializePage);
            } else {
                initializePage();
            }
            
            // Verificar que QRCode esté cargado
            window.addEventListener('load', function() {
                if (typeof QRCode === 'undefined') {
                    console.error('❌ QRCode library failed to load');
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
                    script.onload = () => console.log('✅ QRCode library loaded on retry');
                    script.onerror = () => console.error('❌ Failed to load QRCode library on retry');
                    document.head.appendChild(script);
                } else {
                    console.log('✅ QRCode library loaded successfully');
                }
            });

            // Re-calcular totales cuando la página se vuelve visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    console.log('👁️ Pestaña visible de nuevo, actualizando...');
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

            // Ejecutar cuando la ventana recibe el foco
            window.addEventListener('focus', function() {
                console.log('🔍 Ventana con foco, actualizando...');
                setTimeout(updateTableTotals, 100);
            });

            // Ejecutar cuando la página termina de cargar completamente
            window.addEventListener('load', function() {
                console.log('✨ Página completamente cargada');
                setTimeout(updateTableTotals, 200);
            });

            // Event listeners para los modales
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
    </script>

</body>

</html>