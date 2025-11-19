<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta #{{ $sale->id }}</title>
    {{-- CDN de Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Estilos base */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Contenedor principal para el color de fondo específico de la imagen (Navy/Negro Oscuro) */
        .crud-container {
            /* slate-800 oscuro de la vista de listado */
            background-color: #1e293b;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
        }

        /* Estilo para los inputs en el tema oscuro */
        .input-dark-style {
            background-color: #374151;
            color: #D1D5DB;
            border-color: #4B5563;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .input-dark-style:focus {
            border-color: #10B981;
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.5);
        }

        /* Estilo para el botón de retroceso */
        .back-button {
            color: #9CA3AF;
            transition: color 0.2s;
        }

        .back-button:hover {
            color: #fff;
        }
    </style>
</head>

{{-- Fondo muy oscuro para el cuerpo --}}
<body class="bg-gray-900 min-h-screen font-sans">
    <div class="flex items-start justify-center p-8">
        {{-- Contenedor del formulario: más ancho (max-w-4xl) y oscuro --}}
        <div class="crud-container p-8 rounded-xl shadow-2xl w-full max-w-4xl">
            
            {{-- Encabezado y Botón de Retroceso --}}
            <div class="flex items-center mb-8">
                {{-- Botón de Regreso (Ícono blanco) --}}
                <a href="{{ route('sales.index') }}" class="back-button mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{-- Título --}}
                <h1 class="text-3xl font-bold text-white flex-grow">Editar Venta #{{ $sale->id }}</h1>
            </div>

            {{-- Pasamos el número inicial de ítems en un atributo data --}}
            <div id="sale-meta" data-items-count="{{ $sale->saleItems->count() }}" hidden></div>

            <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Campo Cliente y Fecha --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-300">Cliente</label>
                        <select id="customer_id" name="customer_id"
                            class="mt-1 block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style" required>
                            <option value="" disabled class="text-gray-500">Selecciona un cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                    {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-300">Fecha de Venta</label>
                        <input type="date" id="sale_date" name="sale_date"
                            class="mt-1 block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style" required
                            value="{{ old('sale_date', \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d')) }}">
                    </div>
                </div>

                <hr class="border-gray-700 mt-8 mb-6">

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">Detalle de Productos</h2>

                {{-- Encabezado de la cuadrícula de Productos --}}
                <div
                    class="grid grid-cols-1 md:grid-cols-6 gap-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                    <div class="col-span-2">Producto</div>
                    <div>Cantidad</div>
                    <div>Precio Unitario</div>
                    <div class="text-right">Subtotal</div>
                    <div></div>
                </div>

                {{-- Contenedor de Ítems (Productos de la venta) --}}
                <div id="sale-items-container" class="space-y-4">
                    @foreach($sale->saleItems as $index => $item)
                    {{-- Usamos grid-cols-6 para la coherencia visual --}}
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-center item-row">
                        <div class="col-span-1 md:col-span-2">
                            {{-- No se requiere label aquí ya que está en el encabezado --}}
                            <select name="sale_items[{{ $index }}][product_id]" required
                                class="block w-full rounded-lg shadow-sm px-4 py-2 sm:text-sm product-select input-dark-style">
                                <option value="">Selecciona un producto</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0.00 }}"
                                    data-name="{{ $product->name }}"
                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->price ? '$' . number_format($product->price, 2) : 'Sin Precio' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <input type="number" name="sale_items[{{ $index }}][quantity]" required min="1"
                                class="block w-full rounded-lg shadow-sm px-4 py-2 sm:text-sm input-dark-style"
                                value="{{ $item->quantity }}">
                        </div>
                        <div class="col-span-1">
                            <input type="number" name="sale_items[{{ $index }}][price]" required step="0.01" min="0"
                                class="block w-full rounded-lg shadow-sm px-4 py-2 sm:text-sm input-dark-style"
                                value="{{ number_format($item->price, 2, '.', '') }}">
                        </div>
                        <div class="col-span-1 text-right">
                            <span class="subtotal block text-base font-semibold text-white">
                                ${{ number_format($item->quantity * $item->price, 2) }}
                            </span>
                        </div>
                        <div class="col-span-1">
                            <button type="button"
                                class="remove-item-button w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 focus:ring-offset-[#1e293b]">
                                X
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Botón para Añadir Producto --}}
                <button type="button" id="add-item-button"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 focus:ring-offset-[#1e293b]">
                    <span class="text-xl mr-1">+</span> Añadir Producto
                </button>

                {{-- Resumen del Total --}}
                <div class="border-t border-gray-700 pt-6 mt-6">
                    <p class="text-xl font-bold text-white text-right">
                        TOTAL: <span id="total-amount" class="text-green-400 font-extrabold ml-2">${{ number_format($sale->amount, 2) }}</span>
                    </p>
                </div>

                {{-- Notas --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-300">Notas Adicionales</label>
                    <textarea id="notes" name="notes" rows="4"
                        class="mt-1 block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style">{{ old('notes', $sale->notes) }}</textarea>
                </div>

                {{-- Botón Guardar --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold text-lg py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-600 focus:ring-offset-[#1e293b]">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('sale-items-container');
        const addButton = document.getElementById('add-item-button');
        const totalAmountSpan = document.getElementById('total-amount');
        const inputDarkStyle = 'input-dark-style'; // Clase CSS
        
        // Inicializamos el índice a partir de los ítems ya cargados
        let itemIndex = parseInt(document.getElementById('sale-meta').dataset.itemsCount);

        // **CLAVE:** Preparamos el string de las opciones de producto usando Blade.
        const productOptionsHtml = `
            <option value="">Selecciona un producto</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0.00 }}" data-name="{{ $product->name }}">
                    {{ $product->name }} ({{ $product->price ? '$' . number_format($product->price, 2) : 'Sin Precio' }})
                </option>
            @endforeach
        `;

        // --- FUNCIONES DE LÓGICA ---
        
        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
                const subtotal = quantity * price;
                
                // Formateo de subtotal
                row.querySelector('.subtotal').innerText = `$${subtotal.toFixed(2)}`;
                total += subtotal;
            });
            // Formateo del total
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
            // Usamos grid-cols-6 para que las filas añadidas coincidan con las cargadas
            newItem.classList.add('grid', 'grid-cols-1', 'md:grid-cols-6', 'gap-3', 'items-center', 'item-row');

            newItem.innerHTML = `
                <div class="col-span-1 md:col-span-2">
                    <select name="sale_items[${itemIndex}][product_id]" required
                        class="block w-full rounded-lg shadow-sm px-4 py-2 sm:text-sm product-select ${inputDarkStyle}">
                        ${productOptionsHtml}
                    </select>
                </div>
                <div class="col-span-1">
                    <input type="number" name="sale_items[${itemIndex}][quantity]" required min="1"
                        class="block w-full rounded-lg shadow-sm px-4 py-2 sm:text-sm ${inputDarkStyle}" value="1" placeholder="Cant.">
                </div>
                <div class="col-span-1">
                    <input type="number" name="sale_items[${itemIndex}][price]" required step="0.01" min="0"
                        class="block w-full rounded-lg shadow-sm px-4 py-2 sm:text-sm ${inputDarkStyle}" placeholder="Precio" value="0.00">
                </div>
                <div class="col-span-1 text-right">
                    <span class="subtotal block text-base font-semibold text-white">$0.00</span>
                </div>
                <div class="col-span-1">
                    <button type="button"
                        class="remove-item-button w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 focus:ring-offset-[#1e293b]">
                        X
                    </button>
                </div>
            `;

            container.appendChild(newItem);

            // Conectamos los listeners
            const newRow = newItem;
            newRow.querySelector('.product-select').addEventListener('change', handleProductChange); 
            newRow.querySelector('input[name*="[quantity]"]').addEventListener('input', updateTotals);
            newRow.querySelector('input[name*="[price]"]').addEventListener('input', updateTotals);
            
            itemIndex++;
            updateTotals(); // Recalcular por si hay valores predeterminados
        }

        // --- INICIALIZACIÓN ---

        // Conecta el botón Añadir Producto
        addButton.addEventListener('click', addItem);
        
        // Listener para eliminar ítems (delegado)
        container.addEventListener('click', e => {
            if (e.target.classList.contains('remove-item-button')) {
                e.target.closest('.item-row').remove();
                updateTotals();
            }
        });

        // Inicializar listeners para filas existentes al cargar la página
        document.querySelectorAll('.item-row').forEach(row => {
            row.querySelector('input[name*="[quantity]"]').addEventListener('input', updateTotals);
            row.querySelector('input[name*="[price]"]').addEventListener('input', updateTotals);
            row.querySelector('.product-select').addEventListener('change', handleProductChange); 
        });

        // Recalcular el total al cargar la página (vital para asegurar que el total sea correcto)
        updateTotals();
    });
</script>

</body>

</html>