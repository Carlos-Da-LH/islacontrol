<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | IslaControl</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0/dist/tf.min.js"></script>

    <style>
        /* Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background-color: #ffffff;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        /* Sidebar Navigation */
        .sidebar-link.active {
            background-color: #5F9E74;
            color: white !important;
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: white !important;
        }

        .sidebar-link:hover i {
            color: #5F9E74;
        }

        /* Specific Components */
        .chat-container {
            height: 500px;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 320px;
        }

        /* Effects */
        .metric-card {
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
        }

        /* Loading Skeleton (If applicable) */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* 🚀 Nuevos estilos para Mobile-First y Responsividad 🚀 */

        /* Ocultar/Mostrar Sidebar */
        .sidebar {
            /* Fijo, en el alto de la pantalla, sin ocupar espacio de header en el cálculo */
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            width: 70%; /* MODIFICADO: Ancho mediano para móvil */
            max-width: 280px; /* MODIFICADO: Ancho mediano para móvil */
            overflow-y: hidden;
        }
        
        /* Contenedor principal para el scroll (navegación) */
        /* Se ajusta el padding top para compensar la posición del logo en móvil/escritorio */
        .sidebar-content-scrollable {
            padding: 0 1.5rem; /* p-6 horizontal */
            overflow-y: auto;
            flex-grow: 1;
        }
        
        .sidebar.open {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Mostrar el sidebar y agregar margen en escritorio (> 1024px) */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
                width: 256px;
                max-width: 256px;
                height: 100vh;
                overflow-y: auto; 
            }
            /* Resetear padding para desktop */
            .sidebar-content-scrollable {
                padding-top: 0; /* Se elimina el padding, ya que el logo está en su propio div p-6 */
                padding-bottom: 1.5rem;
                overflow-y: visible;
                flex-grow: 0; 
            }

            .main-content {
                margin-left: 256px;
            }
            /* Asegurar que el logo de escritorio se muestre */
            .sidebar .hidden.lg\:flex { 
                display: flex !important;
            }
        }

        /* Overlay para móvil */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }
        .overlay.visible {
            display: block;
        }

    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-active': '#5F9E74',
                        'custom-text': '#374151',
                        'custom-gray': '#6b7280'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50">
    <div id="sidebar-overlay" class="overlay" onclick="toggleSidebar()"></div>

    <header class="lg:hidden bg-white shadow-md p-4 sticky top-0 z-30 flex justify-between items-center border-b border-gray-200">
        <h1 class="text-xl font-bold text-custom-active">IslaControl</h1>
        <button id="menu-toggle" onclick="toggleSidebar()" class="text-2xl text-custom-text">
            <i class='bx bx-menu'></i>
        </button>
    </header>

    <div id="sidebar" class="sidebar bg-white text-gray-800 flex flex-col shadow-lg border-r border-gray-200">

        <div class="flex items-center justify-center pt-6 pb-4 border-b border-gray-200">
            <img src="/images/nuevo_islacontrol.png" class="h-48 w-48 object-contain drop-shadow-xl" alt="Logo IslaControl">
        </div>
        
        <div class="sidebar-content-scrollable" id="sidebar-nav-container">
            <nav class="space-y-2 pb-6 pt-6" id="sidebar-nav">
                <a href="#dashboard" data-page="dashboard" class="sidebar-link active flex items-center p-3 rounded-lg text-sm font-semibold transition duration-200 text-gray-700" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bxs-dashboard text-xl mr-3 text-gray-400'></i> Dashboard
                </a>
                <a href="#ia-financiera" data-page="ia-financiera" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <span class="text-xl mr-3">🏝️</span> IslaFinance IA
                </a>
                <a href="#sales" data-page="sales" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bxs-credit-card text-xl mr-3 text-gray-400'></i> Ventas
                </a>
                <a href="#products" data-page="products" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bxs-shopping-bag text-xl mr-3 text-gray-400'></i> Productos
                </a>
                <a href="#categories" data-page="categories" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bxs-purchase-tag text-xl mr-3 text-gray-400'></i> Categorías
                </a>
                <a href="#customers" data-page="customers" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bxs-group text-xl mr-3 text-gray-400'></i> Clientes
                </a>
                <a href="#codigo-qr" data-page="codigo-qr" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bx-qr text-xl mr-3 text-gray-400'></i> Código QR
                </a>
                <a href="#scan-product" data-page="scan-product" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bx-barcode-reader text-xl mr-3 text-gray-400'></i> Escanear Producto
                </a>
            </nav>
        </div>

        <div class="border-t border-gray-200 p-4 lg:p-6 flex-shrink-0">
            <div class="flex items-center p-3 rounded-lg hover:bg-gray-100 cursor-pointer transition duration-200" onclick="logout();">
                <div id="user-profile-image" class="h-8 w-8 bg-custom-active text-white rounded-full flex items-center justify-center text-sm font-bold overflow-hidden">C</div>
                <div class="ml-3">
                    <p id="user-profile-name" class="text-sm font-semibold text-custom-text">Cargando...</p>
                    <p class="text-xs text-custom-gray">Cerrar Sesión</p>
                </div>
            </div>
        </div>
    </div>
    <div id="main-content-area" class="main-content min-h-screen p-4 sm:p-8">
        <div class="flex items-center justify-center h-96">
            <i class='bx bx-loader-alt bx-spin text-custom-active text-6xl'></i>
            <span class="ml-4 text-xl text-custom-gray">Inicializando sistema...</span>
        </div>
    </div>

    <script>
        console.log("🚀 Inicializando IslaControl Dashboard v2...");

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const firebaseConfig = {
            apiKey: "AIzaSyA8VguwL3jh2lIVpBSRrOvjy-c0PfmGD-4",
            authDomain: "isla-control.firebaseapp.com",
            projectId: "isla-control",
            storageBucket: "isla-control.firebasestorage.app",
            messagingSenderId: "145410754650",
            appId: "1:145410754650:web:8d590e161d280094a6f063",
            measurementId: "G-Z5RWFK99Q8"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const auth = firebase.auth();
        console.log("✅ Firebase inicializado");

        let currentUser = null;
        let currentUserEmail = null;
        let financialAI = null;
        let dashboardData = {
            sales: [],
            products: [],
            categories: [],
            customers: [],
            totalSalesToday: 0
        };
        let charts = {};
        let refreshInterval = null;

        class IslaPredictAI {
            constructor() {
                this.isModelReady = false;
                this.initialize();
            }

            async initialize() {
                try {
                    if (typeof tf === 'undefined') {
                        await Promise.race([
                            new Promise(resolve => {
                                const checkTF = setInterval(() => {
                                    if (typeof tf !== 'undefined') {
                                        clearInterval(checkTF);
                                        resolve();
                                    }
                                }, 100);
                                setTimeout(() => clearInterval(checkTF), 5000);
                            }),
                            new Promise(resolve => setTimeout(resolve, 5000))
                        ]);
                    }

                    if (typeof tf !== 'undefined') {
                        await tf.ready();
                    }
                    this.isModelReady = true;
                    console.log('✅ IslaFinance IA lista');
                } catch (error) {
                    console.warn('⚠️ IA cargará sin TensorFlow');
                    this.isModelReady = true;
                }
            }

            analyzeFinancialData(data) {
                // CÁLCULO DE MÉTRICAS DETALLADO
                const now = new Date();
                const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                const endLastMonth = new Date(now.getFullYear(), now.getMonth(), 0);

                let salesThisMonth = 0;
                let salesLastMonth = 0;
                let revenueThisMonth = 0;
                let revenueLastMonth = 0;
                let totalSalesRevenue = 0;

                data.sales.forEach(sale => {
                    const date = new Date(sale.fecha || sale.created_at || new Date());
                    const amount = parseFloat(sale.monto || sale.total || 0);
                    totalSalesRevenue += amount;

                    if (date >= startOfMonth) {
                        salesThisMonth++;
                        revenueThisMonth += amount;
                    } else if (date >= lastMonth && date <= endLastMonth) {
                        salesLastMonth++;
                        revenueLastMonth += amount;
                    }
                });

                // CÁLCULO DE TENDENCIAS
                const salesTrend = salesLastMonth > 0 ? ((salesThisMonth - salesLastMonth) / salesLastMonth) * 100 : (salesThisMonth > 0 ? 100 : 0);
                const revenueTrend = revenueLastMonth > 0 ? ((revenueThisMonth - revenueLastMonth) / revenueLastMonth) * 100 : (revenueThisMonth > 0 ? 100 : 0);


                const metrics = {
                    totalSales: salesThisMonth,
                    totalSalesAllTime: data.sales.length,
                    revenueThisMonth: revenueThisMonth,
                    totalRevenueAllTime: totalSalesRevenue,
                    salesTrend: salesTrend,
                    revenueTrend: revenueTrend,
                    totalProducts: data.products.length,
                    totalCustomers: data.customers.length,
                    avgSale: data.sales.length > 0 ? totalSalesRevenue / data.sales.length : 0,
                    lowStock: data.products.filter(p => parseInt(p.stock || 0) < 5).length
                };

                return metrics;
            }

            calculateTrend(sales) {
                return 0; // Se delega al método analyzeFinancialData
            }

            calculateRetention(customers) {
                return customers.length > 0 ? Math.min(95 + (customers.length * 0.5), 99) : 0;
            }

            async generateResponse(question, data) {
                if (!this.isModelReady) {
                    return {
                        response: "⏳ Inicializando...",
                        confidence: 0.5
                    };
                }

                const metrics = this.analyzeFinancialData(data);
                const lowerQ = question.toLowerCase();
                let response = '';

                if (lowerQ.includes('hola') || lowerQ.includes('buenos')) {
                    const trend = metrics.salesTrend > 0 ? '📈' : '📉';
                    response = `¡Hola! 👋\n📊 Estado actual:\n• ${metrics.totalSales} ventas ${trend}\n• Ingresos: $${metrics.revenueThisMonth.toFixed(2)}\n• ${metrics.totalProducts} productos\n• Stock bajo: ${metrics.lowStock} productos`;
                } else if (lowerQ.includes('venta')) {
                    response = `📊 Análisis de Ventas:\n• Total (Este mes): ${metrics.totalSales}\n• Ingresos (Este mes): $${metrics.revenueThisMonth.toFixed(2)}\n• Promedio: $${metrics.avgSale.toFixed(2)}\n• Tendencia: ${metrics.salesTrend.toFixed(1)}%`;
                } else if (lowerQ.includes('producto') || lowerQ.includes('inventario')) {
                    response = `📦 Estado del Inventario:\n• Productos: ${metrics.totalProducts}\n• Stock Total: ${data.products.reduce((sum, p) => sum + parseInt(p.stock || 0), 0)} unidades\n• Stock Bajo: ${metrics.lowStock} productos`;
                } else if (lowerQ.includes('cliente')) {
                    response = `👥 Análisis de Clientes:\n• Total: ${metrics.totalCustomers}\n• Retención: ${this.calculateRetention(data.customers).toFixed(1)}%`;
                } else if (lowerQ.includes('recomendación') || lowerQ.includes('consejo')) {
                    response = `💡 Recomendaciones:\n• Actualiza ${metrics.lowStock} productos con stock bajo\n• Promedio de venta: $${metrics.avgSale.toFixed(2)}\n• Enfócate en los mejores clientes (${metrics.totalCustomers})`;
                } else {
                    response = `Intenta preguntar sobre:\n• "Ventas" - análisis de ventas\n• "Inventario" - estado de productos\n• "Clientes" - análisis de clientes\n• "Recomendaciones" - sugerencias`;
                }

                return {
                    response,
                    confidence: 0.85
                };
            }
        }

        async function fetchDashboardData(userEmail) {
            try {
                const headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-User-Email': userEmail
                };

                const [salesRes, productsRes, categoriesRes, customersRes] = await Promise.all([
                    fetch('/sales/api', {
                        headers,
                        timeout: 10000
                    }),
                    fetch('/products/api', {
                        headers,
                        timeout: 10000
                    }),
                    fetch('/categories/api', {
                        headers,
                        timeout: 10000
                    }),
                    fetch('/customers/api', {
                        headers,
                        timeout: 10000
                    })
                ]);

                const [sales, products, categories, customers] = await Promise.all([
                    salesRes.ok ? salesRes.json() : [],
                    productsRes.ok ? productsRes.json() : [],
                    categoriesRes.ok ? categoriesRes.json() : [],
                    customersRes.ok ? customersRes.json() : []
                ]);

                dashboardData.sales = Array.isArray(sales) ? sales : [];
                dashboardData.products = Array.isArray(products) ? products : [];
                dashboardData.categories = Array.isArray(categories) ? categories : [];
                dashboardData.customers = Array.isArray(customers) ? customers : [];

                console.log('✅ Datos cargados rápidamente');

                // Actualiza las métricas con los datos recién cargados
                if (financialAI) {
                    updateMetricCards(financialAI.analyzeFinancialData(dashboardData));
                }

                return true;
            } catch (error) {
                console.warn('⚠️ Error cargando datos:', error);
                return false;
            }
        }

        function addChatMessage(sender, message) {
            const chatContainer = document.getElementById('ai-chat-messages');
            if (!chatContainer) return;

            const isUser = sender === 'user';
            const bgColor = isUser ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'mb-4 animate-fadeIn';
            messageDiv.innerHTML = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-xs sm:max-w-sm"> ${!isUser ? '<div class="flex items-center mb-2"><span class="text-2xl mr-2">🏝️</span></div>' : ''}
                        <div class="${bgColor} rounded-2xl px-4 py-3 shadow-sm"><div class="text-sm">${message.replace(/\n/g, '<br>')}</div></div>
                    </div>
                </div>
            `;
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        window.sendMessageToAI = async function() {
            const input = document.getElementById('ai-chat-input');
            const question = input.value.trim();
            if (!question || !financialAI?.isModelReady) return;

            addChatMessage('user', question);
            input.value = '';

            try {
                const result = await financialAI.generateResponse(question, dashboardData);
                addChatMessage('ai', result.response);
            } catch (error) {
                addChatMessage('ai', '❌ Error procesando la pregunta');
            }
        };

        window.askSuggestion = function(question) {
            const input = document.getElementById('ai-chat-input');
            if (input) {
                input.value = question;
                window.sendMessageToAI();
            }
        };

        function updateSidebarProfile(user) {
            const profileNameElement = document.getElementById('user-profile-name');
            const profileImageContainer = document.getElementById('user-profile-image');

            if (!profileNameElement || !profileImageContainer) return;

            if (user && user.email) {
                const name = user.displayName || user.email.split('@')[0];
                profileNameElement.textContent = name;

                if (user.photoURL) {
                    profileImageContainer.innerHTML = `<img src="${user.photoURL}" class="h-full w-full object-cover rounded-full" alt="Foto">`;
                } else {
                    const initial = name.charAt(0).toUpperCase();
                    profileImageContainer.textContent = initial;
                }
            }
        }

        window.logout = function() {
            if (refreshInterval) clearInterval(refreshInterval);
            auth.signOut().then(() => {
                window.location.replace('/');
            });
        }

        function updateActiveLink(pageName) {
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active', 'text-white');
                link.classList.add('text-custom-gray', 'hover:bg-gray-100');
            });

            const active = document.querySelector(`[data-page="${pageName}"]`);
            if (active) {
                active.classList.add('active');
                active.classList.remove('text-custom-gray');
            }
        }

        // FUNCIÓN CLAVE: ACTUALIZA LOS CUADROS DE MÉTRICAS
        function updateMetricCards(metrics) {
            const totalSalesEl = document.getElementById('metric-totalSales');
            const salesTrendEl = document.getElementById('metric-salesTrend');

            const totalRevenueEl = document.getElementById('metric-totalRevenue');
            const revenueTrendEl = document.getElementById('metric-revenueTrend');

            const totalProductsEl = document.getElementById('metric-totalProducts');
            const lowStockEl = document.getElementById('metric-lowStock');

            const totalCustomersEl = document.getElementById('metric-totalCustomers');

            const avgSaleEl = document.getElementById('metric-avgSale');

            if (totalSalesEl) totalSalesEl.textContent = metrics.totalSales;

            if (salesTrendEl) {
                const trendIcon = metrics.salesTrend >= 0 ? '↑' : '↓';
                salesTrendEl.className = `text-xs mt-2 ${metrics.salesTrend >= 0 ? 'text-green-600' : 'text-red-600'}`;
                salesTrendEl.textContent = `${trendIcon} ${Math.abs(metrics.salesTrend).toFixed(0)}% este mes`;
            }

            if (totalRevenueEl) totalRevenueEl.textContent = `$${metrics.revenueThisMonth.toFixed(0)}`;

            if (revenueTrendEl) {
                const trendText = metrics.revenueTrend >= 0 ? '↑ Crecimiento positivo' : '↓ Crecimiento negativo';
                revenueTrendEl.className = `text-xs mt-2 ${metrics.revenueTrend >= 0 ? 'text-green-600' : 'text-red-600'}`;
                revenueTrendEl.textContent = trendText;
            }

            if (totalProductsEl) totalProductsEl.textContent = metrics.totalProducts;
            if (lowStockEl) {
                const lowStockText = metrics.lowStock > 0 ? `⚠️ ${metrics.lowStock} bajo stock` : 'Estable';
                lowStockEl.className = `text-xs mt-2 ${metrics.lowStock > 0 ? 'text-red-600' : 'text-green-600'}`;
                lowStockEl.textContent = lowStockText;
            }

            if (totalCustomersEl) totalCustomersEl.textContent = metrics.totalCustomers;

            if (avgSaleEl) avgSaleEl.textContent = `$${metrics.avgSale.toFixed(0)}`;
        }

        // ==================== 10 GRÁFICAS EN TIEMPO REAL ====================

        function renderSalesChart() {
            const ctx = document.getElementById('chartVentas');
            if (!ctx) return;

            const salesByMonth = {};
            dashboardData.sales.forEach(sale => {
                const date = new Date(sale.fecha || sale.created_at || new Date());
                const month = date.toLocaleString('es-ES', {
                    month: 'short',
                    year: 'numeric'
                });
                salesByMonth[month] = (salesByMonth[month] || 0) + 1;
            });

            const labels = Object.keys(salesByMonth).slice(-6);
            const data = labels.map(label => salesByMonth[label] || 0);

            const colors = ['#00D084', '#FFD700', '#5B9BD5', '#9370DB'];

            if (charts.sales) charts.sales.destroy();

            charts.sales = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Ventas',
                        data: data.length ? data : [0],
                        backgroundColor: colors.slice(0, labels.length).concat(colors)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderRevenueChart() {
            const ctx = document.getElementById('chartIngresos');
            if (!ctx) return;

            const revenueByMonth = {};
            dashboardData.sales.forEach(sale => {
                const date = new Date(sale.fecha || sale.created_at || new Date());
                const month = date.toLocaleString('es-ES', {
                    month: 'short',
                    year: 'numeric'
                });
                revenueByMonth[month] = (revenueByMonth[month] || 0) + parseFloat(sale.monto || sale.total || 0);
            });

            const labels = Object.keys(revenueByMonth).slice(-12);
            const data = labels.map(label => revenueByMonth[label] || 0);

            const colors = ['#00D084', '#FFD700', '#5B9BD5', '#9370DB'];
            const coloredData = data.map((_, i) => colors[i % colors.length]);

            if (charts.revenue) charts.revenue.destroy();

            charts.revenue = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: data.length ? data : [0],
                        backgroundColor: coloredData
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderProductsChart() {
            const ctx = document.getElementById('chartProductos');
            if (!ctx) return;

            const sorted = [...dashboardData.products].sort((a, b) => parseInt(b.stock || 0) - parseInt(a.stock || 0));
            const labels = sorted.slice(0, 8).map(p => p.nombre || p.name || p.product || p.title || 'Sin nombre');
            const data = sorted.slice(0, 8).map(p => parseInt(p.stock || p.cantidad || p.quantity || 0));

            const colors = ['#00D084', '#FFD700', '#5B9BD5', '#9370DB'];
            const coloredData = data.map((_, i) => colors[i % colors.length]);

            if (charts.products) charts.products.destroy();

            charts.products = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin productos'],
                    datasets: [{
                        label: 'Stock',
                        data: data.length ? data : [0],
                        backgroundColor: coloredData
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderCategoriesChart() {
            const ctx = document.getElementById('chartCategorias');
            if (!ctx) return;

            const categoryCount = {};
            dashboardData.products.forEach(p => {
                const cat = p.categoria || p.category || 'Sin categoría';
                categoryCount[cat] = (categoryCount[cat] || 0) + 1;
            });

            const rainbowColors = [
                '#00D084', '#FFD700', '#5B9BD5', '#9370DB'
            ];

            const labels = Object.keys(categoryCount);
            const data = Object.values(categoryCount);
            const coloredData = data.map((_, i) => rainbowColors[i % rainbowColors.length]);

            if (charts.categories) charts.categories.destroy();

            charts.categories = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        data: data.length ? data : [0],
                        backgroundColor: coloredData
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15
                            }
                        }
                    }
                }
            });
        }

        function renderInventoryValueChart() {
            const ctx = document.getElementById('chartValorInventario');
            if (!ctx) return;

            const categoryValues = {};
            dashboardData.products.forEach(p => {
                const cat = p.categoria || p.category || p.type || 'Sin categoría';
                const value = (parseInt(p.stock || p.cantidad || p.quantity || 0) * parseFloat(p.precio || p.price || p.cost || 0));
                categoryValues[cat] = (categoryValues[cat] || 0) + value;
            });

            const labels = Object.keys(categoryValues);
            const data = Object.values(categoryValues);

            const rainbowColors = [
                '#00D084', '#FFD700', '#FFD700', '#00D084', '#5B9BD5', '#9370DB', '#9370DB'
            ];

            if (charts.inventory) charts.inventory.destroy();

            charts.inventory = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Valor ($)',
                        data: data.length ? data : [0],
                        backgroundColor: rainbowColors.slice(0, labels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderCustomersChart() {
            const ctx = document.getElementById('chartClientes');
            if (!ctx) return;

            const totalCustomers = dashboardData.customers.length;
            const totalSales = dashboardData.sales.length;
            const avgSale = totalSales > 0 ? dashboardData.sales.reduce((sum, s) => sum + parseFloat(s.monto || s.total || 0), 0) / totalSales : 0;

            if (charts.customers) charts.customers.destroy();

            charts.customers = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Clientes', 'Ventas', 'Promedio'],
                    datasets: [{
                        label: 'Análisis',
                        data: [totalCustomers, totalSales, avgSale],
                        backgroundColor: ['#00D084', '#FFD700', '#5B9BD5']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderPriceRangeChart() {
            const ctx = document.getElementById('chartRangoPrecio');
            if (!ctx) return;

            const ranges = {
                'Bajo (<50)': 0,
                'Medio (50-200)': 0,
                'Alto (200-500)': 0,
                'Premium (>500)': 0
            };
            dashboardData.products.forEach(p => {
                const price = parseFloat(p.precio || p.price || p.cost || 0);
                if (price < 50) ranges['Bajo (<50)']++;
                else if (price < 200) ranges['Medio (50-200)']++;
                else if (price < 500) ranges['Alto (200-500)']++;
                else ranges['Premium (>500)']++;
            });

            if (charts.priceRange) charts.priceRange.destroy();

            charts.priceRange = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: Object.keys(ranges),
                    datasets: [{
                        data: Object.values(ranges),
                        backgroundColor: ['#00D084', '#FFD700', '#5B9BD5', '#9370DB']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function renderSalesChannelChart() {
            const ctx = document.getElementById('chartCanal');
            if (!ctx) return;

            const channels = dashboardData.sales.reduce((acc, s) => {
                const channel = s.canal || 'Directo';
                acc[channel] = (acc[channel] || 0) + parseFloat(s.monto || s.total || 0);
                return acc;
            }, {});

            if (charts.channel) charts.channel.destroy();

            const rainbowColors = [
                '#00D084', '#FFD700', '#FFD700', '#00D084', '#5B9BD5', '#9370DB', '#9370DB'
            ];

            charts.channel = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(channels),
                    datasets: [{
                        label: 'Ingresos por Canal ($)',
                        data: Object.values(channels),
                        backgroundColor: rainbowColors.slice(0, Object.keys(channels).length)
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderStockAlertChart() {
            const ctx = document.getElementById('chartAlertaStock');
            if (!ctx) return;

            const critical = dashboardData.products.filter(p => parseInt(p.stock || 0) < 3).length;
            const warning = dashboardData.products.filter(p => parseInt(p.stock || 0) >= 3 && parseInt(p.stock || 0) < 10).length;
            const ok = dashboardData.products.filter(p => parseInt(p.stock || 0) >= 10).length;

            if (charts.stockAlert) charts.stockAlert.destroy();

            charts.stockAlert = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Crítico', 'Advertencia', 'Óptimo'],
                    datasets: [{
                        label: 'Productos',
                        data: [critical, warning, ok],
                        backgroundColor: ['#00D084', '#FFD700', '#00D084']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderTopProductsChart() {
            const ctx = document.getElementById('chartTopProductos');
            if (!ctx) return;

            const productSales = {};
            dashboardData.sales.forEach(s => {
                const product = s.producto || s.nombre || s.product || s.name || s.title || 'Sin producto';
                productSales[product] = (productSales[product] || 0) + parseFloat(s.monto || s.total || s.amount || 0);
            });

            const sorted = Object.entries(productSales)
                .sort((a, b) => b[1] - a[1])
                .filter(([name]) => name !== 'Sin producto')
                .slice(0, 6);

            const finalData = sorted.length > 0 ? sorted : Object.entries(productSales).sort((a, b) => b[1] - a[1]).slice(0, 6);

            const rainbowColors = ['#00D084', '#FFD700', '#5B9BD5', '#9370DB', '#00D084', '#FFD700'];

            if (charts.topProducts) charts.topProducts.destroy();

            charts.topProducts = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: finalData.map(s => s[0]),
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: finalData.map(s => s[1]),
                        backgroundColor: rainbowColors.slice(0, finalData.length)
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderAllCharts() {
            renderSalesChart();
            renderRevenueChart();
            renderProductsChart();
            renderCategoriesChart();
            renderInventoryValueChart();
            renderCustomersChart();
            renderPriceRangeChart();
            renderSalesChannelChart();
            renderStockAlertChart();
            renderTopProductsChart();
        }

        // FUNCIÓN DE CONTENIDO PARA 'ESCANEAR PRODUCTO'
        function renderScanProduct() {
            const contentArea = document.getElementById('main-content-area');

            contentArea.innerHTML = `
                <div class="max-w-full">
                    <div class="dashboard-card p-6 sm:p-8 rounded-xl shadow-lg border-t-4 border-custom-active">
                        <div class="flex items-center mb-6">
                            <i class='bx bx-barcode-reader text-3xl sm:text-4xl mr-4' style="color: #5F9E74;"></i>
                            <div>
                                <h3 class="text-2xl sm:text-3xl font-bold text-custom-text">Escanear Producto</h3>
                                <p class="text-sm sm:text-base text-custom-gray">Utiliza la cámara o el escáner para buscar un producto.</p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6 bg-gray-50 rounded-lg text-center border-dashed border-2 border-gray-300">
                            <p class="text-base sm:text-lg font-medium text-custom-gray">Aquí se implementará el escáner de código de barras/QR.</p>
                            <div class="mt-4">
                                <button onclick="alert('Función de Escaneo Iniciada')" class="bg-custom-active hover:bg-green-700 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-medium transition-colors shadow-md text-sm sm:text-base">
                                    Activar Escáner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // FUNCIÓN CLAVE: RENDERIZA LA ESTRUCTURA DEL DASHBOARD CON VALORES DINÁMICOS
        function renderDashboard(metrics) {
            const contentArea = document.getElementById('main-content-area');

            // Se hicieron los siguientes ajustes de Tailwind para móvil:
            // - Ajuste de padding en `p-4 sm:p-8` en main-content-area (línea ~1235)
            // - `grid-cols-1 sm:grid-cols-2 lg:grid-cols-5` en las métricas
            // - `text-2xl sm:text-3xl` en los números de las métricas
            // - `grid-cols-1 lg:grid-cols-2` en las gráficas
            
            contentArea.innerHTML = `
                <div class="max-w-full mx-auto">
                    <div class="mb-6 sm:mb-8">
                        <h1 class="text-3xl sm:text-4xl font-bold text-custom-text mb-2">Dashboard de Negocio</h1>
                        <p class="text-base sm:text-lg text-custom-gray">Bienvenido, <span class="font-semibold text-custom-active" id="user-name">Usuario</span></p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                        <div class="dashboard-card metric-card p-4 sm:p-6 rounded-xl shadow-lg border-l-4" style="border-left-color: #E94B3C;">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-custom-gray uppercase">Total Ventas</h3>
                                <i class='bx bxs-credit-card text-xl sm:text-2xl' style="color: #00D084;"></i>
                            </div>
                            <p id="metric-totalSales" class="text-2xl sm:text-3xl font-bold text-custom-text">${metrics.totalSales}</p>
                            <p id="metric-salesTrend" class="text-xs mt-2 ${metrics.salesTrend >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${metrics.salesTrend >= 0 ? '↑' : '↓'} ${Math.abs(metrics.salesTrend).toFixed(0)}% este mes
                            </p>
                        </div>

                        <div class="dashboard-card metric-card p-4 sm:p-6 rounded-xl shadow-lg border-l-4" style="border-left-color: #E6A646;">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-custom-gray uppercase">Ingresos</h3>
                                <i class='bx bxs-dollar-circle text-xl sm:text-2xl' style="color: #FFD700;"></i>
                            </div>
                            <p id="metric-totalRevenue" class="text-2xl sm:text-3xl font-bold text-custom-text">$${metrics.revenueThisMonth.toFixed(0)}</p>
                            <p id="metric-revenueTrend" class="text-xs mt-2 ${metrics.revenueTrend >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${metrics.revenueTrend >= 0 ? '↑ Crecimiento positivo' : '↓ Crecimiento negativo'}
                            </p>
                        </div>

                        <div class="dashboard-card metric-card p-4 sm:p-6 rounded-xl shadow-lg border-l-4" style="border-left-color: #D9D044;">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-custom-gray uppercase">Productos</h3>
                                <i class='bx bxs-shopping-bag text-xl sm:text-2xl' style="color: #FFD700;"></i>
                            </div>
                            <p id="metric-totalProducts" class="text-2xl sm:text-3xl font-bold text-custom-text">${metrics.totalProducts}</p>
                            <p id="metric-lowStock" class="text-xs mt-2 ${metrics.lowStock > 0 ? 'text-red-600' : 'text-green-600'}">
                                ${metrics.lowStock > 0 ? `⚠️ ${metrics.lowStock} bajo stock` : 'Estable'}
                            </p>
                        </div>

                        <div class="dashboard-card metric-card p-4 sm:p-6 rounded-xl shadow-lg border-l-4" style="border-left-color: #5FBB7F;">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-custom-gray uppercase">Clientes</h3>
                                <i class='bx bxs-group text-xl sm:text-2xl' style="color: #00D084;"></i>
                            </div>
                            <p id="metric-totalCustomers" class="text-2xl sm:text-3xl font-bold text-custom-text">${metrics.totalCustomers}</p>
                            <p class="text-xs text-blue-600 mt-2">→ Estable</p>
                        </div>

                        <div class="dashboard-card metric-card p-4 sm:p-6 rounded-xl shadow-lg border-l-4" style="border-left-color: #5B9BD5;">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-custom-gray uppercase">Promedio Venta</h3>
                                <i class='bx bx-trending-up text-xl sm:text-2xl' style="color: #5B9BD5;"></i>
                            </div>
                            <p id="metric-avgSale" class="text-2xl sm:text-3xl font-bold text-custom-text">$${metrics.avgSale.toFixed(0)}</p>
                            <p class="text-xs text-gray-600 mt-2">Por transacción</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-red-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-line-chart text-2xl mr-2' style="color: #00D084;"></i>
                                Ventas por Mes
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartVentas"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-orange-500 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-bar-chart-alt text-2xl mr-2' style="color: #FFD700;"></i>
                                Ingresos por Mes
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartIngresos"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-yellow-500 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-package text-2xl mr-2' style="color: #FFD700;"></i>
                                Productos por Stock
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartProductos"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-green-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-pie-chart-alt text-2xl mr-2' style="color: #00D084;"></i>
                                Categorías
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartCategorias"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-blue-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-radar text-2xl mr-2' style="color: #5B9BD5;"></i>
                                Valor Inventario
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartValorInventario"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-indigo-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-chart text-2xl mr-2' style="color: #7366BD;"></i>
                                Análisis General
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartClientes"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-purple-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-money text-2xl mr-2' style="color: #C55A8D;"></i>
                                Rango de Precios
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartRangoPrecio"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-pink-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-git-branch text-2xl mr-2' style="color: #FF1493;"></i>
                                Canal de Ventas
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartCanal"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-cyan-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-alarm-exclamation text-2xl mr-2' style="color: #00CED1;"></i>
                                Estado de Stock
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartAlertaStock"></canvas>
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-lime-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-crown text-2xl mr-2' style="color: #32CD32;"></i>
                                Top Productos
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartTopProductos"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            renderAllCharts();
        }

        function renderIslaFinance() {
            const contentArea = document.getElementById('main-content-area');

            contentArea.innerHTML = `
                <div class="max-w-full">
                    <div class="dashboard-card p-6 sm:p-8 rounded-xl shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="text-4xl sm:text-5xl mr-4">🏝️</div>
                            <div>
                                <h3 class="text-2xl sm:text-3xl font-bold text-custom-active">IslaFinance IA</h3>
                                <p class="text-sm sm:text-base text-custom-gray">Tu asistente de inteligencia empresarial en tiempo real</p>
                            </div>
                        </div>

                        <div class="mb-6 flex flex-wrap gap-3">
                            <button onclick="askSuggestion('¿Cuántas ventas tengo?')" class="text-xs sm:text-sm bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium">📊 Análisis Ventas</button>
                            <button onclick="askSuggestion('Estado del inventario')" class="text-xs sm:text-sm bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium">📦 Inventario</button>
                            <button onclick="askSuggestion('¿Cuántos clientes tengo?')" class="text-xs sm:text-sm bg-lime-100 hover:bg-lime-200 text-lime-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium">👥 Clientes</button>
                            <button onclick="askSuggestion('Recomendaciones')" class="text-xs sm:text-sm bg-teal-100 hover:bg-teal-200 text-teal-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium">💡 Recomendaciones</button>
                            <button onclick="askSuggestion('Hola')" class="text-xs sm:text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium">👋 Saludar</button>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div id="ai-chat-messages" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 sm:p-6 border border-gray-200 chat-container shadow-inner"></div>
                            <div class="flex gap-3">
                                <input type="text" id="ai-chat-input" placeholder="Pregúntame sobre tu negocio, ventas, inventario, clientes..." class="flex-1 px-3 py-3 sm:px-5 sm:py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-active focus:border-transparent text-sm sm:text-base" onkeypress="if(event.key === 'Enter') sendMessageToAI()">
                                <button onclick="sendMessageToAI()" class="bg-custom-active hover:bg-green-700 text-white px-4 py-3 sm:px-8 sm:py-4 rounded-lg font-medium transition-colors shadow-md">
                                    <i class='bx bx-send text-lg sm:text-xl'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            setTimeout(() => {
                if (financialAI && financialAI.isModelReady) {
                    addChatMessage('ai', '¡Hola! 👋\n\nSoy IslaFinance IA, tu asistente de inteligencia empresarial. Estoy conectado a todos tus datos en tiempo real.\n\n¿En qué puedo ayudarte hoy? Puedo analizar ventas, inventario, clientes y mucho más.');
                }
            }, 500);
        }

        async function loadContent(pageName) {
            const contentArea = document.getElementById('main-content-area');

            if (pageName === 'dashboard') {
                contentArea.innerHTML = `
                    <div class="flex items-center justify-center h-96">
                        <div class="text-center">
                            <i class='bx bx-loader-alt bx-spin text-6xl text-custom-active mb-4'></i>
                            <p class="text-lg text-custom-gray font-medium">Cargando Dashboard...</p>
                        </div>
                    </div>
                `;

                if (currentUserEmail) {
                    await fetchDashboardData(currentUserEmail);
                }

                const metrics = financialAI.analyzeFinancialData(dashboardData);
                renderDashboard(metrics);
                updateActiveLink('dashboard');
                return;
            }

            if (pageName === 'ia-financiera') {
                renderIslaFinance();
                updateActiveLink('ia-financiera');
                return;
            }

            // NUEVA PÁGINA PARA ESCANEO
            if (pageName === 'scan-product') {
                renderScanProduct();
                updateActiveLink('scan-product');
                return;
            }


            const crudRoutes = {
                'sales': '/sales',
                'products': '/products',
                'categories': '/categories',
                'customers': '/customers',
                'codigo-qr': '/codigo-qr',
                // Si 'scan-product' va a cargar una página externa, descomenta y usa esta línea:
                // 'scan-product': '/scan-product'
            };

            if (crudRoutes[pageName]) {
                contentArea.innerHTML = `
                    <div class="flex items-center justify-center h-96">
                        <div class="text-center">
                            <i class='bx bx-loader-alt bx-spin text-6xl text-custom-active mb-4'></i>
                            <p class="text-lg text-custom-gray font-medium">Cargando ${pageName}...</p>
                        </div>
                    </div>
                `;

                try {
                    const response = await fetch(crudRoutes[pageName], {
                        method: 'GET',
                        headers: {
                            'Accept': 'text/html',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                    const html = await response.text();
                    contentArea.innerHTML = html;

                    const scripts = contentArea.querySelectorAll("script");
                    scripts.forEach(script => {
                        const newScript = document.createElement("script");
                        if (script.src) newScript.src = script.src;
                        else newScript.textContent = script.textContent;
                        newScript.async = false;
                        contentArea.appendChild(newScript);
                        script.remove();
                    });

                    updateActiveLink(pageName);
                    console.log(`✅ ${pageName} cargado exitosamente`);

                } catch (error) {
                    console.error(`❌ Error:`, error);
                    contentArea.innerHTML = `
                        <div class="dashboard-card p-8 rounded-lg shadow-lg border-l-4 border-red-500">
                            <div class="flex items-start">
                                <i class='bx bx-error-circle text-4xl text-red-500 mr-4'></i>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-red-700 mb-2">❌ Error al cargar</h2>
                                    <p class="text-red-600 mb-4">${error.message}</p>
                                    <button onclick="loadContent('dashboard')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium">
                                        Volver al Dashboard
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        }
        
        // 🚀 Nueva función para manejar el sidebar en móvil 🚀
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const navContainer = document.getElementById('sidebar-nav-container');

            if (window.innerWidth >= 1024) return; // No hacer nada en desktop

            const isOpen = sidebar.classList.contains('open');

            if (isOpen) {
                sidebar.classList.remove('open');
                overlay.classList.remove('visible');
                document.body.style.overflow = 'auto'; // Permitir scroll
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('visible');
                // Ajustar el scroll del nav container al abrir
                navContainer.scrollTop = 0;
                document.body.style.overflow = 'hidden'; // Evitar scroll de fondo
            }
        }

        document.addEventListener("click", e => {
            const link = e.target.closest(".sidebar-link");
            if (link) {
                e.preventDefault();
                const page = link.getAttribute("data-page");
                loadContent(page);
            }
        });

        // Actualizar datos cada 30 segundos
        async function startAutoRefresh() {
            if (refreshInterval) clearInterval(refreshInterval);
            refreshInterval = setInterval(async () => {
                if (currentUserEmail) {
                    await fetchDashboardData(currentUserEmail);
                    const currentPage = document.querySelector('.sidebar-link.active');
                    if (currentPage) {
                        const pageName = currentPage.getAttribute('data-page');
                        if (pageName === 'dashboard') {
                            renderAllCharts();
                        }
                    }
                }
            }, 30000); // 30 segundos
        }

        (async function() {
            console.log('🔄 Inicializando IA...');
            if (typeof tf !== 'undefined') {
                financialAI = new IslaPredictAI();
                window.financialAI = financialAI;
            }
        })();

        auth.onAuthStateChanged(async function(user) {
            if (!user) {
                window.location.replace('/');
                return;
            }

            console.log("✅ Autenticado:", user.email);
            currentUser = user;
            currentUserEmail = user.email;

            updateSidebarProfile(user);

            const userName = user.displayName || user.email.split('@')[0];
            const userNameEl = document.getElementById('user-name');
            if (userNameEl) userNameEl.textContent = userName;

            // Cargar datos y luego renderizar el dashboard para asegurar la carga inicial de métricas
            await loadContent("dashboard");

            startAutoRefresh();
        });
    </script>
</body>

</html>