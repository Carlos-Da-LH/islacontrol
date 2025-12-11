<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Nueva Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Estilos base */
        body {
            font-family: 'Inter', sans-serif;
        }

        .btn-generate-ai {
            transition: all 0.2s ease-in-out;
        }

        .btn-generate-ai:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="min-h-screen font-sans bg-gray-100 dark:bg-gray-900">

    @include('components.limit-reached-modal')

    {{-- Contenedor ANCHO y BAJO con scroll --}}
    <div class="w-11/12 max-w-5xl mx-auto my-20 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 max-h-[60vh] flex flex-col">

        {{-- Encabezado FIJO --}}
        <div class="flex items-center p-3 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
            <a href="{{ route('sales.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white flex-grow">Crear Nueva Venta</h1>
        </div>

        {{-- Contenedor con SCROLL --}}
        <div class="overflow-y-auto flex-1 px-4 py-3">

            {{-- Mensajes de Session y Errores --}}
            @if (session('success'))
            <div class="p-3 rounded-lg text-sm bg-green-900 text-green-300 border border-green-700 mb-4" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="p-3 rounded-lg text-sm bg-red-900 text-red-300 border border-red-700 mb-4" role="alert">
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="sale-form" action="{{ route('sales.store') }}" method="POST" class="space-y-2">
                @csrf

                {{-- Campo Cliente y Fecha --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="customer_id" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                        <select id="customer_id" name="customer_id"
                            class="block w-full px-2 py-1.5 rounded-md shadow-sm text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                            required>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                {{ $customer->name == 'Público General' ? 'selected' : '' }}>
                                {{ $customer->name === 'Público General' ? '1' : $customer->id }} - {{ $customer->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sale_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                        <input type="date" id="sale_date" name="sale_date"
                            class="block w-full px-2 py-1.5 rounded-md shadow-sm text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                            required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700 my-2">

                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Productos</h2>

                {{-- Encabezado de la cuadrícula de Productos --}}
                <div class="hidden lg:grid grid-cols-1 lg:grid-cols-6 gap-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                    <div class="col-span-2">Producto</div>
                    <div>Cantidad</div>
                    <div>Precio Unitario</div>
                    <div class="text-right">Subtotal</div>
                    <div></div>
                </div>

                {{-- Contenedor de Ítems --}}
                <div id="sale-items-container" class="space-y-2"></div>

                {{-- Botón Añadir --}}
                <button type="button" id="add-item-button"
                    class="px-3 py-1.5 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition-colors text-xs font-semibold">
                    + Añadir
                </button>

                {{-- Total --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                    <p class="text-sm font-bold text-gray-900 dark:text-white text-right">
                        TOTAL: <span id="total-amount" class="text-green-600 dark:text-green-400 font-extrabold ml-1">$0.00</span>
                    </p>
                </div>

                <hr class="border-gray-200 dark:border-gray-700 my-2">

                {{-- Método de Pago --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Método de Pago</label>
                    <div class="grid grid-cols-4 gap-2">
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="efectivo" class="peer sr-only" checked required>
                            <div class="border border-gray-600 peer-checked:border-green-500 peer-checked:bg-green-900/30 rounded-md p-2 text-center hover:border-gray-500 transition-all">
                                <i class='bx bx-money text-xl text-green-400'></i>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-1">Efect.</p>
                            </div>
                        </label>
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="tarjeta_debito" class="peer sr-only" required>
                            <div class="border border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-900/30 rounded-md p-2 text-center hover:border-gray-500 transition-all">
                                <i class='bx bx-credit-card text-xl text-blue-400'></i>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-1">Déb.</p>
                            </div>
                        </label>
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="tarjeta_credito" class="peer sr-only" required>
                            <div class="border border-gray-600 peer-checked:border-purple-500 peer-checked:bg-purple-900/30 rounded-md p-2 text-center hover:border-gray-500 transition-all">
                                <i class='bx bx-credit-card-alt text-xl text-purple-400'></i>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-1">Créd.</p>
                            </div>
                        </label>
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="transferencia" class="peer sr-only" required>
                            <div class="border border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-900/30 rounded-md p-2 text-center hover:border-gray-500 transition-all">
                                <i class='bx bx-transfer text-xl text-indigo-400'></i>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-1">Trans.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Efectivo --}}
                <div id="cash-payment-section" class="space-y-2">
                    <div>
                        <label for="amount_received" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Monto Recibido
                        </label>
                        <input type="number" id="amount_received" name="amount_received" step="0.01" min="0"
                            class="block w-full px-2 py-1.5 rounded-md shadow-sm text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                            placeholder="0.00" oninput="calculateChange()">
                    </div>
                    <div id="change-display" class="hidden p-2 bg-yellow-900/30 border border-yellow-500 rounded-md">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Cambio:</span>
                            <span id="change-value" class="text-base font-black text-yellow-400">$0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Referencia --}}
                <div id="reference-section" class="hidden">
                    <label for="payment_reference" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Referencia (Opcional)
                    </label>
                    <input type="text" id="payment_reference" name="payment_reference" maxlength="100"
                        class="block w-full px-2 py-1.5 rounded-md shadow-sm text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                        placeholder="Ej: AUTH123456">
                </div>

                {{-- Notas --}}
                <div>
                    <label for="notes" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Notas</label>
                    <textarea id="notes" name="notes" rows="2"
                        class="block w-full px-2 py-1.5 rounded-md shadow-sm text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"></textarea>
                </div>

                {{-- Mensajes --}}
                <div id="message-area" class="p-2 rounded-md text-xs transition-opacity duration-300 ease-in-out hidden"></div>

                {{-- Botón Guardar --}}
                <div class="pt-3 pb-2">
                    <button type="submit"
                        class="w-full bg-green-600 text-white font-bold text-sm py-2 rounded-md hover:bg-green-700 transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-green-600">
                        💾 Guardar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const productOptionsHtml = `
            <option value="">Selecciona un producto</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0.00 }}" data-name="{{ $product->name }}">
                    {{ $product->name }} ({{ $product->price ? '$' . number_format($product->price, 2) : 'Sin Precio' }})
                </option>
            @endforeach
        `;
        
        const container = document.getElementById('sale-items-container');
        const addButton = document.getElementById('add-item-button');
        const totalAmountSpan = document.getElementById('total-amount');
        const messageArea = document.getElementById('message-area');
        const inputDarkStyle = 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors';

        let itemIndex = 0;

        function showMessage(message, type = 'error') {
            messageArea.innerText = message;
            messageArea.classList.remove('hidden', 'bg-red-900', 'text-red-300', 'bg-green-900', 'text-green-300', 'bg-blue-900', 'text-blue-300', 'opacity-0');
            messageArea.classList.add('border');

            if (type === 'error') messageArea.classList.add('bg-red-900', 'text-red-300', 'border-red-700');
            else if (type === 'success') messageArea.classList.add('bg-green-900', 'text-green-300', 'border-green-700');
            else if (type === 'info') messageArea.classList.add('bg-blue-900', 'text-blue-300', 'border-blue-700');
            
            setTimeout(hideMessage, 5000);
        }

        function hideMessage() {
            messageArea.classList.add('opacity-0');
            setTimeout(() => {
                messageArea.classList.add('hidden');
                messageArea.innerText = '';
            }, 300);
        }

        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
                const subtotal = quantity * price;
                
                row.querySelector('.subtotal').innerText = `$${subtotal.toFixed(2)}`;
                total += subtotal;
            });
            totalAmountSpan.innerText = `$${total.toFixed(2)}`;
        }
        
        function handleProductChange(event) {
            const selectElement = event.target;
            const itemRow = selectElement.closest('.item-row');
            const priceInput = itemRow.querySelector('input[name*="[price]"]');
            
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.dataset.price || '0.00'; 
            
            priceInput.value = parseFloat(price).toFixed(2);
            updateTotals();
        }

        function addItem() {
            const newItem = document.createElement('div');
            newItem.classList.add('grid', 'grid-cols-1', 'lg:grid-cols-6', 'gap-2', 'mb-2', 'items-center', 'item-row', 'p-2', 'bg-gray-50', 'dark:bg-gray-900/50', 'rounded-md', 'border', 'border-gray-200', 'dark:border-gray-700');

            newItem.innerHTML = `
                <div class="col-span-1 lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 lg:hidden">Producto</label>
                    <select name="sale_items[${itemIndex}][product_id]" required
                        class="block w-full rounded-md shadow-sm px-2 py-1.5 product-select-field text-xs ${inputDarkStyle}">
                        ${productOptionsHtml}
                    </select>
                </div>
                <div class="col-span-1">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 lg:hidden">Cant.</label>
                    <input type="number" name="sale_items[${itemIndex}][quantity]" required min="1"
                        class="block w-full rounded-md shadow-sm px-2 py-1.5 text-xs ${inputDarkStyle}" value="1" placeholder="Cant.">
                </div>
                <div class="col-span-1">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 lg:hidden">Precio</label>
                    <input type="number" name="sale_items[${itemIndex}][price]" required step="0.01" min="0"
                        class="block w-full rounded-md shadow-sm px-2 py-1.5 text-xs ${inputDarkStyle}" placeholder="Precio" value="0.00">
                </div>
                <div class="col-span-1 text-left lg:text-right">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 lg:hidden">Subtotal</label>
                    <span class="subtotal block text-sm font-bold text-green-600 dark:text-green-400">$0.00</span>
                </div>
                <div class="col-span-1">
                    <button type="button"
                        class="remove-item-button w-full px-2 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors focus:outline-none text-xs">
                        ✕
                    </button>
                </div>
            `;

            container.appendChild(newItem);

            const productSelect = newItem.querySelector('.product-select-field');
            const priceInput = newItem.querySelector('input[name*="[price]"]');
            const quantityInput = newItem.querySelector('input[name*="[quantity]"]');

            productSelect.addEventListener('change', handleProductChange);
            quantityInput.addEventListener('input', updateTotals);
            priceInput.addEventListener('input', updateTotals);

            itemIndex++;
        }

        addButton.addEventListener('click', addItem);
        
        container.addEventListener('click', e => {
            if (e.target.classList.contains('remove-item-button')) {
                e.target.closest('.item-row').remove();
                updateTotals();
            }
        });

        addItem();

        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const cashSection = document.getElementById('cash-payment-section');
                const referenceSection = document.getElementById('reference-section');

                if (this.value === 'efectivo') {
                    cashSection.classList.remove('hidden');
                    referenceSection.classList.add('hidden');
                } else {
                    cashSection.classList.add('hidden');
                    referenceSection.classList.remove('hidden');
                    document.getElementById('change-display').classList.add('hidden');
                }
            });
        });

        function calculateChange() {
            const totalText = document.getElementById('total-amount').innerText.replace('$', '').replace(',', '');
            const total = parseFloat(totalText) || 0;
            const received = parseFloat(document.getElementById('amount_received').value) || 0;

            if (received >= total && received > 0) {
                const change = received - total;
                document.getElementById('change-value').textContent = `$${change.toFixed(2)}`;
                document.getElementById('change-display').classList.remove('hidden');
            } else {
                document.getElementById('change-display').classList.add('hidden');
            }
        }

        window.calculateChange = calculateChange;
    });
</script>
</body>

</html>