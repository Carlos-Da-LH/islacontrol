<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla del Cliente - IslaControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #5F9E74 0%, #059669 100%);
            overflow: hidden;
        }

        .product-enter {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .total-pulse {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .logo-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header con Logo y Nombre del Negocio -->
    <div class="bg-white shadow-lg py-6 px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <img id="business-logo" src="/images/logo.png" alt="Logo"
                     class="h-16 w-auto logo-float"
                     onerror="this.style.display='none'">
                <div>
                    <h1 id="business-name" class="text-3xl font-black text-gray-800">IslaControl</h1>
                    <p class="text-sm text-gray-500">Punto de Venta</p>
                </div>
            </div>
            <div class="text-right">
                <div id="current-time" class="text-2xl font-bold text-gray-800"></div>
                <div id="current-date" class="text-sm text-gray-600"></div>
            </div>
        </div>
    </div>

    <!-- Área Principal: Lista de Productos -->
    <div class="flex-1 overflow-hidden p-8">
        <div class="max-w-7xl mx-auto h-full">
            <div class="bg-white rounded-3xl shadow-2xl h-full overflow-hidden">

                <!-- Encabezado de la tabla -->
                <div class="bg-gradient-to-r from-emerald-600 to-green-700 text-white py-6 px-8" style="background: linear-gradient(to right, #059669, #047857);">
                    <div class="grid grid-cols-12 gap-4 text-lg font-bold">
                        <div class="col-span-1 text-center">#</div>
                        <div class="col-span-5">PRODUCTO</div>
                        <div class="col-span-2 text-center">CANTIDAD</div>
                        <div class="col-span-2 text-right">PRECIO</div>
                        <div class="col-span-2 text-right">SUBTOTAL</div>
                    </div>
                </div>

                <!-- Lista de productos escaneados -->
                <div id="products-list" class="overflow-y-auto px-8 py-4" style="max-height: calc(100vh - 450px);">
                    <!-- Mensaje cuando no hay productos -->
                    <div id="empty-message" class="text-center py-20">
                        <i class='bx bx-cart text-9xl text-gray-300'></i>
                        <p class="text-3xl text-gray-400 mt-6 font-semibold">Esperando productos...</p>
                        <p class="text-xl text-gray-300 mt-2">Los productos aparecerán aquí al escanearlos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer: Resumen y Total -->
    <div class="bg-white shadow-2xl">
        <div class="max-w-7xl mx-auto py-8 px-8">
            <div class="grid grid-cols-3 gap-8">

                <!-- Productos -->
                <div class="text-center">
                    <p class="text-lg text-gray-600 font-semibold mb-2">PRODUCTOS</p>
                    <div class="bg-emerald-50 rounded-2xl py-4 px-6 border-2 border-emerald-100">
                        <p id="total-products" class="text-5xl font-black text-emerald-700">0</p>
                    </div>
                </div>

                <!-- Unidades -->
                <div class="text-center">
                    <p class="text-lg text-gray-600 font-semibold mb-2">UNIDADES</p>
                    <div class="bg-green-50 rounded-2xl py-4 px-6 border-2 border-green-100" style="background-color: #f0fdf4; border-color: #bbf7d0;">
                        <p id="total-units" class="text-5xl font-black" style="color: #5F9E74;">0</p>
                    </div>
                </div>

                <!-- Total a Pagar -->
                <div class="text-center">
                    <p class="text-lg text-gray-600 font-semibold mb-2">TOTAL A PAGAR</p>
                    <div class="rounded-2xl py-4 px-6 shadow-lg" style="background: linear-gradient(to right, #10b981, #059669);">
                        <p id="total-amount" class="text-5xl font-black text-white">$0.00</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Actualizar reloj
        function updateClock() {
            const now = new Date();
            const timeEl = document.getElementById('current-time');
            const dateEl = document.getElementById('current-date');

            timeEl.textContent = now.toLocaleTimeString('es-MX', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            dateEl.textContent = now.toLocaleDateString('es-MX', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        setInterval(updateClock, 1000);
        updateClock();

        // Cargar configuración del negocio
        async function loadBusinessSettings() {
            try {
                const response = await fetch('/settings/api');
                const settings = await response.json();

                if (settings.nombre_negocio) {
                    document.getElementById('business-name').textContent = settings.nombre_negocio;
                }

                if (settings.logo_url) {
                    document.getElementById('business-logo').src = settings.logo_url + '?v=' + Date.now();
                }
            } catch (error) {
                console.error('Error cargando configuración:', error);
            }
        }

        loadBusinessSettings();

        // Escuchar cambios en el carrito usando BroadcastChannel
        const channel = new BroadcastChannel('pos_channel');

        channel.onmessage = (event) => {
            if (event.data.type === 'cart_update') {
                updateDisplay(event.data.cart);
            } else if (event.data.type === 'cart_clear') {
                clearDisplay();
            }
        };

        // También escuchar usando localStorage como fallback
        window.addEventListener('storage', (e) => {
            if (e.key === 'pos_cart_data') {
                const cart = JSON.parse(e.newValue || '[]');
                updateDisplay(cart);
            }
        });

        // Cargar datos iniciales del localStorage
        function loadInitialData() {
            const cartData = localStorage.getItem('pos_cart_data');
            if (cartData) {
                const cart = JSON.parse(cartData);
                updateDisplay(cart);
            }
        }

        loadInitialData();

        // Actualizar pantalla con los productos del carrito
        function updateDisplay(cartItems) {
            const productsList = document.getElementById('products-list');
            const emptyMessage = document.getElementById('empty-message');
            const totalProducts = document.getElementById('total-products');
            const totalUnits = document.getElementById('total-units');
            const totalAmount = document.getElementById('total-amount');

            if (!cartItems || cartItems.length === 0) {
                emptyMessage.classList.remove('hidden');
                productsList.innerHTML = '';
                productsList.appendChild(emptyMessage);
                totalProducts.textContent = '0';
                totalUnits.textContent = '0';
                totalAmount.textContent = '$0.00';
                return;
            }

            emptyMessage.classList.add('hidden');

            // Generar HTML de productos
            const productsHTML = cartItems.map((item, index) => `
                <div class="product-enter grid grid-cols-12 gap-4 py-6 border-b border-gray-200 items-center hover:bg-emerald-50 transition-colors">
                    <div class="col-span-1 text-center">
                        <span class="text-3xl font-bold text-gray-400">${index + 1}</span>
                    </div>
                    <div class="col-span-5">
                        <h3 class="text-2xl font-bold" style="color: #374151;">${item.name}</h3>
                        <p class="text-sm font-mono" style="color: #6b7280;">${item.barcode || 'Sin código'}</p>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-3xl font-bold" style="color: #5F9E74;">${item.quantity}</span>
                        <span class="text-lg text-gray-400 ml-2">un.</span>
                    </div>
                    <div class="col-span-2 text-right">
                        <span class="text-2xl font-bold" style="color: #374151;">$${item.price.toFixed(2)}</span>
                    </div>
                    <div class="col-span-2 text-right">
                        <span class="text-3xl font-black" style="color: #059669;">$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                </div>
            `).join('');

            productsList.innerHTML = productsHTML;

            // Calcular totales
            const numProducts = cartItems.length;
            const numUnits = cartItems.reduce((sum, item) => sum + item.quantity, 0);
            const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Actualizar con animación
            totalProducts.textContent = numProducts;
            totalUnits.textContent = numUnits;
            totalAmount.textContent = `$${total.toFixed(2)}`;

            // Animar el total
            totalAmount.classList.add('total-pulse');
            setTimeout(() => {
                totalAmount.classList.remove('total-pulse');
            }, 500);
        }

        // Limpiar pantalla
        function clearDisplay() {
            const productsList = document.getElementById('products-list');
            const emptyMessage = document.getElementById('empty-message');

            productsList.innerHTML = '';
            productsList.appendChild(emptyMessage);
            emptyMessage.classList.remove('hidden');

            document.getElementById('total-products').textContent = '0';
            document.getElementById('total-units').textContent = '0';
            document.getElementById('total-amount').textContent = '$0.00';
        }

        // Mensaje de bienvenida
        console.log('%c👁️ Pantalla del Cliente Activa', 'color: #667eea; font-size: 20px; font-weight: bold;');
    </script>

</body>
</html>
