<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/dark-mode.css">
    <script src="/js/dark-mode.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .light-input {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #1f2937;
            transition: all 0.15s ease-in-out;
        }

        .light-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 1px #10b981;
            outline: none;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content {
            animation: modalFadeIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen pt-36 lg:pt-8 pb-8 flex justify-center transition-colors duration-300">

    @include('components.limit-reached-modal')

    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden p-6 md:p-8 border border-gray-200 dark:border-gray-700">

            <div class="flex items-center justify-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                <svg class="w-9 h-9 mr-3 text-emerald-500 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 8l-9-5-9 5v8l9 5 9-5z"></path>
                    <polyline points="2 12 12 17 22 12"></polyline>
                    <polyline points="2 16 12 21 22 16"></polyline>
                    <line x1="12" y1="5" x2="12" y2="17"></line>
                </svg>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white text-center">
                    Gestión de Productos
                </h1>
            </div>

            @if (session('success') && !session('limit_reached'))
            <div class="bg-emerald-500 p-3 rounded-lg mb-4 text-white font-medium text-center shadow-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-indigo-500 p-6 rounded-xl shadow-xl border-b-4 border-indigo-600 flex justify-between items-center">
                    <div>
                        <p class="text-white text-base font-semibold uppercase tracking-wider opacity-90">Total de Productos</p>
                        <p id="total-products-count" class="text-white text-4xl font-extrabold">{{ count($products) }}</p>
                    </div>
                    <svg class="w-10 h-10 text-white opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path>
                    </svg>
                </div>

                <div class="bg-emerald-500 p-6 rounded-xl shadow-xl border-b-4 border-emerald-600 flex justify-between items-center">
                    <div>
                        <p class="text-white text-base font-semibold uppercase tracking-wider opacity-90">Valor Total de Inventario</p>
                        <p class="text-white text-4xl font-extrabold">$<span id="total-inventory-value">0.00</span></p>
                    </div>
                    <svg class="w-10 h-10 text-white opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
            </div>

            <form action="{{ route('products.store') }}" method="POST" class="mb-8 p-6 border border-gray-200 rounded-xl bg-gray-50 shadow-xl" id="product-form">
                @csrf
                <input type="hidden" name="from" value="dashboard">
                <h2 class="text-xl font-bold text-emerald-600 mb-4 border-b border-gray-200 pb-2">
                    Agregar Nuevo Producto
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            placeholder="Ej. Laptop Gaming"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                    <div>
                        <label for="codigo_barras" class="block text-xs font-medium text-gray-700 mb-1">Código de Barras</label>
                        <input type="text" name="codigo_barras" id="codigo_barras" value="{{ old('codigo_barras') }}"
                            placeholder="7501086801046"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                    <div>
                        <label for="stock" class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" name="stock" id="stock" required value="{{ old('stock') }}"
                            placeholder="0" min="0"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                    <div>
                        <label for="price" class="block text-xs font-medium text-gray-700 mb-1">Precio ($)</label>
                        <input type="number" step="0.01" name="price" id="price" required value="{{ old('price') }}"
                            placeholder="0.00" min="0"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                    <div>
                        <label for="category_id" class="block text-xs font-medium text-gray-700 mb-1">Categoría</label>
                        <select name="category_id" id="category_id" required
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                            <option value="">Seleccionar...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" id="submit-button"
                        class="mt-6 w-full py-2.5 px-4 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white 
                                 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 
                                 transition duration-300 ease-in-out transform">
                        Guardar Producto
                    </button>
                </div>
            </form>

            <div class="mb-4 flex items-center bg-gray-50 rounded-lg p-3 border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="products-search-input" placeholder="Buscar por ID, Nombre, Stock o Categoría..."
                    class="w-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none text-sm">
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                Lista de Productos
            </h2>

            <div class="overflow-x-auto rounded-xl shadow-2xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[8%]">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[22%]">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[15%]">Código de Barras</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider w-[10%]">Stock</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider w-[12%]">Precio</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[15%]">Categoría</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-[18%]">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200" id="products-table-body">
                        @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out product-row"
                            data-product-price="{{ $product->price }}"
                            data-product-stock="{{ $product->stock }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $product->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-mono">
                                @if($product->codigo_barras)
                                    {{ $product->codigo_barras }}
                                @else
                                    <span class="text-gray-400 italic">Sin código</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-yellow-600 font-medium">{{ $product->stock }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-emerald-600 font-medium">${{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $product->category->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <button type="button"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ e($product->name) }}"
                                    data-product-barcode="{{ e($product->codigo_barras ?? '') }}"
                                    data-product-stock="{{ $product->stock }}"
                                    data-product-price="{{ $product->price }}"
                                    data-product-category="{{ $product->category_id }}"
                                    onclick="showEditModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white
                                         bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out mr-2">
                                    Editar
                                </button>

                                <button type="button" 
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ e($product->name) }}"
                                    onclick="showDeleteModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white 
                                         bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                    Eliminar
                                </button>
                                
                                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="from" value="dashboard">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-state-row">
                            <td colspan="7" class="px-6 py-8 text-center text-lg text-gray-500 font-medium">
                                No hay productos registrados. ¡Usa el formulario de arriba para agregar uno!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="notification-container" class="fixed bottom-5 right-5 z-50 space-y-2"></div>
            <div id="modal-container"></div>

        </div>
    </div>

    <script>
        // SOLUCIÓN: Envolver todo en DOMContentLoaded y usar try-catch
        (function() {
            'use strict';
            
            // Cargar categorías desde PHP/Blade al cargar la página
            const categoriesData = {!! json_encode($categories) !!};
            
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
            const updateTotalsProducts = () => {
                try {
                    let totalProductsCount = 0;
                    let totalInventoryValue = 0;

                    const productRows = document.querySelectorAll('#products-table-body tr.product-row[data-product-price]');
                    
                    productRows.forEach(row => {
                        if (row.style.display !== 'none') {
                            const price = parseFloat(row.getAttribute('data-product-price')) || 0;
                            const stock = parseInt(row.getAttribute('data-product-stock')) || 0;
                            
                            if (!isNaN(price) && !isNaN(stock)) {
                                totalProductsCount++;
                                totalInventoryValue += (price * stock);
                            }
                        }
                    });

                    const countElement = document.getElementById('total-products-count');
                    const valueElement = document.getElementById('total-inventory-value');

                    if (countElement) {
                        countElement.textContent = totalProductsCount;
                    }
                    if (valueElement) {
                        valueElement.textContent = formatCurrency(totalInventoryValue);
                    }
                } catch (e) {
                    console.error('Error updating totals:', e);
                }
            };

            // Función de filtrado
            const filterTableProducts = () => {
                try {
                    const input = document.getElementById('products-search-input');
                    if (!input) return;
                    
                    const filter = input.value.toUpperCase();
                    const tableBody = document.getElementById('products-table-body');
                    if (!tableBody) return;
                    
                    const rows = tableBody.querySelectorAll('tr.product-row');
                    
                    rows.forEach(row => {
                        const rowText = row.innerText.toUpperCase();
                        if (rowText.indexOf(filter) > -1) {
                            row.style.display = ''; 
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    updateTotalsProducts();
                } catch (e) {
                    console.error('Error filtering table:', e);
                }
            };

            // Función de notificaciones
            window.showNotification = function(message, type = 'info') {
                try {
                    const notification = document.createElement('div');
                    let baseClasses = 'p-4 rounded-lg shadow-xl text-white font-semibold transition-opacity duration-300';
                    let colorClasses = type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-red-600' : 'bg-blue-500';

                    notification.className = `${baseClasses} ${colorClasses}`;
                    notification.textContent = message;

                    const container = document.getElementById('notification-container');
                    if (container) {
                        container.appendChild(notification);

                        setTimeout(() => {
                            notification.style.opacity = '0';
                            setTimeout(() => notification.remove(), 300);
                        }, 3000);
                    }
                } catch (e) {
                    console.error('Error showing notification:', e);
                }
            };

            // Función para mostrar modal de edición
            window.showEditModal = function(buttonElement) {
                try {
                    const id = buttonElement.dataset.productId;
                    const name = buttonElement.dataset.productName;
                    const barcode = buttonElement.dataset.productBarcode || '';
                    const stock = buttonElement.dataset.productStock;
                    const price = buttonElement.dataset.productPrice;
                    const categoryId = buttonElement.dataset.productCategory;

                    const modalContainer = document.getElementById('modal-container');
                    if (!modalContainer) return;
                    
                    modalContainer.innerHTML = '';

                    // Usar las categorías cargadas globalmente
                    let optionsHtml = '<option value="">Seleccionar...</option>';
                    for (let i = 0; i < categoriesData.length; i++) {
                        const cat = categoriesData[i];
                        const selected = cat.id == categoryId ? 'selected' : '';
                        const catName = String(cat.name).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                        optionsHtml += '<option value="' + cat.id + '" ' + selected + '>' + catName + '</option>';
                    }

                    const modal = document.createElement('div');
                    modal.id = 'edit-modal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto';
                    
                    const formAction = '{{ route("products.update", "") }}/' + id;
                    const csrfToken = '{{ csrf_token() }}';
                    
                    modal.innerHTML = '<div class="modal-content bg-white p-8 rounded-2xl shadow-2xl max-w-3xl w-full border border-gray-200 my-8">' +
                            '<div class="flex items-center justify-center mb-6 border-b border-gray-200 pb-4">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-500 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                                    '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>' +
                                    '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>' +
                                '</svg>' +
                                '<h2 class="text-2xl font-extrabold text-gray-800">Editar Producto</h2>' +
                            '</div>' +
                            '<form action="' + formAction + '" method="POST" class="p-6 border border-gray-200 rounded-xl bg-gray-50 shadow-xl">' +
                                '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                                '<input type="hidden" name="_method" value="PUT">' +
                                '<input type="hidden" name="from" value="dashboard">' +
                                '<h3 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-200 pb-2">Actualizar Datos del Producto #' + id + '</h3>' +
                                '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
                                    '<div>' +
                                        '<label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>' +
                                        '<input type="text" name="name" id="edit-name" value="' + name + '" required class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                                    '</div>' +
                                    '<div>' +
                                        '<label for="edit-barcode" class="block text-sm font-medium text-gray-700 mb-1">Código de Barras</label>' +
                                        '<input type="text" name="codigo_barras" id="edit-barcode" value="' + barcode + '" placeholder="7501086801046" class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                                    '</div>' +
                                    '<div>' +
                                        '<label for="edit-stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>' +
                                        '<input type="number" name="stock" id="edit-stock" value="' + stock + '" required min="0" class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                                    '</div>' +
                                    '<div>' +
                                        '<label for="edit-price" class="block text-sm font-medium text-gray-700 mb-1">Precio ($)</label>' +
                                        '<input type="number" step="0.01" name="price" id="edit-price" value="' + price + '" required min="0" class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                                    '</div>' +
                                    '<div>' +
                                        '<label for="edit-category" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>' +
                                        '<select name="category_id" id="edit-category" required class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                                            optionsHtml +
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="mt-6 flex justify-end gap-3">' +
                                    '<button type="submit" class="py-2.5 px-5 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-300 ease-in-out transform hover:scale-[1.02]">Actualizar Producto</button>' +
                                    '<button type="button" onclick="window.closeEditModal()" class="py-2.5 px-5 text-sm font-medium rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-150 ease-in-out shadow-md">Cancelar</button>' +
                                '</div>' +
                            '</form>' +
                        '</div>';

                    modalContainer.appendChild(modal);
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) window.closeEditModal();
                    });
                } catch (e) {
                    console.error('Error showing edit modal:', e);
                }
            };

            // Función para cerrar modal de edición
            window.closeEditModal = function() {
                try {
                    const modal = document.getElementById('edit-modal');
                    if (modal) modal.remove();
                } catch (e) {
                    console.error('Error closing edit modal:', e);
                }
            };

            // Función para mostrar modal de eliminación
            window.showDeleteModal = function(buttonElement) {
                try {
                    const id = buttonElement.dataset.productId;
                    const name = buttonElement.dataset.productName;

                    const modalContainer = document.getElementById('modal-container');
                    if (!modalContainer) return;
                    
                    modalContainer.innerHTML = '';

                    const modal = document.createElement('div');
                    modal.id = 'confirmation-modal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50';
                    
                    modal.innerHTML = '<div class="modal-content bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full border border-gray-200">' +
                            '<h3 class="text-xl font-bold text-red-600 mb-4">Confirmar Eliminación</h3>' +
                            '<p class="text-gray-800 text-lg font-medium mb-6">¿Estás seguro de que quieres eliminar el producto "' + name + '"? Esta acción no se puede deshacer.</p>' +
                            '<div class="flex justify-end space-x-3">' +
                                '<button id="modal-cancel" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Cancelar</button>' +
                                '<button id="modal-confirm" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">Eliminar</button>' +
                            '</div>' +
                        '</div>';

                    modalContainer.appendChild(modal);
                    
                    const confirmBtn = document.getElementById('modal-confirm');
                    const cancelBtn = document.getElementById('modal-cancel');
                    
                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function() {
                            const form = document.getElementById('delete-form-' + id);
                            if (form) form.submit();
                            window.closeModal();
                        });
                    }
                    
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', window.closeModal);
                    }
                    
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) window.closeModal();
                    });
                } catch (e) {
                    console.error('Error showing delete modal:', e);
                }
            };

            // Función para cerrar modal de confirmación
            window.closeModal = function() {
                try {
                    const modal = document.getElementById('confirmation-modal');
                    if (modal) modal.remove();
                } catch (e) {
                    console.error('Error closing modal:', e);
                }
            };

            // Inicialización cuando el DOM está listo
            const initializePage = () => {
                try {
                    // Agregar event listener al buscador
                    const searchInput = document.getElementById('products-search-input');
                    if (searchInput) {
                        searchInput.addEventListener('input', filterTableProducts);
                    }

                    // Agregar event listener para ESC
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            window.closeEditModal();
                            window.closeModal();
                        }
                    });

                    // Actualizar totales al cargar
                    updateTotalsProducts();
                } catch (e) {
                    console.error('Error initializing page:', e);
                }
            };

            // Ejecutar cuando el DOM esté completamente cargado
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializePage);
            } else {
                // DOM ya está cargado
                initializePage();
            }

            // Re-calcular totales cuando la página se vuelve visible (para el problema de cambio de pestaña)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    setTimeout(updateTotalsProducts, 100);
                }
            });

        })();
    </script>
</body>

</html>