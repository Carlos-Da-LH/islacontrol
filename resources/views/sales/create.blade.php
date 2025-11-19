<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Nueva Venta</title>
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
            background-color: #374151; /* Gris oscuro para el fondo del input */
            color: #D1D5DB; /* Gris claro para el texto */
            border-color: #4B5563; /* Borde gris oscuro */
            transition: border-color 0.2s, background-color 0.2s;
        }

        .input-dark-style:focus {
            border-color: #10B981; /* Verde esmeralda al enfocar */
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.5); /* Anillo de enfoque verde */
        }
        
        .btn-generate-ai {
            transition: all 0.2s ease-in-out;
        }

        .btn-generate-ai:hover {
            transform: scale(1.05);
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
                <a href="{{ route('sales.index') }}" class="text-gray-400 hover:text-white transition-colors mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{-- Título --}}
                <h1 class="text-3xl font-bold text-white flex-grow">Crear Nueva Venta</h1>
            </div>

            {{-- Mensajes de Session y Errores (Ajustados al tema oscuro) --}}
            @if (session('success'))
                <div class="p-3 rounded-lg text-sm bg-green-900 text-green-300 border border-green-700 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="p-3 rounded-lg text-sm bg-red-900 text-red-300 border border-red-700 mb-6" role="alert">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="sale-form" action="{{ route('sales.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Campo Cliente y Fecha --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-300">Cliente</label>
                        <select id="customer_id" name="customer_id"
                            class="mt-1 block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style"
                            required>
                            <option value="" disabled selected class="text-gray-500">Selecciona un cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}">
                                    {{ $customer->id }} - {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-300">Fecha de Venta</label>
                        <input type="date" id="sale_date" name="sale_date"
                            class="mt-1 block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style"
                            required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <hr class="border-gray-700 mt-8 mb-6">

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">Detalle de Productos</h2>

                {{-- Encabezado de la cuadrícula de Productos --}}
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                    <div class="col-span-2">Producto</div>
                    <div>Cantidad</div>
                    <div>Precio Unitario</div>
                    <div class="text-right">Subtotal</div>
                    <div></div>
                </div>

                {{-- Contenedor de Ítems (Productos de la venta) --}}
                <div id="sale-items-container" class="space-y-4"></div>

                {{-- Botón para Añadir Producto --}}
                <button type="button" id="add-item-button"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 focus:ring-offset-[#1e293b]">
                    <span class="text-xl mr-1">+</span> Añadir Producto
                </button>

                {{-- Resumen del Total --}}
                <div class="border-t border-gray-700 pt-6 mt-6">
                    <p class="text-xl font-bold text-white text-right">
                        TOTAL: <span id="total-amount" class="text-green-400 font-extrabold ml-2">$0.00</span>
                    </p>
                </div>

                {{-- Notas y Botón IA --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="notes" class="block text-sm font-medium text-gray-300">Notas Adicionales</label>
                        <button type="button" id="generate-note-button"
                            class="bg-purple-600 text-white font-semibold py-1 px-3 rounded-lg shadow-md hover:bg-purple-700 transition-colors btn-generate-ai text-xs focus:ring-offset-[#1e293b]">
                            Generar Nota IA
                        </button>
                    </div>
                    <textarea id="notes" name="notes" rows="4"
                        class="mt-1 block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style"></textarea>
                </div>

                {{-- Área de Mensajes Dinámicos (Errores/Info) --}}
                <div id="message-area"
                    class="p-3 rounded-lg text-sm transition-opacity duration-300 ease-in-out hidden"></div>

                {{-- Botón Guardar --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-600 text-white font-bold text-lg py-3 rounded-lg hover:bg-green-700 transition-colors shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-green-600 focus:ring-offset-[#1e293b]">
                        Guardar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // **CLAVE:** Preparamos el string de las opciones de producto usando Blade.
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
        const notesTextarea = document.getElementById('notes');
        const customerSelect = document.getElementById('customer_id');
        const generateNoteButton = document.getElementById('generate-note-button');
        const messageArea = document.getElementById('message-area');
        const inputDarkStyle = 'input-dark-style'; // Clase CSS para los inputs oscuros

        let itemIndex = 0;

        // --- FUNCIONES DE UTILIDAD (Mensajes) ---
        
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

        function resetGenerateButton() {
            generateNoteButton.disabled = false;
            generateNoteButton.innerText = 'Generar Nota IA';
            generateNoteButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            generateNoteButton.classList.add('bg-purple-600', 'hover:bg-purple-700');
        }
        
        // --- FUNCIONES DE CÁLCULO Y LÓGICA DE PRODUCTOS ---
        
        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                // Aseguramos que los valores sean números y usamos .toFixed(2)
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
            
            // Asignar precio y actualizar totales
            priceInput.value = parseFloat(price).toFixed(2);
            updateTotals();
        }

        function addItem() {
            const newItem = document.createElement('div');
            newItem.classList.add('grid', 'grid-cols-1', 'md:grid-cols-6', 'gap-3', 'mb-4', 'items-center', 'item-row');

            // Usamos la variable productOptionsHtml y la clase de estilo oscuro
            newItem.innerHTML = `
                <div class="col-span-1 md:col-span-2">
                    <select name="sale_items[${itemIndex}][product_id]" required
                        class="block w-full rounded-lg shadow-sm px-4 py-2 product-select-field sm:text-sm ${inputDarkStyle}">
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

            // Conectamos los listeners a los campos del producto
            const productSelect = newItem.querySelector('.product-select-field');
            const priceInput = newItem.querySelector('input[name*="[price]"]');
            const quantityInput = newItem.querySelector('input[name*="[quantity]"]');

            productSelect.addEventListener('change', handleProductChange); 
            quantityInput.addEventListener('input', updateTotals);
            priceInput.addEventListener('input', updateTotals);

            itemIndex++;
        }

        // --- INICIALIZACIÓN DE LISTENERS Y LÓGICA DE CARGA ---

        // Conecta el botón Añadir Producto
        addButton.addEventListener('click', addItem);
        
        // Listener para eliminar ítems
        container.addEventListener('click', e => {
            if (e.target.classList.contains('remove-item-button')) {
                e.target.closest('.item-row').remove();
                updateTotals();
            }
        });

        // Lógica para el botón "Generar Nota IA" (AJAX)
        generateNoteButton.addEventListener('click', async () => {
            // Desactivar botón
            generateNoteButton.disabled = true;
            generateNoteButton.innerText = 'Generando...';
            generateNoteButton.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            generateNoteButton.classList.add('bg-gray-400', 'cursor-not-allowed');

            const customerName = customerSelect.options[customerSelect.selectedIndex]?.dataset?.name;
            
            // Validaciones
            if (!customerName) {
                showMessage('⚠️ Selecciona un cliente para poder generar la nota.', 'error');
                resetGenerateButton();
                return;
            }

            const products = [];
            document.querySelectorAll('.item-row').forEach(row => {
                const productSelect = row.querySelector('select[name*="[product_id]"]');
                const quantityInput = row.querySelector('input[name*="[quantity]"]');
                const priceInput = row.querySelector('input[name*="[price]"]');
                if (productSelect.value && quantityInput.value) {
                    products.push({
                        name: productSelect.options[productSelect.selectedIndex].dataset.name,
                        quantity: quantityInput.value,
                        price: priceInput.value
                    });
                }
            });

            if (products.length === 0) {
                showMessage('⚠️ Añade al menos un producto a la venta.', 'error');
                resetGenerateButton();
                return;
            }

            // Preparar datos para el envío
            const productsText = products.map(p => `${p.quantity} x ${p.name} ($${p.price})`).join(', ');
            const total = totalAmountSpan.innerText;

            try {
                const response = await fetch('{{ route("sales.generate_note") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        customer_name: customerName,
                        products_list: productsText,
                        total_amount: total
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) { 
                    if (data.note) {
                        notesTextarea.value = data.note;
                        showMessage('Nota generada exitosamente.', 'success');
                    } else {
                        throw new Error('El servidor no devolvió el contenido de la nota.');
                    }
                } else {
                    const errorMessage = data.note || data.message || `Error HTTP ${response.status}. Revisa el backend.`;
                    throw new Error(errorMessage);
                }
            } catch (error) {
                // Manejo de errores de conexión o servidor
                const finalMessage = `❌ Error al generar la nota: ${error.message || 'Hubo un problema de conexión.'}`;
                notesTextarea.value = finalMessage;
                showMessage(finalMessage, 'error');
                console.error('Error de generación de nota (JS):', error);
            } finally {
                resetGenerateButton();
            }
        });

        // ⚠️ CLAVE: Agregamos el primer ítem al cargar la página ⚠️
        addItem();
    });
</script>
</body>

</html>