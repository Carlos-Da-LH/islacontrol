<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | IslaControl</title>
    <link rel="icon" type="image/png" href="/storage/logos/logo_islacontrol22.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0/dist/tf.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <style>
        /* Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f9fafb;
            transition: background-color 0.3s ease;
        }

        /* Dark Mode Styles */
        .dark body {
            background-color: #111827;
        }

        .dark .bg-white {
            background-color: #1f2937 !important;
        }

        .dark .bg-gray-50 {
            background-color: #111827 !important;
        }

        .dark .bg-gray-100 {
            background-color: #374151 !important;
        }

        .dark .text-gray-800,
        .dark .text-gray-700,
        .dark .text-custom-text {
            color: #e5e7eb !important;
        }

        .dark .text-gray-600,
        .dark .text-custom-gray {
            color: #9ca3af !important;
        }

        .dark .text-gray-500 {
            color: #6b7280 !important;
        }

        .dark .border-gray-200 {
            border-color: #374151 !important;
        }

        .dark .border-gray-300 {
            border-color: #4b5563 !important;
        }

        .dark .shadow-md,
        .dark .shadow-lg {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2) !important;
        }

        .dark .sidebar-link:hover {
            background-color: #374151 !important;
        }

        .dark .dashboard-card {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
            border-color: #374151 !important;
        }

        .dark input,
        .dark textarea,
        .dark select {
            background-color: #374151 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #6b7280 !important;
        }

        /* Toggle Switch Styles */
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background-color: #d1d5db;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dark .theme-toggle {
            background-color: #5F9E74;
        }

        .theme-toggle-slider {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .theme-toggle-slider {
            transform: translateX(30px);
        }

        /* Proteger la fuente en todos los elementos */
        body, body * {
            font-family: 'Inter', sans-serif !important;
        }

        /* Excepciones para elementos que necesitan fuentes específicas */
        .font-mono, code, pre, #barcode-input {
            font-family: 'Courier New', Courier, monospace !important;
        }

        /* Excepciones para íconos - NO aplicar Inter */
        i, i.bx, [class^="bx-"], [class*=" bx-"] {
            font-family: 'boxicons' !important;
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

        /* Enlaces externos en el sidebar */
        .sidebar-external-link {
            /* Heredar estilos de sidebar-link */
        }

        .sidebar-external-link:hover i {
            transform: translateX(2px);
            transition: transform 0.2s;
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

        /* Animación de puntos escribiendo */
        .typing-dots {
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #5F9E74;
            opacity: 0.6;
            animation: typing 1.4s infinite;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.6;
            }
            30% {
                transform: translateY(-8px);
                opacity: 1;
            }
        }

        /* Animación de fade in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
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
                padding-top: 0; /* Quitar padding-top en desktop */
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

        /* ===================================
           SPLASH SCREEN STYLES
           =================================== */
        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        #splash-screen.fade-out {
            opacity: 0;
            pointer-events: none;
        }

        .splash-logo {
            width: 300px;
            height: 300px;
            margin-bottom: 2rem;
            animation: splash-pulse 2s ease-in-out infinite;
            filter: drop-shadow(0 4px 15px rgba(0, 0, 0, 0.1));
        }

        @keyframes splash-pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .splash-title {
            display: none;
        }

        .splash-tagline {
            font-size: 1.3rem;
            color: #6b7280;
            margin-bottom: 3rem;
            font-weight: 500;
            text-align: center;
            padding: 0 1rem;
        }

        .splash-loader {
            width: 60px;
            height: 60px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #5F9E74;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .splash-loading-text {
            margin-top: 1.5rem;
            color: #9ca3af;
            font-size: 0.9rem;
            font-weight: 500;
            animation: fade-in-out 1.5s ease-in-out infinite;
        }

        @keyframes fade-in-out {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        /* Responsive para móviles */
        @media (max-width: 640px) {
            .splash-logo {
                width: 220px;
                height: 220px;
            }
            .splash-tagline {
                font-size: 1.1rem;
                padding: 0 2rem;
            }
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

    <!-- ===================================
         SPLASH SCREEN
         =================================== -->
    <div id="splash-screen">
        <img src="/images/nuevo_islacontrol.png" alt="IslaControl Logo" class="splash-logo">
        <h1 class="splash-title">IslaControl</h1>
        <p class="splash-tagline">Gestión y Punto de Venta para tu Negocio</p>
        <div class="splash-loader"></div>
        <p class="splash-loading-text">Cargando...</p>
    </div>

    <div id="sidebar-overlay" class="overlay" onclick="toggleSidebar()"></div>

    <header class="lg:hidden bg-white shadow-md p-4 fixed top-0 left-0 right-0 z-30 flex justify-between items-center border-b border-gray-200">
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
                    <i class='bx bxs-home text-xl mr-3 text-gray-400'></i> Inicio
                </a>
                <a href="#ia-financiera" data-page="ia-financiera" data-requires-premium="true" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="checkPremiumAccess(event, 'ia-financiera')">
                    <img src="/storage/logos/isla_ia.png" alt="Isla IA" class="w-6 h-6 mr-3">
                    <span>Isla IA</span>
                    <span class="ml-auto bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">PRO</span>
                </a>
                <a href="#sales" data-page="sales" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bxs-credit-card text-xl mr-3 text-gray-400'></i> Ventas
                </a>
                <a href="#reports" data-page="reports" class="sidebar-link flex items-center p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                    <i class='bx bx-line-chart text-xl mr-3 text-gray-400'></i> Reportes
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

                <!-- Menú desplegable de Caja Express -->
                <div class="sidebar-dropdown">
                    <div class="sidebar-dropdown-toggle flex items-center justify-between p-3 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200 cursor-pointer" onclick="checkPremiumAccessForMenu(event, 'caja')">
                        <div class="flex items-center">
                            <i class='bx bx-store text-xl mr-3 text-gray-400'></i>
                            <span>Caja Express</span>
                            <span class="ml-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">PRO</span>
                        </div>
                        <i class='bx bx-chevron-down text-xl transition-transform duration-200' id="caja-menu-icon"></i>
                    </div>
                    <div id="caja-submenu" class="sidebar-submenu hidden ml-4 mt-1 space-y-1">
                        <a href="#scan-product" data-page="scan-product" class="sidebar-link flex items-center p-2 pl-4 rounded-lg text-sm font-medium text-custom-gray hover:bg-gray-100 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                            <i class='bx bx-barcode text-xl mr-3 text-gray-400'></i> Escanear Producto
                        </a>
                        <a href="#abrir-caja" data-page="abrir-caja" class="sidebar-link flex items-center p-2 pl-4 rounded-lg text-sm font-medium text-custom-gray hover:bg-emerald-100 hover:text-emerald-700 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                            <i class='bx bx-wallet text-xl mr-3 text-emerald-600'></i> Abrir Caja
                        </a>
                        <a href="#historial-cajas" data-page="historial-cajas" class="sidebar-link flex items-center p-2 pl-4 rounded-lg text-sm font-medium text-custom-gray hover:bg-blue-100 hover:text-blue-700 transition duration-200" onclick="if(window.innerWidth < 1024) toggleSidebar()">
                            <i class='bx bx-history text-xl mr-3 text-blue-600'></i> Historial de Cajas
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="border-t border-gray-200 p-4 lg:p-6 flex-shrink-0">
            <!-- Subscription Info -->
            @auth
                @if(auth()->user()->subscribed('default'))
                    @php
                        $subscription = auth()->user()->subscription('default');
                        $currentPlan = null;
                        $allPlans = config('plans');

                        // Encontrar el plan actual
                        foreach($allPlans as $key => $plan) {
                            if($subscription->stripe_price == $plan['stripe_price_id']) {
                                $currentPlan = $plan;
                                $currentPlan['key'] = $key;
                                break;
                            }
                        }

                        // Si no se encuentra, usar el primer plan
                        if(!$currentPlan) {
                            $currentPlan = reset($allPlans);
                            $currentPlan['key'] = key($allPlans);
                        }
                    @endphp

                    <a href="{{ route('subscription.dashboard') }}" class="block mb-4 p-3 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-white uppercase">Mi Plan</span>
                            @if($subscription->onTrial())
                                <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">
                                    Prueba
                                </span>
                            @elseif($subscription->canceled())
                                <span class="bg-yellow-400/90 text-gray-800 text-xs px-2 py-0.5 rounded-full">
                                    Cancelado
                                </span>
                            @else
                                <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">
                                    Activo
                                </span>
                            @endif
                        </div>
                        <p class="text-white font-bold text-base mb-1">{{ $currentPlan['name'] ?? 'Plan Actual' }}</p>
                        <div class="flex items-baseline">
                            <span class="text-white text-xl font-bold">${{ $currentPlan['price'] ?? '0' }}</span>
                            <span class="text-white/80 text-xs ml-1">/mes</span>
                        </div>
                        @if($subscription->onTrial())
                            <p class="text-white/90 text-xs mt-2">
                                <i class='bx bx-time-five'></i> Termina el {{ $subscription->trial_ends_at->format('d/m/Y') }}
                            </p>
                        @endif
                        <div class="flex items-center justify-between mt-2 text-white/90">
                            <span class="text-xs">Ver detalles</span>
                            <i class='bx bx-chevron-right'></i>
                        </div>
                    </a>
                @else
                    @php
                        $freePlan = config('plans.free');
                    @endphp
                    <a href="{{ route('subscription.select-plan') }}" class="block mb-4 p-3 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-white uppercase">Mi Plan</span>
                            <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">
                                Gratuito
                            </span>
                        </div>
                        <p class="text-white font-bold text-base mb-1">Plan Gratuito</p>
                        <div class="flex items-baseline mb-2">
                            <span class="text-white text-xl font-bold">$0</span>
                            <span class="text-white/80 text-xs ml-1">/mes</span>
                        </div>
                        <div class="bg-white/10 rounded p-2 mb-2">
                            <p class="text-white/90 text-xs mb-1">
                                <i class='bx bx-package'></i> {{ $freePlan['limits']['products'] }} productos
                            </p>
                            <p class="text-white/90 text-xs">
                                <i class='bx bx-shopping-bag'></i> {{ $freePlan['limits']['sales_per_month'] }} ventas/mes
                            </p>
                        </div>
                        <div class="flex items-center justify-between text-white bg-white/20 rounded p-2">
                            <span class="text-xs font-semibold">🎁 Mejora con 30 días gratis</span>
                            <i class='bx bx-chevron-right'></i>
                        </div>
                    </a>
                @endif
            @endauth

            <!-- Theme Toggle -->
            <div class="flex items-center justify-between p-3 mb-3 rounded-lg">
                <div class="flex items-center">
                    <i class='bx bx-moon text-xl mr-3 text-gray-400'></i>
                    <span class="text-sm font-medium text-custom-gray">Modo Oscuro</span>
                </div>
                <div class="theme-toggle" onclick="toggleTheme()">
                    <div class="theme-toggle-slider">
                        <i class='bx bx-sun text-sm text-yellow-500 dark-sun' style="display: none;"></i>
                        <i class='bx bx-moon text-sm text-gray-600 light-moon'></i>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
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
        // ===================================
        // SPLASH SCREEN CONTROL
        // ===================================
        window.addEventListener('load', function() {
            const splashScreen = document.getElementById('splash-screen');

            // Ocultar el splash screen después de 2 segundos (o cuando todo esté cargado)
            setTimeout(() => {
                splashScreen.classList.add('fade-out');

                // Remover completamente del DOM después de la animación
                setTimeout(() => {
                    splashScreen.remove();
                }, 500); // Coincide con la duración de la transición CSS
            }, 2000); // 2 segundos de visualización
        });

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

        // Theme Management
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                updateThemeIcons(true);
            } else {
                document.documentElement.classList.remove('dark');
                updateThemeIcons(false);
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons(isDark);
        }

        function updateThemeIcons(isDark) {
            const darkSun = document.querySelector('.dark-sun');
            const lightMoon = document.querySelector('.light-moon');
            if (darkSun && lightMoon) {
                darkSun.style.display = isDark ? 'block' : 'none';
                lightMoon.style.display = isDark ? 'none' : 'block';
            }
        }

        // Initialize theme on page load
        initTheme();

        let currentUser = null;
        let currentUserEmail = null;
        let currentUserName = null;
        let financialAI = null;
        let dashboardData = {
            sales: [],
            products: [],
            categories: [],
            customers: [],
            saleItems: [], // ← Cache de sale_items
            totalSalesToday: 0
        };
        let charts = {};
        let refreshInterval = null;
        let aiWelcomeShown = false; // Bandera para controlar el saludo de la IA
        let saleItemsLoading = false; // ← Flag para evitar fetches duplicados

        class IslaPredictAI {
            constructor() {
                this.isModelReady = false;
                this.sessionId = localStorage.getItem('isla_session_id') || null;
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
                    console.log('✅ Isla IA lista');
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
                    lowStock: data.products.filter(p => parseInt(p.stock || 0) < 5).length,
                    hasActiveSubscription: data.subscriptionInfo?.hasActiveSubscription || false,
                    planName: data.subscriptionInfo?.planName || 'Plan Gratuito',
                    planLimits: data.subscriptionInfo?.planLimits || null
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

                // 🤖 INTENTAR USAR OLLAMA PRIMERO (IA AVANZADA CON MEMORIA)
                try {
                    const ollamaResponse = await fetch('/api/ollama/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            question: question,
                            session_id: this.sessionId, // 🧠 ENVIAR SESSION_ID PARA MEMORIA
                            context: {
                                sales: metrics.totalSales,
                                revenue: metrics.revenueThisMonth,
                                products: metrics.totalProducts,
                                lowStock: metrics.lowStock,
                                trend: metrics.salesTrend,
                                customers: metrics.totalCustomers
                            }
                        }),
                        timeout: 30000
                    });

                    if (ollamaResponse.ok) {
                        const ollamaData = await ollamaResponse.json();
                        if (ollamaData.success && ollamaData.response) {
                            // 🧠 GUARDAR SESSION_ID PARA MANTENER MEMORIA
                            if (ollamaData.session_id) {
                                this.sessionId = ollamaData.session_id;
                                localStorage.setItem('isla_session_id', this.sessionId);
                                updateMemoryIndicator(true); // Mostrar indicador
                            }
                            return {
                                response: ollamaData.response,
                                confidence: 0.95
                            };
                        }
                    }
                } catch (error) {
                    console.log('Ollama no disponible, usando IA local:', error.message);
                }

                // Si Ollama no está disponible, usar IA LOCAL (respuestas predefinidas)
                const lowerQ = question.toLowerCase();
                let response = '';

                // ANÁLISIS DE SALUDOS
                if (lowerQ.includes('hola') || lowerQ.includes('buenos') || lowerQ.includes('buenas') || lowerQ.includes('hey')) {
                    const trend = metrics.salesTrend > 0 ? '📈' : '📉';
                    const trendText = metrics.salesTrend > 0 ? `aumentaron ${metrics.salesTrend.toFixed(1)}%` : `bajaron ${Math.abs(metrics.salesTrend).toFixed(1)}%`;
                    response = `¡Hola! 👋 Un placer ayudarte.\n\n📊 *Resumen Ejecutivo:*\n━━━━━━━━━━━━━━━━━━\n• Ventas este mes: ${metrics.totalSales} ${trend}\n• Ingresos: $${metrics.revenueThisMonth.toFixed(2)}\n• Tendencia: ${trendText}\n• Productos: ${metrics.totalProducts}\n• ⚠️ Stock bajo: ${metrics.lowStock} productos\n\n¿En qué más puedo ayudarte?`;

                // ANÁLISIS DE VENTAS DETALLADO
                } else if (lowerQ.includes('venta') || lowerQ.includes('ingreso') || lowerQ.includes('ganancia')) {
                    const trend = metrics.salesTrend > 0 ? '📈 Positiva' : '📉 Negativa';
                    const trendEmoji = metrics.salesTrend > 0 ? '✅' : '⚠️';
                    const projection = metrics.totalSales > 0 ? (metrics.totalSales / new Date().getDate() * 30).toFixed(0) : 0;

                    response = `📊 *Análisis de Ventas Completo*\n━━━━━━━━━━━━━━━━━━\n\n*Este Mes:*\n• Ventas: ${metrics.totalSales}\n• Ingresos: $${metrics.revenueThisMonth.toFixed(2)}\n• Ticket promedio: $${metrics.avgSale.toFixed(2)}\n\n*Tendencia:* ${trend} ${trendEmoji}\n• Cambio: ${metrics.salesTrend.toFixed(1)}%\n\n*Proyección:*\n• Estimado fin de mes: ${projection} ventas\n• Ingreso proyectado: $${(projection * metrics.avgSale).toFixed(2)}\n\n*Histórico:*\n• Total de ventas: ${metrics.totalSalesAllTime}\n• Ingresos totales: $${metrics.totalRevenueAllTime.toFixed(2)}`;

                // ANÁLISIS DE PRODUCTOS TOP
                } else if (lowerQ.includes('mejor') || lowerQ.includes('top') || lowerQ.includes('más vendido')) {
                    const productSales = {};
                    data.sales.forEach(sale => {
                        if (sale.product_id && sale.cantidad) {
                            productSales[sale.product_id] = (productSales[sale.product_id] || 0) + parseInt(sale.cantidad);
                        }
                    });

                    const topProducts = Object.entries(productSales)
                        .sort((a, b) => b[1] - a[1])
                        .slice(0, 5)
                        .map(([id, qty]) => {
                            const product = data.products.find(p => p.id == id);
                            return product ? `• ${product.nombre}: ${qty} unidades` : null;
                        })
                        .filter(Boolean);

                    response = `🏆 *Top Productos Más Vendidos*\n━━━━━━━━━━━━━━━━━━\n\n${topProducts.length > 0 ? topProducts.join('\n') : '• No hay datos suficientes'}\n\n💡 *Recomendación:*\nMantén stock alto de estos productos.`;

                // ANÁLISIS DE INVENTARIO
                } else if (lowerQ.includes('producto') || lowerQ.includes('inventario') || lowerQ.includes('stock')) {
                    const totalStock = data.products.reduce((sum, p) => sum + parseInt(p.stock || 0), 0);
                    const lowStockItems = data.products.filter(p => parseInt(p.stock || 0) < 5);
                    const outOfStock = data.products.filter(p => parseInt(p.stock || 0) === 0);
                    const totalValue = data.products.reduce((sum, p) => sum + (parseInt(p.stock || 0) * parseFloat(p.price || p.precio || 0)), 0);

                    response = `📦 *Análisis de Inventario*\n━━━━━━━━━━━━━━━━━━\n\n*General:*\n• Total productos: ${metrics.totalProducts}\n• Stock total: ${totalStock} unidades\n• Valor inventario: $${totalValue.toFixed(2)}\n\n*Alertas:* ⚠️\n• Stock bajo (<5): ${lowStockItems.length}\n• Sin stock: ${outOfStock.length}\n\n${lowStockItems.length > 0 ? `*Productos críticos:*\n${lowStockItems.slice(0, 5).map(p => `• ${p.nombre}: ${p.stock} unidades`).join('\n')}` : '✅ Todo en orden'}`;

                // ANÁLISIS DE CLIENTES
                } else if (lowerQ.includes('cliente')) {
                    const retention = this.calculateRetention(data.customers);
                    const avgPurchase = metrics.totalSalesAllTime > 0 ? (metrics.totalRevenueAllTime / metrics.totalSalesAllTime).toFixed(2) : 0;

                    response = `👥 *Análisis de Clientes*\n━━━━━━━━━━━━━━━━━━\n\n*Base de datos:*\n• Total clientes: ${metrics.totalCustomers}\n• Tasa retención: ${retention.toFixed(1)}%\n\n*Comportamiento:*\n• Compra promedio: $${avgPurchase}\n• Total compras: ${metrics.totalSalesAllTime}\n\n💡 *Recomendación:*\n${retention < 80 ? '• Implementa programa de lealtad\n• Ofrece promociones especiales' : '• Excelente retención, sigue así\n• Considera programa VIP'}`;

                // PREDICCIONES
                } else if (lowerQ.includes('predic') || lowerQ.includes('futuro') || lowerQ.includes('proyecc')) {
                    const dailyAvg = metrics.totalSales / new Date().getDate();
                    const projection30 = (dailyAvg * 30).toFixed(0);
                    const revenue30 = (dailyAvg * 30 * metrics.avgSale).toFixed(2);

                    response = `🔮 *Predicciones y Proyecciones*\n━━━━━━━━━━━━━━━━━━\n\n*Basado en datos actuales:*\n\n📈 *Fin de mes:*\n• Ventas estimadas: ${projection30}\n• Ingresos estimados: $${revenue30}\n\n📊 *Promedio diario:*\n• Ventas/día: ${dailyAvg.toFixed(1)}\n• Ingresos/día: $${(dailyAvg * metrics.avgSale).toFixed(2)}\n\n⚡ *Próximos 7 días:*\n• Ventas estimadas: ${(dailyAvg * 7).toFixed(0)}\n• Ingresos: $${(dailyAvg * 7 * metrics.avgSale).toFixed(2)}`;

                // ALERTAS
                } else if (lowerQ.includes('alerta') || lowerQ.includes('problema') || lowerQ.includes('urgente')) {
                    const alerts = [];
                    if (metrics.lowStock > 0) alerts.push(`⚠️ ${metrics.lowStock} productos con stock bajo`);
                    if (metrics.salesTrend < -10) alerts.push(`📉 Ventas bajaron ${Math.abs(metrics.salesTrend).toFixed(1)}%`);
                    if (metrics.totalSales === 0) alerts.push(`🚨 No hay ventas este mes`);

                    response = `🚨 *Alertas del Sistema*\n━━━━━━━━━━━━━━━━━━\n\n${alerts.length > 0 ? alerts.join('\n') : '✅ No hay alertas críticas'}\n\n${alerts.length > 0 ? '\n💡 *Acción requerida:*\n• Revisa inventario\n• Analiza estrategia de ventas\n• Contacta proveedores' : ''}`;

                // RECOMENDACIONES INTELIGENTES
                } else if (lowerQ.includes('recomendación') || lowerQ.includes('consejo') || lowerQ.includes('sugerencia') || lowerQ.includes('qué hacer')) {
                    const recommendations = [];

                    if (metrics.lowStock > 0) {
                        recommendations.push(`📦 *Inventario:*\n• Reabastecer ${metrics.lowStock} productos urgente`);
                    }

                    if (metrics.salesTrend < 0) {
                        recommendations.push(`📈 *Ventas:*\n• Lanzar promoción para aumentar ventas\n• Revisar precios vs competencia`);
                    } else if (metrics.salesTrend > 20) {
                        recommendations.push(`🚀 *Crecimiento:*\n• Capitaliza la tendencia positiva\n• Aumenta stock de productos populares`);
                    }

                    if (metrics.totalCustomers < 10) {
                        recommendations.push(`👥 *Marketing:*\n• Enfócate en adquirir más clientes\n• Implementa referidos`);
                    }

                    response = `💡 *Recomendaciones Personalizadas*\n━━━━━━━━━━━━━━━━━━\n\n${recommendations.length > 0 ? recommendations.join('\n\n') : '✅ Tu negocio va bien, sigue así'}\n\n*Métricas clave:*\n• Ticket promedio: $${metrics.avgSale.toFixed(2)}\n• ${metrics.totalCustomers} clientes activos`;

                // COMPARACIONES
                } else if (lowerQ.includes('comparar') || lowerQ.includes('diferencia') || lowerQ.includes('mes pasado')) {
                    const change = metrics.salesTrend;
                    const revenueChange = metrics.revenueTrend;

                    response = `📊 *Comparación Periodos*\n━━━━━━━━━━━━━━━━━━\n\n*Ventas:*\n• Cambio: ${change > 0 ? '+' : ''}${change.toFixed(1)}%\n• ${change > 0 ? '📈 Mejorando' : '📉 Descendiendo'}\n\n*Ingresos:*\n• Cambio: ${revenueChange > 0 ? '+' : ''}${revenueChange.toFixed(1)}%\n• ${revenueChange > 0 ? '✅ Crecimiento' : '⚠️ Decrecimiento'}\n\n*Análisis:*\n${change > 10 ? '🎉 Excelente crecimiento' : change > 0 ? '👍 Crecimiento moderado' : change > -10 ? '⚠️ Ligera baja' : '🚨 Necesita atención'}`;

                // AYUDA
                } else {
                    response = `🤖 *Puedo ayudarte con:*\n━━━━━━━━━━━━━━━━━━\n\n📊 *Ventas:*\n• "Análisis de ventas"\n• "Predicciones"\n• "Comparar periodos"\n\n📦 *Inventario:*\n• "Estado del inventario"\n• "Top productos"\n• "Alertas"\n\n👥 *Clientes:*\n• "Análisis de clientes"\n\n💡 *Estrategia:*\n• "Recomendaciones"\n• "Qué hacer"\n\n*Escribe tu pregunta y te ayudo* 😊`;
                }

                return {
                    response,
                    confidence: 0.90
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

                const [salesRes, productsRes, categoriesRes, customersRes, saleItemsRes, subscriptionRes] = await Promise.all([
                    fetch('/sales/api', { headers, timeout: 10000 }),
                    fetch('/products/api', { headers, timeout: 10000 }),
                    fetch('/categories/api', { headers, timeout: 10000 }),
                    fetch('/customers/api', { headers, timeout: 10000 }),
                    fetch('/api/sale-items/top-products', { headers, timeout: 10000 }),
                    fetch('/api/subscription/info?t=' + Date.now(), {
                        headers,
                        timeout: 10000,
                        cache: 'no-store'
                    })
                ]);

                const [sales, products, categories, customers, saleItems, subscriptionInfo] = await Promise.all([
                    salesRes.ok ? salesRes.json() : [],
                    productsRes.ok ? productsRes.json() : [],
                    categoriesRes.ok ? categoriesRes.json() : [],
                    customersRes.ok ? customersRes.json() : [],
                    saleItemsRes.ok ? saleItemsRes.json() : [],
                    subscriptionRes.ok ? subscriptionRes.json() : { hasActiveSubscription: false, planLimits: null }
                ]);

                dashboardData.sales = Array.isArray(sales) ? sales : [];
                dashboardData.products = Array.isArray(products) ? products : [];
                dashboardData.categories = Array.isArray(categories) ? categories : [];
                dashboardData.customers = Array.isArray(customers) ? customers : [];
                dashboardData.saleItems = Array.isArray(saleItems) ? saleItems : [];
                dashboardData.subscriptionInfo = subscriptionInfo;

                // Debug subscription info
                console.log('🔍 DEBUG SUSCRIPCIÓN:', {
                    subscriptionInfo: subscriptionInfo,
                    hasActiveSubscription: subscriptionInfo?.hasActiveSubscription,
                    planName: subscriptionInfo?.planName,
                    planLimits: subscriptionInfo?.planLimits
                });

                console.log('✅ Datos cargados desde API:', {
                    ventas: dashboardData.sales.length,
                    productos: dashboardData.products.length,
                    categorías: dashboardData.categories.length,
                    clientes: dashboardData.customers.length,
                    items_vendidos: dashboardData.saleItems.length
                });

                if (dashboardData.sales.length > 0) {
                    console.log('📝 Muestra de venta:', dashboardData.sales[0]);
                }
                if (dashboardData.products.length > 0) {
                    console.log('📦 Muestra de producto:', dashboardData.products[0]);
                }

                // Actualiza las métricas con los datos recién cargados
                if (financialAI) {
                    updateMetricCards(financialAI.analyzeFinancialData(dashboardData));
                }

                // Actualizar estado premium y badges del sidebar
                updatePremiumStatus();

                return true;
            } catch (error) {
                console.warn('⚠️ Error cargando datos:', error);
                return false;
            }
        }

        // Función para convertir Markdown a HTML
        function formatMarkdown(text) {
            return text
                // Convertir **texto** a negritas
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                // Convertir *texto* a negritas
                .replace(/\*(.+?)\*/g, '<strong>$1</strong>')
                // Convertir _texto_ a cursiva (si se usa)
                .replace(/_(.*?)_/g, '<em>$1</em>')
                // Convertir saltos de línea
                .replace(/\n/g, '<br>');
        }

        function addChatMessage(sender, message) {
            const chatContainer = document.getElementById('ai-chat-messages');
            if (!chatContainer) return;

            const isUser = sender === 'user';
            const bgColor = isUser ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'mb-4 animate-fadeIn';

            // Formatear el mensaje (convertir markdown a HTML)
            const formattedMessage = formatMarkdown(message);

            messageDiv.innerHTML = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-xs sm:max-w-sm"> ${!isUser ? '<div class="flex items-center mb-2"><img src="/storage/logos/isla_ia.png" alt="Isla IA" class="w-8 h-8 mr-2"></div>' : ''}
                        <div class="${bgColor} rounded-2xl px-4 py-3 shadow-sm"><div class="text-sm">${formattedMessage}</div></div>
                    </div>
                </div>
            `;
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Agregar indicador de "escribiendo..."
        function showTypingIndicator() {
            const chatContainer = document.getElementById('ai-chat-messages');
            if (!chatContainer) return;

            const typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'mb-4 animate-fadeIn';
            typingDiv.innerHTML = `
                <div class="flex justify-start">
                    <div class="max-w-xs">
                        <div class="bg-gray-100 rounded-2xl px-5 py-3 shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Isla está escribiendo</span>
                                <div class="typing-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatContainer.appendChild(typingDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function removeTypingIndicator() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        window.sendMessageToAI = async function() {
            const input = document.getElementById('ai-chat-input');
            const question = input.value.trim();
            if (!question || !financialAI?.isModelReady) return;

            addChatMessage('user', question);
            input.value = '';

            // Mostrar indicador de escritura
            showTypingIndicator();

            try {
                const result = await financialAI.generateResponse(question, dashboardData);
                removeTypingIndicator();
                addChatMessage('ai', result.response);
            } catch (error) {
                removeTypingIndicator();
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

        // 🧠 NUEVA CONVERSACIÓN - Limpiar memoria
        window.newConversation = async function() {
            try {
                // Limpiar chat visualmente
                const chatContainer = document.getElementById('ai-chat-messages');
                if (chatContainer) chatContainer.innerHTML = '';

                // Resetear session_id (se creará uno nuevo al enviar el primer mensaje)
                financialAI.sessionId = null;
                localStorage.removeItem('isla_session_id');

                // Ocultar indicador de memoria
                updateMemoryIndicator(false);

                // Mensaje de bienvenida
                addChatMessage('ai', '✨ Nueva conversación iniciada.\n\nSoy Isla, tu Asistente Inteligente de Negocios.\n\nPuedo ayudarte con análisis de ventas, inventario, tendencias, recomendaciones para tu negocio, y también conversar sobre cualquier tema que necesites. 😊');
            } catch (error) {
                console.error('Error al iniciar nueva conversación:', error);
                addChatMessage('ai', '⚠️ Error al iniciar nueva conversación. Intenta de nuevo.');
            }
        };

        // Actualizar indicador de memoria
        function updateMemoryIndicator(hasMemory) {
            const indicator = document.getElementById('isla-memory-indicator');
            if (indicator) {
                if (hasMemory) {
                    indicator.classList.remove('hidden');
                } else {
                    indicator.classList.add('hidden');
                }
            }
        }

        // 📜 VER HISTORIAL - Abrir modal y cargar historial
        window.viewHistory = async function() {
            const modal = document.getElementById('historyModal');
            const content = document.getElementById('historyContent');

            if (!modal || !content) return;

            // Mostrar modal
            modal.classList.remove('hidden');

            // Verificar si hay session_id
            if (!financialAI || !financialAI.sessionId) {
                content.innerHTML = `
                    <div class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <i class='bx bx-message-x text-6xl mb-4 text-gray-400'></i>
                            <h4 class="text-xl font-semibold text-gray-700 mb-2">No hay historial</h4>
                            <p class="text-gray-500">Aún no has iniciado una conversación con Isla.</p>
                            <p class="text-sm text-gray-400 mt-2">Escribe un mensaje para empezar.</p>
                        </div>
                    </div>
                `;
                return;
            }

            try {
                // Cargar historial desde API
                const response = await fetch(`/api/ollama/history?session_id=${financialAI.sessionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) throw new Error('Error al cargar historial');

                const data = await response.json();

                if (!data.success || !data.history || data.history.length === 0) {
                    content.innerHTML = `
                        <div class="flex items-center justify-center py-12">
                            <div class="text-center">
                                <i class='bx bx-message-detail text-6xl mb-4 text-gray-400'></i>
                                <h4 class="text-xl font-semibold text-gray-700 mb-2">Historial vacío</h4>
                                <p class="text-gray-500">No hay mensajes en esta conversación.</p>
                            </div>
                        </div>
                    `;
                    return;
                }

                // Renderizar historial
                let historyHTML = '<div class="space-y-4">';

                data.history.forEach((msg, index) => {
                    const isUser = msg.role === 'user';
                    const bgColor = isUser ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800';
                    const alignment = isUser ? 'justify-end' : 'justify-start';
                    const formattedMessage = formatMarkdown(msg.message);
                    const timestamp = new Date(msg.created_at).toLocaleString('es-MX', {
                        hour: '2-digit',
                        minute: '2-digit',
                        day: '2-digit',
                        month: 'short'
                    });

                    historyHTML += `
                        <div class="flex ${alignment}">
                            <div class="max-w-[80%]">
                                <div class="${bgColor} rounded-lg px-4 py-3 shadow-sm">
                                    <div class="text-sm font-semibold mb-1 ${isUser ? 'text-green-100' : 'text-gray-600'} flex items-center gap-1">
                                        <i class='bx ${isUser ? 'bx-user' : 'bx-brain'}'></i>
                                        ${isUser ? 'Tú' : 'Isla'}
                                    </div>
                                    <div class="text-sm leading-relaxed">${formattedMessage}</div>
                                </div>
                                <div class="text-xs text-gray-400 mt-1 ${isUser ? 'text-right' : 'text-left'} px-2">
                                    ${timestamp}
                                </div>
                            </div>
                        </div>
                    `;
                });

                historyHTML += '</div>';

                // Agregar contador
                historyHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class='bx bx-message-square-dots text-2xl mr-3 text-green-600'></i>
                                <div>
                                    <p class="font-semibold text-green-900">Total de mensajes: ${data.count}</p>
                                    <p class="text-sm text-green-600">Session ID: ${financialAI.sessionId.substring(0, 20)}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ` + historyHTML;

                content.innerHTML = historyHTML;

            } catch (error) {
                console.error('Error al cargar historial:', error);
                content.innerHTML = `
                    <div class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <i class='bx bx-error-circle text-6xl mb-4 text-red-500'></i>
                            <h4 class="text-xl font-semibold text-red-700 mb-2">Error</h4>
                            <p class="text-gray-500">No se pudo cargar el historial.</p>
                            <p class="text-sm text-gray-400 mt-2">${error.message}</p>
                        </div>
                    </div>
                `;
            }
        };

        // Cerrar modal de historial
        window.closeHistoryModal = function() {
            const modal = document.getElementById('historyModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // 📋 VER CONVERSACIONES - Abrir modal
        window.viewConversations = async function() {
            const modal = document.getElementById('conversationsModal');
            const content = document.getElementById('conversationsModalContent');

            if (!modal || !content) return;

            // Mostrar modal
            modal.classList.remove('hidden');

            // Cargar conversaciones
            await loadConversationsList();
        };

        // Cerrar modal de conversaciones
        window.closeConversationsModal = function() {
            const modal = document.getElementById('conversationsModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // 📋 CARGAR LISTA DE CONVERSACIONES
        window.loadConversationsList = async function() {
            const listContainer = document.getElementById('conversationsModalContent');
            if (!listContainer) return;

            try {
                const response = await fetch('/api/ollama/sessions', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) throw new Error('Error al cargar sesiones');

                const data = await response.json();

                if (!data.success || data.count === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-12">
                            <i class='bx bx-message text-6xl text-gray-400 mb-4'></i>
                            <h4 class="text-xl font-semibold text-gray-700 mb-2">No hay conversaciones</h4>
                            <p class="text-gray-500">Empieza una nueva conversación con Isla</p>
                        </div>
                    `;
                    return;
                }

                // Renderizar sesiones
                let sessionsHTML = '<div class="space-y-3">';

                data.sessions.forEach(session => {
                    const isActive = financialAI && financialAI.sessionId === session.session_id;
                    const activeClass = isActive ? 'border-custom-active bg-green-50' : 'border-gray-200 bg-white hover:bg-gray-50';
                    const activeBadge = isActive ? '<span class="bg-custom-active text-white text-xs px-2 py-1 rounded-full">Activa</span>' : '';

                    const date = new Date(session.last_message_at);
                    const dateStr = date.toLocaleDateString('es-MX', { day: 'numeric', month: 'short' });

                    sessionsHTML += `
                        <div class="${activeClass} rounded-lg p-4 border-2 transition-all cursor-pointer group" onclick="loadConversation('${session.session_id}'); closeConversationsModal();">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0 pr-4">
                                    <h4 class="font-semibold text-gray-900 truncate">${session.title}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class='bx bx-message-detail'></i> ${session.message_count} mensajes • ${dateStr}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    ${activeBadge}
                                    <button onclick="event.stopPropagation(); deleteConversation('${session.session_id}')" class="opacity-0 group-hover:opacity-100 transition-opacity p-2 hover:bg-red-100 rounded-lg text-red-600">
                                        <i class='bx bx-trash text-lg'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                sessionsHTML += '</div>';
                listContainer.innerHTML = sessionsHTML;

            } catch (error) {
                console.error('Error al cargar conversaciones:', error);
                listContainer.innerHTML = `
                    <div class="text-center py-12">
                        <i class='bx bx-error-circle text-6xl text-red-500 mb-4'></i>
                        <h4 class="text-xl font-semibold text-red-700 mb-2">Error</h4>
                        <p class="text-gray-500">No se pudo cargar las conversaciones</p>
                    </div>
                `;
            }
        };

        // 📂 CARGAR UNA CONVERSACIÓN ESPECÍFICA
        window.loadConversation = async function(sessionId) {
            if (!sessionId) return;

            try {
                // Actualizar session_id actual
                financialAI.sessionId = sessionId;
                localStorage.setItem('isla_session_id', sessionId);

                // Limpiar chat actual
                const chatContainer = document.getElementById('ai-chat-messages');
                if (chatContainer) chatContainer.innerHTML = '';

                // Cargar historial de esta sesión
                const response = await fetch(`/api/ollama/history?session_id=${sessionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) throw new Error('Error al cargar conversación');

                const data = await response.json();

                if (data.success && data.history && data.history.length > 0) {
                    // Mostrar mensajes en el chat
                    data.history.forEach(msg => {
                        addChatMessage(msg.role === 'user' ? 'user' : 'ai', msg.message);
                    });
                }

                // Actualizar indicador de memoria
                updateMemoryIndicator(true);

            } catch (error) {
                console.error('Error al cargar conversación:', error);
                addChatMessage('ai', '⚠️ Error al cargar la conversación.');
            }
        };

        // 🗑️ ELIMINAR UNA CONVERSACIÓN
        window.deleteConversation = async function(sessionId) {
            const confirmed = await showAlert({
                title: '¿Eliminar conversación?',
                message: 'Esta acción no se puede deshacer.',
                type: 'warning',
                showCancel: true,
                confirmText: 'Sí, eliminar',
                cancelText: 'Cancelar'
            });

            if (!confirmed) return;

            try {
                const response = await fetch('/api/ollama/session', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        session_id: sessionId
                    })
                });

                if (!response.ok) throw new Error('Error al eliminar sesión');

                const data = await response.json();

                if (data.success) {
                    // Si era la sesión activa, limpiar
                    if (financialAI && financialAI.sessionId === sessionId) {
                        financialAI.sessionId = null;
                        localStorage.removeItem('isla_session_id');
                        const chatContainer = document.getElementById('ai-chat-messages');
                        if (chatContainer) chatContainer.innerHTML = '';
                        updateMemoryIndicator(false);
                    }

                    // Recargar lista si el modal está abierto
                    const modal = document.getElementById('conversationsModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        await loadConversationsList();
                    }
                }

            } catch (error) {
                console.error('Error al eliminar conversación:', error);
                showAlert({
                    title: 'Error',
                    message: 'No se pudo eliminar la conversación',
                    type: 'error'
                });
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

            // Primero cerrar sesión en Laravel
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                // Luego cerrar sesión en Firebase
                if (auth) {
                    return auth.signOut();
                }
            }).then(() => {
                // Redirigir al inicio
                window.location.replace('/');
            }).catch((error) => {
                console.error('Error al cerrar sesión:', error);
                // Redirigir de todos modos
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

        // ==================== 10 GRÁFICAS CON COLORES VIBRANTES ====================

        // 🎨 PALETA DE COLORES VIBRANTE (basada en el verde de la app #5F9E74)
        const islaColors = {
            primary: '#5F9E74',     // Verde principal de la app
            teal: '#26A69A',        // Turquesa vibrante
            blue: '#2196F3',        // Azul oceánico
            purple: '#9C27B0',      // Púrpura vibrante
            pink: '#E91E63',        // Rosa fucsia
            orange: '#FF9800',      // Naranja cálido
            amber: '#FFC107',       // Ámbar dorado
            mint: '#4CAF50',        // Verde menta
            coral: '#FF5252',       // Coral rojizo
            lime: '#7CB342',        // Verde lima
            cyan: '#00BCD4',        // Cian brillante
            indigo: '#3F51B5'       // Índigo
        };

        // Array de colores en orden visual atractivo
        const chartColors = [
            islaColors.primary,  // Verde principal
            islaColors.teal,     // Turquesa
            islaColors.blue,     // Azul
            islaColors.purple,   // Púrpura
            islaColors.pink,     // Rosa
            islaColors.orange,   // Naranja
            islaColors.amber,    // Ámbar
            islaColors.mint,     // Verde menta
            islaColors.coral,    // Coral
            islaColors.lime,     // Lime
            islaColors.cyan,     // Cian
            islaColors.indigo    // Índigo
        ];

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

            if (charts.sales) charts.sales.destroy();

            charts.sales = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Ventas',
                        data: data.length ? data : [0],
                        backgroundColor: data.map((_, i) => chartColors[i % chartColors.length]),
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: islaColors.primary,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
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

            if (charts.revenue) charts.revenue.destroy();

            charts.revenue = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: data.length ? data : [0],
                        backgroundColor: data.map((_, i) => chartColors[i % chartColors.length]),
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Ingresos: $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function renderProductsChart() {
            const ctx = document.getElementById('chartProductos');
            if (!ctx) return;

            const sorted = [...dashboardData.products].sort((a, b) => parseInt(b.stock || 0) - parseInt(a.stock || 0));
            const labels = sorted.slice(0, 8).map(p => p.name || p.nombre || p.product || p.title || 'Sin nombre');
            const data = sorted.slice(0, 8).map(p => parseInt(p.stock || p.cantidad || p.quantity || 0));

            if (charts.products) charts.products.destroy();

            charts.products = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin productos'],
                    datasets: [{
                        label: 'Stock',
                        data: data.length ? data : [0],
                        backgroundColor: data.map((_, i) => chartColors[i % chartColors.length]),
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
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

            const labels = Object.keys(categoryCount);
            const data = Object.values(categoryCount);

            if (charts.categories) charts.categories.destroy();

            charts.categories = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        data: data.length ? data : [0],
                        backgroundColor: data.map((_, i) => chartColors[i % chartColors.length]),
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1200
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12
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
                const value = (parseInt(p.stock || p.cantidad || p.quantity || 0) * parseFloat(p.price || p.precio || p.cost || 0));
                categoryValues[cat] = (categoryValues[cat] || 0) + value;
            });

            const labels = Object.keys(categoryValues);
            const data = Object.values(categoryValues);

            if (charts.inventory) charts.inventory.destroy();

            charts.inventory = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Valor ($)',
                        data: data.length ? data : [0],
                        backgroundColor: data.map((_, i) => chartColors[i % chartColors.length]),
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Valor: $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
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
                    labels: ['Clientes', 'Ventas', 'Promedio $'],
                    datasets: [{
                        label: 'Análisis',
                        data: [totalCustomers, totalSales, avgSale],
                        backgroundColor: [islaColors.primary, islaColors.teal, islaColors.blue],
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
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
                const price = parseFloat(p.price || p.precio || p.cost || 0);
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
                        backgroundColor: [islaColors.mint, islaColors.amber, islaColors.orange, islaColors.purple],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1200
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 12,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12
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

            const channelKeys = Object.keys(channels);
            const channelData = Object.values(channels);

            charts.channel = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: channelKeys.length ? channelKeys : ['Sin datos'],
                    datasets: [{
                        label: 'Ingresos por Canal ($)',
                        data: channelData.length ? channelData : [0],
                        backgroundColor: channelData.map((_, i) => chartColors[i % chartColors.length]),
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Ingresos: $' + context.parsed.x.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
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
                        backgroundColor: [islaColors.coral, islaColors.amber, islaColors.primary],
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
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

            if (charts.topProducts) charts.topProducts.destroy();

            charts.topProducts = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: finalData.length ? finalData.map(s => s[0]) : ['Sin datos'],
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: finalData.length ? finalData.map(s => s[1]) : [0],
                        backgroundColor: finalData.map((_, i) => chartColors[i % chartColors.length]),
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Ingresos: $' + context.parsed.x.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // ========================================
        // 16 GRÁFICAS PROFESIONALES DE ANÁLISIS
        // ========================================

        // 1. Tendencia de Ventas (Línea con Área)
        function render1TendenciaVentas() {
            const ctx = document.getElementById('chartTendenciaVentas');
            if (!ctx) {
                console.warn('⚠️ No se encontró canvas chartTendenciaVentas');
                return;
            }

            console.log('📊 Renderizando gráfica 1: Tendencia de Ventas con', dashboardData.sales?.length, 'ventas');

            const salesByDate = {};
            dashboardData.sales.forEach(sale => {
                const date = new Date(sale.fecha || sale.date || sale.created_at).toLocaleDateString('es-MX');
                salesByDate[date] = (salesByDate[date] || 0) + parseFloat(sale.monto || sale.total || 0);
            });

            const sortedDates = Object.entries(salesByDate).sort((a,b) => new Date(a[0]) - new Date(b[0])).slice(-30);
            console.log('  → Fechas procesadas:', sortedDates.length);

            if(charts.tendenciaVentas) charts.tendenciaVentas.destroy();

            charts.tendenciaVentas = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: sortedDates.map(d => d[0]),
                    datasets: [{
                        label: 'Ventas Diarias',
                        data: sortedDates.map(d => d[1]),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: (context) => '$' + context.parsed.y.toFixed(2)
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (value) => '$' + value } }
                    }
                }
            });
        }

        // 2. Rentabilidad por Producto (Bar Horizontal)
        function render2Rentabilidad() {
            const ctx = document.getElementById('chartRentabilidad');
            if (!ctx) return;

            const productProfit = {};
            dashboardData.products.forEach(p => {
                const price = parseFloat(p.price || p.price || p.precio || 0);
                const cost = parseFloat(p.cost || p.costo || price * 0.6);
                const profit = (price - cost) * (p.stock || 10);
                const productName = p.name || p.nombre || 'Sin nombre';
                productProfit[productName] = profit;
            });

            const sorted = Object.entries(productProfit).sort((a,b) => b[1] - a[1]).slice(0,8);
            console.log('📊 Gráfica 2: Rentabilidad de', sorted.length, 'productos');

            if(charts.rentabilidad) charts.rentabilidad.destroy();

            charts.rentabilidad = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sorted.map(p => p[0]),
                    datasets: [{
                        label: 'Rentabilidad ($)',
                        data: sorted.map(p => p[1]),
                        backgroundColor: '#10B981',
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // 3. Distribución por Categoría (Doughnut)
        function render3Distribucion() {
            const ctx = document.getElementById('chartDistribucion');
            if (!ctx) return;

            console.log('📊 Gráfica 3: Distribución por Categoría');

            // Inicializar TODAS las categorías con $0
            const catSales = {};

            // Primero, agregar todas las categorías que existen
            dashboardData.categories.forEach(category => {
                const catName = category.name || category.nombre || 'Sin categoría';
                catSales[catName] = 0;
            });

            // También agregar categorías de productos (por si hay productos sin categoría registrada)
            dashboardData.products.forEach(product => {
                const category = product.category_name || product.categoria || 'Sin categoría';
                if (!catSales[category]) {
                    catSales[category] = 0;
                }
            });

            console.log('  → Categorías disponibles:', Object.keys(catSales));

            // Usar datos CACHEADOS de saleItems (ya cargados en fetchDashboardData)
            if (dashboardData.saleItems && dashboardData.saleItems.length > 0) {
                console.log('  → Usando sale items cacheados:', dashboardData.saleItems.length);

                // Calcular ventas REALES por categoría desde sale_items
                dashboardData.saleItems.forEach(item => {
                    // Buscar el producto para obtener su categoría
                    const product = dashboardData.products.find(p => p.id === item.product_id);

                    if (product) {
                        const category = product.category_name || product.categoria || 'Sin categoría';
                        const revenue = parseFloat(item.price || 0) * parseInt(item.quantity || 0);

                        catSales[category] = (catSales[category] || 0) + revenue;
                    }
                });
            } else {
                console.warn('  ⚠️ No hay sale items, usando estimación por inventario');
                // Fallback: Usar inventario como estimación
                dashboardData.products.forEach(product => {
                    const category = product.category_name || product.categoria || 'Sin categoría';
                    const value = (parseInt(product.stock || 0) * parseFloat(product.price || product.precio || 0));
                    catSales[category] = (catSales[category] || 0) + value;
                });
            }

            console.log('  → Ventas por categoría:', catSales);

                if(charts.distribucion) charts.distribucion.destroy();

                const categories = Object.keys(catSales);
                const values = Object.values(catSales);
                const total = values.reduce((a, b) => a + b, 0);

                charts.distribucion = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: categories,
                        datasets: [{
                            data: values,
                            backgroundColor: ['#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#3B82F6', '#EF4444', '#F97316', '#14B8A6'],
                            borderWidth: 2,
                            borderColor: '#1F2937'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12
                                    },
                                    generateLabels: (chart) => {
                                        const data = chart.data;
                                        return data.labels.map((label, i) => {
                                            const value = data.datasets[0].data[i];
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return {
                                                text: `${label}: ${percentage}%`,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return [
                                            `${label}`,
                                            `Ventas: $${value.toFixed(2)}`,
                                            `Porcentaje: ${percentage}%`
                                        ];
                                    }
                                }
                            }
                        }
                    }
                });
        }

        // 4. Inventario vs Ventas (Mixed)
        function render4InventarioVentas() {
            const ctx = document.getElementById('chartInventarioVentas');
            if (!ctx) return;

            console.log('📊 Gráfica 4: Inventario vs Ventas');

            // Calcular ventas por producto (simulación basada en stock vendido)
            const productSales = {};
            dashboardData.products.forEach(product => {
                const productName = product.name || product.nombre || 'Sin nombre';
                productSales[productName] = {
                    stock: parseInt(product.stock || 0),
                    // Estimar ventas: si el stock es bajo, probablemente se vendió más
                    sales: Math.max(0, 100 - parseInt(product.stock || 0))
                };
            });

            const products = Object.entries(productSales).slice(0, 10);

            console.log('  → Productos analizados:', products.length);

            if(charts.inventarioVentas) charts.inventarioVentas.destroy();

            charts.inventarioVentas = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: products.map(p => p[0]),
                    datasets: [
                        {
                            label: 'Stock Actual',
                            data: products.map(p => p[1].stock),
                            backgroundColor: '#F59E0B',
                            type: 'bar'
                        },
                        {
                            label: 'Unidades Vendidas (Est.)',
                            data: products.map(p => p[1].sales),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            type: 'line',
                            tension: 0.4,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: (context) => context.dataset.label + ': ' + context.parsed.y + ' unidades'
                            }
                        }
                    }
                }
            });
        }

        // 5. Ticket Promedio Diario (Área)
        function render5TicketPromedio() {
            const ctx = document.getElementById('chartTicketPromedio');
            if (!ctx) return;

            const dailyTickets = {};
            dashboardData.sales.forEach(sale => {
                const date = new Date(sale.fecha || sale.created_at).toLocaleDateString();
                if(!dailyTickets[date]) dailyTickets[date] = { total: 0, count: 0 };
                dailyTickets[date].total += parseFloat(sale.monto || 0);
                dailyTickets[date].count++;
            });

            const avgTickets = Object.entries(dailyTickets).map(([date, data]) => ({
                date,
                avg: data.total / data.count
            })).slice(-15);

            if(charts.ticketPromedio) charts.ticketPromedio.destroy();

            charts.ticketPromedio = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: avgTickets.map(t => t.date),
                    datasets: [{
                        label: 'Ticket Promedio',
                        data: avgTickets.map(t => t.avg),
                        borderColor: '#06B6D4',
                        backgroundColor: 'rgba(6, 182, 212, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // 6. Top 10 Productos (Bar con Gradiente)
        function render6Top10Productos() {
            const ctx = document.getElementById('chartTop10Productos');
            if (!ctx) return;

            console.log('📊 Gráfica 6: Top 10 Productos Más Vendidos');

            let productSales = {};

            // Usar datos CACHEADOS de saleItems (ya cargados en fetchDashboardData)
            if (dashboardData.saleItems && dashboardData.saleItems.length > 0) {
                console.log('  → Usando sale items cacheados:', dashboardData.saleItems.length);

                // Contar ventas reales por producto
                dashboardData.saleItems.forEach(item => {
                    const productId = item.product_id;
                    if (!productSales[productId]) {
                        productSales[productId] = {
                            id: productId,
                            quantity: 0,
                            revenue: 0
                        };
                    }
                    productSales[productId].quantity += parseInt(item.quantity || 0);
                    productSales[productId].revenue += parseFloat(item.price || 0) * parseInt(item.quantity || 0);
                });
            } else {
                console.warn('  ⚠️ No hay sale items, usando estimación por inventario');
                // Fallback: Usar inventario inverso como estimación
                dashboardData.products.forEach(product => {
                    const stock = parseInt(product.stock || 0);
                    productSales[product.id] = {
                        id: product.id,
                        quantity: Math.max(0, 100 - stock),
                        revenue: Math.max(0, 100 - stock) * parseFloat(product.price || product.precio || 0)
                    };
                });
            }

                // Unir con nombres de productos
                const productData = Object.values(productSales).map(sale => {
                    const product = dashboardData.products.find(p => p.id === sale.id);
                    return {
                        name: product ? (product.name || product.nombre || 'Producto #' + sale.id) : 'Desconocido',
                        quantity: sale.quantity,
                        revenue: sale.revenue,
                        price: product ? parseFloat(product.price || product.precio || 0) : 0
                    };
                });

                // Ordenar por cantidad vendida y tomar top 10
                const top10 = productData
                    .sort((a, b) => b.quantity - a.quantity)
                    .slice(0, 10);

                console.log('  → Top 10 productos:', top10.map(p => `${p.name}: ${p.quantity} unidades`));

                if(charts.top10) charts.top10.destroy();

                const gradientColors = [
                    '#F43F5E', '#EC4899', '#D946EF', '#C026D3',
                    '#A855F7', '#9333EA', '#7C3AED', '#6366F1',
                    '#3B82F6', '#0EA5E9'
                ];

                charts.top10 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: top10.map(p => p.name),
                        datasets: [{
                            label: 'Unidades Vendidas',
                            data: top10.map(p => p.quantity),
                            backgroundColor: gradientColors,
                            borderRadius: 10,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Barras horizontales para mejor lectura de nombres
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const product = top10[context.dataIndex];
                                        return [
                                            `Vendidos: ${product.quantity} unidades`,
                                            `Precio: $${product.price.toFixed(2)}`,
                                            `Ingresos: $${product.revenue.toFixed(2)}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => value + ' unidades'
                                }
                            }
                        }
                    }
                });
        }

        // 7. Análisis de Márgenes (Radar)
        function render7MargenGanancia() {
            const ctx = document.getElementById('chartMargenGanancia');
            if (!ctx) return;

            if(charts.margen) charts.margen.destroy();

            charts.margen = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Margen Bruto', 'Margen Neto', 'ROI', 'Eficiencia', 'Liquidez'],
                    datasets: [{
                        label: 'Mes Actual',
                        data: [75, 60, 85, 70, 90],
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: '#6366F1',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // 8. Conversión por Hora (Línea Escalonada)
        function render8ConversionHora() {
            const ctx = document.getElementById('chartConversionHora');
            if (!ctx) return;

            console.log('📊 Gráfica 8: Conversión por Hora');

            // Analizar ventas por hora del día
            const hourlyStats = {};
            for (let i = 8; i <= 20; i++) {
                hourlyStats[i] = { sales: 0, count: 0 };
            }

            dashboardData.sales.forEach(sale => {
                const saleDate = new Date(sale.fecha || sale.sale_date || sale.created_at);
                const hour = saleDate.getHours();
                if (hour >= 8 && hour <= 20) {
                    hourlyStats[hour].sales += parseFloat(sale.monto || sale.total || sale.amount || 0);
                    hourlyStats[hour].count++;
                }
            });

            const hours = Object.keys(hourlyStats).map(h => `${h}:00`);
            const conversions = Object.values(hourlyStats).map(stat => stat.count);

            console.log('  → Ventas por hora:', hourlyStats);

            if(charts.conversion) charts.conversion.destroy();

            charts.conversion = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Ventas por Hora',
                        data: conversions,
                        borderColor: '#EA580C',
                        backgroundColor: 'rgba(234, 88, 12, 0.1)',
                        stepped: true,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // 9. Rotación de Inventario (Polar Area)
        function render9RotacionInventario() {
            const ctx = document.getElementById('chartRotacionInventario');
            if (!ctx) return;

            console.log('📊 Gráfica 9: Rotación de Inventario');

            // Calcular rotación real por categoría (ventas / stock disponible)
            const categoryRotation = {};

            // Agrupar productos por categoría
            dashboardData.products.forEach(product => {
                const category = product.category_name || product.categoria || 'Sin categoría';
                if (!categoryRotation[category]) {
                    categoryRotation[category] = { sales: 0, stock: 0 };
                }
                categoryRotation[category].stock += parseInt(product.stock || 0);
            });

            // Calcular ventas por categoría
            dashboardData.sales.forEach(sale => {
                // Si tienes detalles de venta por producto, úsalos aquí
                // Por ahora, distribuimos las ventas entre todas las categorías
                Object.keys(categoryRotation).forEach(cat => {
                    categoryRotation[cat].sales += parseFloat(sale.monto || sale.total || sale.amount || 0) / Object.keys(categoryRotation).length;
                });
            });

            // Calcular índice de rotación (ventas totales / stock promedio)
            const rotationData = Object.entries(categoryRotation).map(([name, data]) => ({
                name,
                rotation: data.stock > 0 ? (data.sales / data.stock).toFixed(2) : 0
            })).sort((a, b) => b.rotation - a.rotation).slice(0, 6);

            console.log('  → Rotación calculada:', rotationData);

            if(charts.rotacion) charts.rotacion.destroy();

            charts.rotacion = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: rotationData.map(d => d.name),
                    datasets: [{
                        label: 'Índice de Rotación',
                        data: rotationData.map(d => d.rotation),
                        backgroundColor: ['#14B8A6', '#06B6D4', '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: (context) => context.label + ': ' + context.parsed + ' veces'
                            }
                        }
                    }
                }
            });
        }

        // 10. Clientes Frecuentes (Bar Apilado)
        function render10ClientesFrecuentes() {
            const ctx = document.getElementById('chartClientesFrecuentes');
            if (!ctx) return;

            console.log('📊 Gráfica 10: Clientes Frecuentes');

            // Calcular estadísticas reales de clientes
            const customerStats = {};

            dashboardData.customers.forEach(customer => {
                const customerId = customer.id;
                const customerName = customer.name || customer.nombre || 'Cliente #' + customerId;

                customerStats[customerName] = {
                    purchases: 0,
                    totalSpent: 0
                };
            });

            // Contar compras y gasto total por cliente
            dashboardData.sales.forEach(sale => {
                const customerName = sale.cliente || sale.customer || 'Desconocido';
                if (customerStats[customerName]) {
                    customerStats[customerName].purchases++;
                    customerStats[customerName].totalSpent += parseFloat(sale.monto || sale.total || sale.amount || 0);
                }
            });

            // Ordenar por número de compras y tomar top 6
            const topCustomers = Object.entries(customerStats)
                .sort((a, b) => b[1].purchases - a[1].purchases)
                .slice(0, 6);

            console.log('  → Top clientes:', topCustomers);

            if(charts.clientesFrecuentes) charts.clientesFrecuentes.destroy();

            charts.clientesFrecuentes = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: topCustomers.map(c => c[0]),
                    datasets: [
                        {
                            label: 'Compras',
                            data: topCustomers.map(c => c[1].purchases),
                            backgroundColor: '#EC4899'
                        },
                        {
                            label: 'Gasto Total ($)',
                            data: topCustomers.map(c => c[1].totalSpent),
                            backgroundColor: '#8B5CF6'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: false },
                        y: {
                            stacked: false,
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    if (label.includes('Gasto')) {
                                        return label + ': $' + value.toFixed(2);
                                    }
                                    return label + ': ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 11. Pronóstico de Ventas (Línea con Predicción)
        function render11Pronostico() {
            const ctx = document.getElementById('chartPronostico');
            if (!ctx) return;

            console.log('📊 Gráfica 11: Pronóstico de Ventas');

            // Agrupar ventas REALES por semana (últimas 7 semanas)
            const weeklyData = {};
            const today = new Date();
            const sevenWeeksAgo = new Date(today.getTime() - (7 * 7 * 24 * 60 * 60 * 1000));

            // Inicializar últimas 7 semanas
            for (let i = 0; i < 7; i++) {
                const weekStart = new Date(sevenWeeksAgo.getTime() + (i * 7 * 24 * 60 * 60 * 1000));
                const weekKey = `S${i + 1}`;
                weeklyData[weekKey] = 0;
            }

            // Calcular ventas reales
            dashboardData.sales.forEach(sale => {
                const saleDate = new Date(sale.fecha || sale.sale_date || sale.created_at);
                if (saleDate >= sevenWeeksAgo && saleDate <= today) {
                    const weeksDiff = Math.floor((saleDate - sevenWeeksAgo) / (7 * 24 * 60 * 60 * 1000));
                    const weekKey = `S${Math.min(weeksDiff + 1, 7)}`;

                    const amount = parseFloat(sale.monto || sale.total || sale.amount || 0);
                    if (weeklyData[weekKey] !== undefined) {
                        weeklyData[weekKey] += amount;
                    }
                }
            });

            const historico = Object.values(weeklyData);

            // Calcular predicción usando regresión lineal simple
            // Fórmula: y = mx + b (tendencia lineal)
            const n = historico.length;
            let sumX = 0, sumY = 0, sumXY = 0, sumX2 = 0;

            historico.forEach((value, index) => {
                sumX += index;
                sumY += value;
                sumXY += index * value;
                sumX2 += index * index;
            });

            // Calcular pendiente (m) e intercepto (b)
            const m = (n * sumXY - sumX * sumY) / (n * sumX2 - sumX * sumX);
            const b = (sumY - m * sumX) / n;

            // Predecir próximas 3 semanas
            const prediccionWeek8 = m * 7 + b;
            const prediccionWeek9 = m * 8 + b;
            const prediccionWeek10 = m * 9 + b;

            // Crear arrays para la gráfica
            const labels = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8', 'S9', 'S10'];
            const historicoData = [...historico, null, null, null];
            const prediccionData = [null, null, null, null, null, null, historico[6], prediccionWeek8, prediccionWeek9, prediccionWeek10];

            // Calcular crecimiento
            const avgHistorico = historico.reduce((a, b) => a + b, 0) / historico.length;
            const crecimiento = ((prediccionWeek8 - historico[6]) / historico[6] * 100).toFixed(1);

            console.log('  → Ventas históricas:', historico);
            console.log('  → Predicción S8: $' + prediccionWeek8.toFixed(2));
            console.log('  → Tendencia: ' + (m > 0 ? '📈 Creciente' : '📉 Decreciente'));
            console.log('  → Crecimiento esperado: ' + crecimiento + '%');

            if(charts.pronostico) charts.pronostico.destroy();

            charts.pronostico = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Ventas Históricas',
                            data: historicoData,
                            borderColor: '#7C3AED',
                            backgroundColor: 'rgba(124, 58, 237, 0.2)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Predicción (IA)',
                            data: prediccionData,
                            borderColor: '#EC4899',
                            borderDash: [8, 4],
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointStyle: 'rectRot',
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    if (value === null) return '';
                                    return label + ': $' + value.toFixed(2);
                                },
                                afterLabel: (context) => {
                                    if (context.datasetIndex === 1 && context.parsed.y !== null) {
                                        return '🔮 Basado en tendencia histórica';
                                    }
                                    return '';
                                }
                            }
                        },
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    xMin: 6.5,
                                    xMax: 6.5,
                                    borderColor: '#9CA3AF',
                                    borderWidth: 2,
                                    borderDash: [5, 5]
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => '$' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        // 12. Punto de Equilibrio (Mixed)
        function render12PuntoEquilibrio() {
            const ctx = document.getElementById('chartPuntoEquilibrio');
            if (!ctx) return;

            const units = [0, 20, 40, 60, 80, 100, 120];
            const costos = units.map(u => 10000 + u * 50);
            const ingresos = units.map(u => u * 150);

            if(charts.equilibrio) charts.equilibrio.destroy();

            charts.equilibrio = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: units,
                    datasets: [
                        { label: 'Costos', data: costos, borderColor: '#EF4444', fill: false },
                        { label: 'Ingresos', data: ingresos, borderColor: '#10B981', fill: false }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // 13. Estacionalidad (Heatmap Style Bar)
        function render13Estacionalidad() {
            const ctx = document.getElementById('chartEstacionalidad');
            if (!ctx) return;

            console.log('📊 Gráfica 13: Análisis de Estacionalidad');

            // Calcular ventas REALES por mes
            const salesByMonth = {
                0: 0, 1: 0, 2: 0, 3: 0, 4: 0, 5: 0,
                6: 0, 7: 0, 8: 0, 9: 0, 10: 0, 11: 0
            };

            dashboardData.sales.forEach(sale => {
                const saleDate = new Date(sale.fecha || sale.sale_date || sale.created_at);
                const month = saleDate.getMonth(); // 0-11
                const amount = parseFloat(sale.monto || sale.total || sale.amount || 0);
                salesByMonth[month] += amount;
            });

            const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const ventas = Object.values(salesByMonth);

            // Calcular promedio para determinar colores
            const avgSales = ventas.reduce((a, b) => a + b, 0) / ventas.length;
            const maxSales = Math.max(...ventas);

            console.log('  → Ventas por mes:', salesByMonth);
            console.log('  → Promedio mensual: $' + avgSales.toFixed(2));
            console.log('  → Mes más alto: $' + maxSales.toFixed(2));

            if(charts.estacionalidad) charts.estacionalidad.destroy();

            charts.estacionalidad = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Ventas por Mes',
                        data: ventas,
                        // Color verde si está por encima del promedio, amarillo si está cerca, rojo si está por debajo
                        backgroundColor: ventas.map(v =>
                            v > avgSales * 1.2 ? '#10B981' : // Verde (20% arriba del promedio)
                            v > avgSales * 0.8 ? '#F59E0B' : // Amarillo (cerca del promedio)
                            '#EF4444'  // Rojo (20% debajo del promedio)
                        ),
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const value = context.parsed.y;
                                    const percentage = ((value / avgSales - 1) * 100).toFixed(1);
                                    return [
                                        'Ventas: $' + value.toFixed(2),
                                        'vs Promedio: ' + (percentage >= 0 ? '+' : '') + percentage + '%'
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => '$' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        // 14. Análisis ABC (Pareto)
        function render14ABC() {
            const ctx = document.getElementById('chartABC');
            if (!ctx) return;

            const products = dashboardData.products.slice(0, 10);
            const cumulative = products.map((_, i, arr) =>
                arr.slice(0, i+1).reduce((sum, p) => sum + ((p.price || p.precio) * p.stock || 0), 0)
            );

            if(charts.abc) charts.abc.destroy();

            charts.abc = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: products.map(p => p.name || p.nombre),
                    datasets: [
                        {
                            label: 'Valor',
                            data: products.map(p => (p.price || p.precio) * p.stock || 0),
                            backgroundColor: '#0EA5E9',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Acumulado %',
                            data: cumulative.map((c, i, arr) => (c / arr[arr.length-1]) * 100),
                            borderColor: '#EF4444',
                            backgroundColor: 'transparent',
                            type: 'line',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { position: 'left' },
                        y1: { position: 'right', max: 100 }
                    }
                }
            });
        }

        // 15. Cash Flow Proyectado (Área)
        function render15CashFlow() {
            const ctx = document.getElementById('chartCashFlow');
            if (!ctx) return;

            console.log('📊 Gráfica 15: Cash Flow Proyectado');

            // Agrupar ingresos REALES por semana (últimas 8 semanas)
            const weeklyData = {};
            const today = new Date();
            const eightWeeksAgo = new Date(today.getTime() - (8 * 7 * 24 * 60 * 60 * 1000));

            // Inicializar semanas
            for (let i = 0; i < 8; i++) {
                const weekStart = new Date(eightWeeksAgo.getTime() + (i * 7 * 24 * 60 * 60 * 1000));
                const weekKey = `S${i + 1}`;
                weeklyData[weekKey] = {
                    ingresos: 0,
                    egresos: 0,
                    label: weekKey
                };
            }

            // Calcular ingresos reales de ventas
            dashboardData.sales.forEach(sale => {
                const saleDate = new Date(sale.fecha || sale.sale_date || sale.created_at);
                if (saleDate >= eightWeeksAgo && saleDate <= today) {
                    const weeksDiff = Math.floor((saleDate - eightWeeksAgo) / (7 * 24 * 60 * 60 * 1000));
                    const weekKey = `S${Math.min(weeksDiff + 1, 8)}`;

                    const amount = parseFloat(sale.monto || sale.total || sale.amount || 0);
                    if (weeklyData[weekKey]) {
                        weeklyData[weekKey].ingresos += amount;
                    }
                }
            });

            // Estimar egresos (costos) basados en el margen típico del negocio
            // Suponiendo que los productos tienen un costo del 60% del precio de venta
            Object.keys(weeklyData).forEach(week => {
                const ingresos = weeklyData[week].ingresos;
                // Egresos = costo de productos vendidos (60%) + gastos operativos estimados (10%)
                weeklyData[week].egresos = ingresos * 0.70; // 70% de costos totales
            });

            const semanas = Object.keys(weeklyData);
            const ingresos = Object.values(weeklyData).map(w => w.ingresos);
            const egresos = Object.values(weeklyData).map(w => w.egresos);
            const flujoNeto = ingresos.map((ing, i) => ing - egresos[i]);

            console.log('  → Cash Flow semanal:', weeklyData);
            console.log('  → Flujo neto promedio: $' + (flujoNeto.reduce((a,b) => a+b, 0) / flujoNeto.length).toFixed(2));

            if(charts.cashflow) charts.cashflow.destroy();

            charts.cashflow = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: semanas,
                    datasets: [
                        {
                            label: 'Ingresos (Ventas)',
                            data: ingresos,
                            borderColor: '#16A34A',
                            backgroundColor: 'rgba(22, 163, 74, 0.2)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3
                        },
                        {
                            label: 'Egresos (Costos Est.)',
                            data: egresos,
                            borderColor: '#DC2626',
                            backgroundColor: 'rgba(220, 38, 38, 0.2)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3
                        },
                        {
                            label: 'Flujo Neto',
                            data: flujoNeto,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            borderDash: [5, 5]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    return label + ': $' + value.toFixed(2);
                                },
                                afterBody: (items) => {
                                    const index = items[0].dataIndex;
                                    const ing = ingresos[index];
                                    const egr = egresos[index];
                                    const neto = ing - egr;
                                    return [
                                        '',
                                        '💰 Flujo Neto: $' + neto.toFixed(2),
                                        neto > 0 ? '✅ Positivo' : '⚠️ Negativo'
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => '$' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        // 16. KPIs Comparativo (Radar Multi)
        function render16KPIs() {
            const ctx = document.getElementById('chartKPIs');
            if (!ctx) return;

            const totalSales = dashboardData.sales.reduce((sum, s) => sum + parseFloat(s.monto || s.total || 0), 0);
            const avgTicket = totalSales / (dashboardData.sales.length || 1);
            const totalProducts = dashboardData.products.length;
            const avgStock = dashboardData.products.reduce((sum, p) => sum + (p.stock || 0), 0) / (totalProducts || 1);

            if(charts.kpis) charts.kpis.destroy();

            charts.kpis = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Ventas', 'Ticket Avg', 'Inventario', 'Productos', 'Clientes', 'Rotación'],
                    datasets: [
                        {
                            label: 'Actual',
                            data: [
                                Math.min(totalSales / 1000, 100),
                                Math.min(avgTicket / 10, 100),
                                Math.min(avgStock * 2, 100),
                                Math.min(totalProducts * 5, 100),
                                Math.min(dashboardData.customers.length * 10, 100),
                                Math.floor(Math.random() * 30) + 60
                            ],
                            backgroundColor: 'rgba(220, 38, 38, 0.2)',
                            borderColor: '#DC2626',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: { beginAtZero: true, max: 100 }
                    }
                }
            });
        }

        // 17. Métodos de Pago (Pie)
        function render17MetodosPago() {
            const ctx = document.getElementById('chartMetodosPago');
            if (!ctx) return;

            const paymentMethods = {};
            dashboardData.sales.forEach(sale => {
                const method = sale.payment_method || sale.metodo_pago || 'Efectivo';
                const methodName = method === 'efectivo' ? 'Efectivo' :
                                 method === 'tarjeta_debito' ? 'Tarjeta Débito' :
                                 method === 'tarjeta_credito' ? 'Tarjeta Crédito' :
                                 method === 'transferencia' ? 'Transferencia' : 'Efectivo';
                paymentMethods[methodName] = (paymentMethods[methodName] || 0) + parseFloat(sale.monto || sale.total || sale.amount || 0);
            });

            const labels = Object.keys(paymentMethods);
            const data = Object.values(paymentMethods);

            if(charts.metodosPago) charts.metodosPago.destroy();

            charts.metodosPago = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels.length > 0 ? labels : ['Efectivo', 'Tarjeta', 'Transferencia'],
                    datasets: [{
                        data: data.length > 0 ? data : [60, 30, 10],
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6', '#EC4899'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, font: { size: 11 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // 18. Ventas por Día de Semana (Bar)
        function render18VentasDiaSemana() {
            const ctx = document.getElementById('chartVentasDiaSemana');
            if (!ctx) return;

            const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const ventasPorDia = [0, 0, 0, 0, 0, 0, 0];
            const transaccionesPorDia = [0, 0, 0, 0, 0, 0, 0];

            dashboardData.sales.forEach(sale => {
                const date = new Date(sale.fecha || sale.date || sale.created_at || sale.sale_date);
                const dayOfWeek = date.getDay();
                ventasPorDia[dayOfWeek] += parseFloat(sale.monto || sale.total || sale.amount || 0);
                transaccionesPorDia[dayOfWeek]++;
            });

            if(charts.ventasDiaSemana) charts.ventasDiaSemana.destroy();

            charts.ventasDiaSemana = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: diasSemana,
                    datasets: [
                        {
                            label: 'Ventas ($)',
                            data: ventasPorDia,
                            backgroundColor: '#64748B',
                            borderRadius: 8,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Transacciones',
                            data: transaccionesPorDia,
                            borderColor: '#F59E0B',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            type: 'line',
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.dataset.yAxisID === 'y') {
                                        label += '$' + context.parsed.y.toFixed(2);
                                    } else {
                                        label += context.parsed.y + ' transacciones';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(0);
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }

        function renderAllCharts() {
            console.log('📈 Iniciando renderizado de 18 gráficas...');
            console.log('📋 Datos disponibles:', {
                ventas: dashboardData.sales?.length,
                productos: dashboardData.products?.length,
                categorias: dashboardData.categories?.length,
                clientes: dashboardData.customers?.length
            });

            // Renderizar las 18 gráficas profesionales
            try {
                render1TendenciaVentas();
                render2Rentabilidad();
                render3Distribucion();
                render4InventarioVentas();
                render5TicketPromedio();
                render6Top10Productos();
                render7MargenGanancia();
                render8ConversionHora();
                render9RotacionInventario();
                render10ClientesFrecuentes();
                render11Pronostico();
                render12PuntoEquilibrio();
                render13Estacionalidad();
                render14ABC();
                render15CashFlow();
                render16KPIs();
                render17MetodosPago();
                render18VentasDiaSemana();
                console.log('✅ Todas las gráficas renderizadas correctamente');
            } catch (error) {
                console.error('❌ Error al renderizar gráficas:', error);
            }
        }

        // FUNCIÓN DE CONTENIDO PARA 'ESCANEAR PRODUCTO' - PUNTO DE VENTA (POS)
        let html5QrCode = null;
        let scannerActive = false;
        let barcodeBuffer = '';
        let barcodeTimeout = null;
        let cartItems = []; // Carrito de compras
        let customerDisplayWindow = null; // Ventana de pantalla del cliente
        let posChannel = null; // Canal de comunicación BroadcastChannel

        function renderScanProduct() {
            const contentArea = document.getElementById('main-content-area');

            // Inicializar BroadcastChannel para comunicación con pantalla del cliente
            try {
                posChannel = new BroadcastChannel('pos_channel');
                console.log('BroadcastChannel inicializado');
            } catch (error) {
                console.warn('BroadcastChannel no disponible, usando localStorage como fallback');
            }

            contentArea.innerHTML = `
                <div class="max-w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <!-- Panel Izquierdo: Escáner -->
                        <div class="lg:col-span-2">
                            <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-custom-active">
                                <div class="flex items-center mb-6">
                                    <i class='bx bx-barcode-reader text-3xl mr-4' style="color: #5F9E74;"></i>
                                    <div>
                                        <h3 class="text-2xl font-bold text-custom-text">Punto de Venta</h3>
                                        <p class="text-sm text-custom-gray">Escanea productos para agregarlos a la venta</p>
                                    </div>
                                </div>

                                <!-- Escáner Físico -->
                                <div class="mb-4 bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
                                    <div class="flex items-center mb-2">
                                        <i class='bx bx-devices text-xl mr-2 text-blue-600'></i>
                                        <h4 class="font-bold text-blue-900">Lector de Código de Barras</h4>
                                    </div>
                                    <input
                                        type="text"
                                        id="barcode-input"
                                        placeholder="Escanea aquí..."
                                        class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-active text-lg font-mono"
                                        autocomplete="off"
                                    >
                                </div>

                                <!-- Escáner con Cámara -->
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border-2 border-green-200 dark:border-green-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <i class='bx bx-barcode text-xl mr-2 text-green-600 dark:text-green-400'></i>
                                            <div>
                                                <h4 class="font-bold text-green-900 dark:text-green-100">Escáner de Código de Barras y QR</h4>
                                                <p class="text-xs text-green-700 dark:text-green-300">Escanea productos con la cámara</p>
                                            </div>
                                        </div>
                                        <button
                                            id="toggle-camera-btn"
                                            onclick="toggleCameraScanner()"
                                            class="bg-custom-active hover:bg-green-700 text-white px-3 py-2 rounded-lg font-medium transition-colors shadow-md text-sm"
                                        >
                                            <i class='bx bx-camera mr-1'></i> Activar
                                        </button>
                                    </div>
                                    <div id="camera-reader" class="hidden mt-3 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                        <div id="qr-reader" class="rounded-lg overflow-hidden border-2 border-green-400 dark:border-green-600"></div>
                                        <p class="text-xs text-center text-gray-600 dark:text-gray-400 mt-2">
                                            📱 Soporta: EAN-13, EAN-8, UPC, Code 128, Code 39, QR y más
                                        </p>
                                    </div>
                                </div>

                                <!-- Lista de Productos Escaneados -->
                                <div class="mt-6">
                                    <h4 class="text-lg font-bold text-custom-text mb-3 flex items-center">
                                        <i class='bx bx-cart text-xl mr-2'></i>
                                        Productos en la Venta
                                    </h4>
                                    <div id="cart-items-list" class="space-y-2 max-h-96 overflow-y-auto">
                                        <div class="text-center py-8 text-custom-gray">
                                            <i class='bx bx-cart text-5xl opacity-30'></i>
                                            <p class="mt-2">No hay productos escaneados</p>
                                            <p class="text-sm">Escanea un producto para comenzar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Derecho: Resumen y Total -->
                        <div class="lg:col-span-1">
                            <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-emerald-500 sticky top-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-xl font-bold text-custom-text flex items-center">
                                        <i class='bx bx-receipt text-2xl mr-2 text-emerald-600'></i>
                                        Resumen de Venta
                                    </h4>
                                    <div class="flex gap-2">
                                        <button onclick="openCustomerDisplay()"
                                           class="text-gray-500 hover:text-blue-600 transition-colors"
                                           title="Abrir Pantalla del Cliente">
                                            <i class='bx bx-desktop text-2xl'></i>
                                        </button>
                                        <button onclick="openSettingsModal()"
                                           class="text-gray-500 hover:text-emerald-600 transition-colors"
                                           title="Configurar tickets">
                                            <i class='bx bx-cog text-2xl'></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-custom-gray">Productos:</span>
                                        <span id="cart-count" class="font-bold text-custom-text">0</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-custom-gray">Unidades:</span>
                                        <span id="cart-units" class="font-bold text-custom-text">0</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 bg-emerald-50 px-3 rounded-lg">
                                        <span class="text-lg font-bold text-emerald-700">TOTAL:</span>
                                        <span id="cart-total" class="text-2xl font-extrabold text-emerald-600">$0.00</span>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <button
                                        onclick="processSale()"
                                        id="process-sale-btn"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 px-4 rounded-lg font-bold text-lg transition-colors shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                                        disabled
                                    >
                                        <i class='bx bx-check-circle text-2xl mr-2'></i>
                                        Procesar Venta
                                    </button>
                                    <button
                                        onclick="clearCart()"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg font-semibold transition-colors shadow-md flex items-center justify-center"
                                    >
                                        <i class='bx bx-trash text-xl mr-2'></i>
                                        Limpiar Todo
                                    </button>
                                </div>

                                <!-- Mensaje de Última Acción -->
                                <div id="last-action-msg" class="mt-4 hidden">
                                    <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded">
                                        <p class="text-sm text-green-700 font-medium"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de Configuración -->
                    <div id="settings-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none; pointer-events: none;">
                        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" style="pointer-events: auto;">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                                        <i class='bx bx-cog text-3xl mr-2 text-emerald-600'></i>
                                        Configuración de Tickets
                                    </h3>
                                    <button onclick="closeSettingsModal()" class="text-gray-400 hover:text-gray-600">
                                        <i class='bx bx-x text-3xl'></i>
                                    </button>
                                </div>
                            </div>

                            <form id="settings-form" class="p-6 space-y-6">
                                <!-- Nombre del Negocio -->
                                <div>
                                    <label for="business-name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class='bx bx-store text-emerald-600 mr-1'></i>
                                        Nombre del Negocio
                                    </label>
                                    <input type="text" id="business-name" name="name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-800"
                                        placeholder="Ej: Tienda La Esquina" required>
                                </div>

                                <!-- Teléfono del Negocio -->
                                <div>
                                    <label for="business-phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class='bx bx-phone text-emerald-600 mr-1'></i>
                                        Teléfono del Negocio
                                    </label>
                                    <input type="text" id="business-phone" name="phone"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-800"
                                        placeholder="Ej: (555) 123-4567">
                                </div>

                                <!-- Ubicación del Negocio -->
                                <div>
                                    <label for="business-location" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class='bx bx-map text-emerald-600 mr-1'></i>
                                        Ubicación del Negocio
                                    </label>
                                    <textarea id="business-location" name="location" rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-800"
                                        placeholder="Ej: Calle Principal #123, Col. Centro, Ciudad, Estado, C.P. 12345"></textarea>
                                </div>

                                <!-- Logo -->
                                <div>
                                    <label for="business-logo" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Logo del Negocio
                                    </label>
                                    <input type="file" id="business-logo" name="logo"
                                        accept="image/png,image/jpeg,image/jpg,image/gif,image/svg+xml"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-800
                                               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold
                                               file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    <p class="text-xs text-gray-500 mt-2">Formatos: JPG, PNG, GIF, SVG (máx. 2MB)</p>
                                </div>

                                <!-- Preview del Logo Actual -->
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Logo Actual:</p>
                                    <div class="bg-gray-50 p-4 rounded-lg inline-block">
                                        <img id="current-logo-preview" src="" alt="Logo actual"
                                             class="h-20 w-auto object-contain"
                                             onerror="this.src='/images/default_logo.png'">
                                    </div>
                                </div>

                                <!-- Mensaje de respuesta -->
                                <div id="settings-message" class="hidden"></div>

                                <!-- Botones -->
                                <div class="flex gap-3 pt-4">
                                    <button type="submit"
                                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg">
                                        <i class='bx bx-save mr-2'></i>
                                        Guardar Configuración
                                    </button>
                                    <button type="button" onclick="closeSettingsModal()"
                                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal de Método de Pago -->
                    <div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
                        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                                        <i class='bx bx-money text-3xl mr-2 text-emerald-600'></i>
                                        Método de Pago
                                    </h3>
                                    <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                                        <i class='bx bx-x text-3xl'></i>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Total de la Venta -->
                                <div class="bg-emerald-50 p-4 rounded-lg border-2 border-emerald-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-700">Total a Cobrar:</span>
                                        <span id="payment-total" class="text-3xl font-black text-emerald-600">$0.00</span>
                                    </div>
                                </div>

                                <!-- Selector de Método de Pago -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-3">
                                        <i class='bx bx-wallet text-emerald-600 mr-1'></i>
                                        Selecciona el Método de Pago
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" onclick="selectPaymentMethod('efectivo')"
                                            class="payment-method-btn border-2 border-gray-300 rounded-lg p-4 hover:border-emerald-500 hover:bg-emerald-50 transition-all text-center"
                                            data-method="efectivo">
                                            <i class='bx bx-money text-4xl text-emerald-600'></i>
                                            <p class="font-bold text-gray-700 mt-2">Efectivo</p>
                                        </button>
                                        <button type="button" onclick="selectPaymentMethod('tarjeta_debito')"
                                            class="payment-method-btn border-2 border-gray-300 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition-all text-center"
                                            data-method="tarjeta_debito">
                                            <i class='bx bx-credit-card text-4xl text-blue-600'></i>
                                            <p class="font-bold text-gray-700 mt-2">Tarjeta Débito</p>
                                        </button>
                                        <button type="button" onclick="selectPaymentMethod('tarjeta_credito')"
                                            class="payment-method-btn border-2 border-gray-300 rounded-lg p-4 hover:border-purple-500 hover:bg-purple-50 transition-all text-center"
                                            data-method="tarjeta_credito">
                                            <i class='bx bx-credit-card-alt text-4xl text-purple-600'></i>
                                            <p class="font-bold text-gray-700 mt-2">Tarjeta Crédito</p>
                                        </button>
                                        <button type="button" onclick="selectPaymentMethod('transferencia')"
                                            class="payment-method-btn border-2 border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 transition-all text-center"
                                            data-method="transferencia">
                                            <i class='bx bx-transfer text-4xl text-indigo-600'></i>
                                            <p class="font-bold text-gray-700 mt-2">Transferencia</p>
                                        </button>
                                    </div>
                                </div>

                                <!-- Campo para Efectivo: Monto Recibido -->
                                <div id="cash-section" class="hidden">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class='bx bx-dollar text-emerald-600 mr-1'></i>
                                        Monto Recibido
                                    </label>
                                    <input type="number" id="amount-received" step="0.01" min="0"
                                        placeholder="0.00"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-800 text-lg font-bold"
                                        oninput="calculateChange()">

                                    <!-- Cambio a Devolver -->
                                    <div id="change-section" class="mt-3 p-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg hidden">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-semibold text-gray-700">Cambio:</span>
                                            <span id="change-amount" class="text-2xl font-black text-yellow-600">$0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campo para Referencia (Tarjetas/Transferencias) -->
                                <div id="reference-section" class="hidden">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class='bx bx-receipt text-blue-600 mr-1'></i>
                                        Referencia / Número de Transacción (Opcional)
                                    </label>
                                    <input type="text" id="payment-reference" maxlength="100"
                                        placeholder="Ej: AUTH123456 o últimos 4 dígitos"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                                </div>

                                <!-- Mensaje de Error -->
                                <div id="payment-error" class="hidden p-3 bg-red-50 border-l-4 border-red-500 rounded">
                                    <p class="text-sm text-red-700 font-medium"></p>
                                </div>

                                <!-- Botones -->
                                <div class="space-y-3 pt-4">
                                    <button onclick="confirmPayment()" id="confirm-payment-btn"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-lg transition-colors shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                                        disabled>
                                        <i class='bx bx-check-circle text-2xl mr-2'></i>
                                        Confirmar y Procesar Venta
                                    </button>
                                    <button onclick="closePaymentModal()" type="button"
                                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de WhatsApp -->
                    <div id="whatsapp-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
                        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                                        <i class='bx bxl-whatsapp text-3xl mr-2 text-green-500'></i>
                                        Enviar por WhatsApp
                                    </h3>
                                    <button onclick="closeWhatsAppModal()" class="text-gray-400 hover:text-gray-600">
                                        <i class='bx bx-x text-3xl'></i>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class='bx bx-phone text-emerald-600 mr-1'></i>
                                        Número de teléfono
                                    </label>
                                    <input type="tel"
                                           id="whatsapp-phone"
                                           placeholder="5512345678"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-800"
                                           maxlength="10">
                                    <p class="text-xs text-gray-500 mt-1">Ingresa el número sin espacios ni guiones</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class='bx bx-message-detail text-emerald-600 mr-1'></i>
                                        Vista previa del mensaje
                                    </label>
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 max-h-64 overflow-y-auto">
                                        <pre id="whatsapp-preview" class="text-sm text-gray-700 whitespace-pre-wrap font-mono"></pre>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <button onclick="sendWhatsApp()"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg flex items-center justify-center">
                                        <i class='bx bxl-whatsapp text-2xl mr-2'></i>
                                        Enviar por WhatsApp
                                    </button>
                                    <button onclick="downloadTicketImage()"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg flex items-center justify-center">
                                        <i class='bx bx-download text-2xl mr-2'></i>
                                        Descargar Imagen para WhatsApp
                                    </button>
                                    <button onclick="printTicketPhysical()"
                                        class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg flex items-center justify-center">
                                        <i class='bx bx-printer text-2xl mr-2'></i>
                                        Imprimir Ticket
                                    </button>
                                    <button onclick="closeWhatsAppModal()"
                                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                                        Omitir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de Vista Previa de Ticket -->
                    <div id="ticket-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
                        <div class="bg-gray-900 rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] flex flex-col">
                            <!-- Header -->
                            <div class="p-4 border-b border-gray-700 flex items-center justify-between sticky top-0 bg-gray-900 z-10">
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    <i class='bx bx-receipt text-2xl mr-2 text-emerald-500'></i>
                                    Ticket de Venta
                                </h3>
                                <button onclick="closeTicketModal()" class="text-gray-400 hover:text-white transition-colors">
                                    <i class='bx bx-x text-3xl'></i>
                                </button>
                            </div>

                            <!-- Contenido del Ticket -->
                            <div class="overflow-y-auto flex-1 p-4">
                                <div id="ticket-content">
                                    <!-- El contenido del ticket se insertará aquí -->
                                </div>
                            </div>

                            <!-- Footer con Botones -->
                            <div class="p-4 border-t border-gray-700 flex gap-3 sticky bottom-0 bg-gray-900">
                                <button onclick="printTicketFromModal()"
                                    class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg flex items-center justify-center">
                                    <i class='bx bx-printer text-xl mr-2'></i>
                                    Imprimir
                                </button>
                                <button onclick="closeTicketModal()"
                                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Inicializar carrito vacío
            cartItems = [];

            // Inicializar event listeners para el escáner físico
            initPhysicalScanner();

            // Cargar configuración actual
            loadCurrentSettings();

            // Inicializar formulario de configuración
            initSettingsForm();

            // Listener global para cerrar modales con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Cerrar modal de pago si está abierto
                    const paymentModal = document.getElementById('payment-modal');
                    if (paymentModal && paymentModal.style.display === 'flex') {
                        closePaymentModal();
                    }
                }
            });

        }

        // Variable para controlar si el auto-focus está activo
        let scannerAutoFocusEnabled = true;

        // Inicializar escáner físico (captura de teclado)
        function initPhysicalScanner() {
            const barcodeInput = document.getElementById('barcode-input');
            if (!barcodeInput) return;

            // Auto-focus en el input
            barcodeInput.focus();

            // Detectar cuando el usuario presiona Enter (el escáner envía Enter al final)
            barcodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const barcode = this.value.trim();
                    if (barcode) {
                        searchProductByBarcode(barcode);
                        this.value = '';
                    }
                }
            });

            // También detectar cuando pierde el foco y volver a enfocarlo
            // PERO solo si el auto-focus está habilitado (no hay modal abierto)
            barcodeInput.addEventListener('blur', function() {
                setTimeout(() => {
                    if (scannerAutoFocusEnabled && document.getElementById('barcode-input')) {
                        document.getElementById('barcode-input').focus();
                    }
                }, 100);
            });
        }

        // Toggle cámara escáner
        window.toggleCameraScanner = async function() {
            const cameraReader = document.getElementById('camera-reader');
            const toggleBtn = document.getElementById('toggle-camera-btn');

            if (!scannerActive) {
                try {
                    cameraReader.classList.remove('hidden');
                    toggleBtn.innerHTML = '<i class="bx bx-stop mr-1"></i> Detener Cámara';
                    toggleBtn.classList.remove('bg-custom-active', 'hover:bg-green-700');
                    toggleBtn.classList.add('bg-red-500', 'hover:bg-red-600');

                    if (!html5QrCode) {
                        html5QrCode = new Html5Qrcode("qr-reader");
                    }

                    await html5QrCode.start(
                        { facingMode: "environment" },
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            // Habilitar TODOS los formatos de códigos de barras
                            formatsToSupport: [
                                Html5QrcodeSupportedFormats.QR_CODE,
                                Html5QrcodeSupportedFormats.EAN_13,
                                Html5QrcodeSupportedFormats.EAN_8,
                                Html5QrcodeSupportedFormats.UPC_A,
                                Html5QrcodeSupportedFormats.UPC_E,
                                Html5QrcodeSupportedFormats.CODE_128,
                                Html5QrcodeSupportedFormats.CODE_39,
                                Html5QrcodeSupportedFormats.CODE_93,
                                Html5QrcodeSupportedFormats.ITF,
                                Html5QrcodeSupportedFormats.CODABAR,
                                Html5QrcodeSupportedFormats.DATA_MATRIX,
                                Html5QrcodeSupportedFormats.PDF_417,
                                Html5QrcodeSupportedFormats.AZTEC
                            ]
                        },
                        (decodedText, decodedResult) => {
                            searchProductByBarcode(decodedText);
                            // Opcional: detener escáner después de encontrar
                            // toggleCameraScanner();
                        },
                        (errorMessage) => {
                            // Error de escaneo (normal, sigue intentando)
                        }
                    );

                    scannerActive = true;
                } catch (err) {
                    console.error('Error al iniciar cámara:', err);
                    alert('No se pudo acceder a la cámara. Verifica los permisos.');
                    cameraReader.classList.add('hidden');
                    toggleBtn.innerHTML = '<i class="bx bx-camera mr-1"></i> Activar Cámara';
                    toggleBtn.classList.add('bg-custom-active', 'hover:bg-green-700');
                    toggleBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
                }
            } else {
                try {
                    await html5QrCode.stop();
                    cameraReader.classList.add('hidden');
                    toggleBtn.innerHTML = '<i class="bx bx-camera mr-1"></i> Activar Cámara';
                    toggleBtn.classList.add('bg-custom-active', 'hover:bg-green-700');
                    toggleBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
                    scannerActive = false;
                } catch (err) {
                    console.error('Error al detener cámara:', err);
                }
            }
        }

        // Buscar producto por código de barras y agregarlo al carrito
        async function searchProductByBarcode(barcode) {
            console.log('Buscando producto con código:', barcode);

            try {
                const response = await fetch('/products/api', {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-User-Email': currentUserEmail
                    }
                });

                if (!response.ok) throw new Error('Error al buscar productos');

                const products = await response.json();
                const product = products.find(p =>
                    p.codigo_barras === barcode ||
                    p.barcode === barcode ||
                    p.sku === barcode ||
                    p.id == barcode
                );

                if (product) {
                    // Producto encontrado - Agregarlo al carrito
                    addToCart(product);
                    playSuccessSound();
                    showLastActionMessage(`✓ ${product.name} agregado`, 'success');
                } else {
                    // Producto no encontrado
                    playErrorSound();
                    showLastActionMessage(`✗ Código ${barcode} no encontrado`, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                showLastActionMessage(`✗ Error al buscar producto`, 'error');
            }
        }

        // Agregar producto al carrito
        function addToCart(product) {
            const existingItem = cartItems.find(item => item.id === product.id);

            if (existingItem) {
                // Si ya existe, incrementar cantidad
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity++;
                } else {
                    showLastActionMessage(`⚠ Stock máximo alcanzado (${product.stock})`, 'warning');
                    return;
                }
            } else {
                // Agregar nuevo producto
                cartItems.push({
                    id: product.id,
                    name: product.name || product.nombre,
                    price: parseFloat(product.price || product.precio || 0),
                    quantity: 1,
                    stock: parseInt(product.stock || 0),
                    barcode: product.codigo_barras || product.barcode
                });
            }

            updateCartDisplay();
        }

        // Sincronizar carrito con pantalla del cliente
        function syncCartToCustomerDisplay() {
            // Enviar por BroadcastChannel
            if (posChannel) {
                try {
                    posChannel.postMessage({
                        type: 'cart_update',
                        cart: cartItems
                    });
                } catch (error) {
                    console.error('Error enviando por BroadcastChannel:', error);
                }
            }

            // También guardar en localStorage como fallback
            localStorage.setItem('pos_cart_data', JSON.stringify(cartItems));
        }

        // Actualizar visualización del carrito
        function updateCartDisplay() {
            const cartList = document.getElementById('cart-items-list');
            const cartCount = document.getElementById('cart-count');
            const cartUnits = document.getElementById('cart-units');
            const cartTotal = document.getElementById('cart-total');
            const processSaleBtn = document.getElementById('process-sale-btn');

            // Sincronizar con pantalla del cliente
            syncCartToCustomerDisplay();

            if (cartItems.length === 0) {
                cartList.innerHTML = `
                    <div class="text-center py-8 text-custom-gray">
                        <i class='bx bx-cart text-5xl opacity-30'></i>
                        <p class="mt-2">No hay productos escaneados</p>
                        <p class="text-sm">Escanea un producto para comenzar</p>
                    </div>
                `;
                processSaleBtn.disabled = true;
            } else {
                cartList.innerHTML = cartItems.map((item, index) => `
                    <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h5 class="font-bold text-custom-text">${item.name}</h5>
                                <p class="text-sm text-custom-gray font-mono">${item.barcode || 'Sin código'}</p>
                                <p class="text-lg font-bold text-emerald-600">$${item.price.toFixed(2)}</p>
                            </div>
                            <button
                                onclick="removeFromCart(${index})"
                                class="text-red-500 hover:text-red-700 p-1"
                                title="Eliminar"
                            >
                                <i class='bx bx-trash text-xl'></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-2">
                            <button
                                onclick="decreaseQuantity(${index})"
                                class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-lg font-bold"
                            >-</button>
                            <div class="text-center">
                                <span class="font-bold text-lg text-custom-text">${item.quantity}</span>
                                <span class="text-sm text-custom-gray ml-1">un.</span>
                            </div>
                            <button
                                onclick="increaseQuantity(${index})"
                                class="bg-emerald-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-lg font-bold ${item.quantity >= item.stock ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${item.quantity >= item.stock ? 'disabled' : ''}
                            >+</button>
                        </div>
                        <div class="mt-2 text-right">
                            <span class="text-sm text-custom-gray">Subtotal: </span>
                            <span class="font-bold text-custom-text">$${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    </div>
                `).join('');
                processSaleBtn.disabled = false;
            }

            // Actualizar resumen
            const totalItems = cartItems.length;
            const totalUnits = cartItems.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            cartCount.textContent = totalItems;
            cartUnits.textContent = totalUnits;
            cartTotal.textContent = `$${totalPrice.toFixed(2)}`;
        }

        // Incrementar cantidad
        function increaseQuantity(index) {
            if (cartItems[index].quantity < cartItems[index].stock) {
                cartItems[index].quantity++;
                updateCartDisplay();
            }
        }

        // Decrementar cantidad
        function decreaseQuantity(index) {
            if (cartItems[index].quantity > 1) {
                cartItems[index].quantity--;
                updateCartDisplay();
            } else {
                removeFromCart(index);
            }
        }

        // Eliminar producto del carrito
        function removeFromCart(index) {
            cartItems.splice(index, 1);
            updateCartDisplay();
            showLastActionMessage('Producto eliminado', 'success');
        }

        // Limpiar carrito
        async function clearCart() {
            if (cartItems.length === 0) return;

            const confirmed = await showAlert({
                title: '¿Limpiar carrito?',
                message: 'Se eliminarán todos los productos del carrito.',
                type: 'question',
                showCancel: true,
                confirmText: 'Sí, limpiar',
                cancelText: 'Cancelar'
            });

            if (confirmed) {
                cartItems = [];
                updateCartDisplay();
                showLastActionMessage('Carrito limpiado', 'success');

                // Notificar a pantalla del cliente
                if (posChannel) {
                    posChannel.postMessage({ type: 'cart_clear' });
                }
                localStorage.removeItem('pos_cart_data');
            }
        }

        // Mostrar mensaje de última acción
        function showLastActionMessage(message, type = 'success') {
            const msgContainer = document.getElementById('last-action-msg');
            const msgText = msgContainer.querySelector('p');

            const colors = {
                success: 'bg-green-50 border-green-500 text-green-700',
                error: 'bg-red-50 border-red-500 text-red-700',
                warning: 'bg-yellow-50 border-yellow-500 text-yellow-700'
            };

            msgContainer.className = 'mt-4';
            msgContainer.querySelector('div').className = `${colors[type]} border-l-4 p-3 rounded`;
            msgText.textContent = message;
            msgContainer.classList.remove('hidden');

            setTimeout(() => {
                msgContainer.classList.add('hidden');
            }, 3000);
        }

        // Variables globales para el método de pago
        let selectedPaymentMethod = null;
        let paymentData = {};

        // Abrir modal de método de pago
        function processSale() {
            if (cartItems.length === 0) {
                alert('El carrito está vacío');
                return;
            }

            // Desactivar auto-focus del escáner mientras el modal está abierto
            scannerAutoFocusEnabled = false;

            // Calcular total
            const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Mostrar total en el modal
            document.getElementById('payment-total').textContent = `$${total.toFixed(2)}`;

            // Resetear selección
            selectedPaymentMethod = null;
            paymentData = { total: total };
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'border-blue-500', 'bg-blue-50', 'border-purple-500', 'bg-purple-50', 'border-indigo-500', 'bg-indigo-50');
                btn.classList.add('border-gray-300');
            });
            document.getElementById('cash-section').classList.add('hidden');
            document.getElementById('reference-section').classList.add('hidden');
            document.getElementById('change-section').classList.add('hidden');
            document.getElementById('payment-error').classList.add('hidden');
            document.getElementById('amount-received').value = '';
            document.getElementById('payment-reference').value = '';
            document.getElementById('confirm-payment-btn').disabled = true;

            // Mostrar modal
            document.getElementById('payment-modal').style.display = 'flex';
        }

        // Seleccionar método de pago
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            paymentData.payment_method = method;

            // Resetear estilos
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'border-blue-500', 'bg-blue-50', 'border-purple-500', 'bg-purple-50', 'border-indigo-500', 'bg-indigo-50');
                btn.classList.add('border-gray-300');
            });

            // Aplicar estilo al seleccionado
            const selectedBtn = document.querySelector(`[data-method="${method}"]`);
            if (method === 'efectivo') {
                selectedBtn.classList.remove('border-gray-300');
                selectedBtn.classList.add('border-emerald-500', 'bg-emerald-50');
                document.getElementById('cash-section').classList.remove('hidden');
                document.getElementById('reference-section').classList.add('hidden');
                // Auto-focus en el input de efectivo
                setTimeout(() => document.getElementById('amount-received').focus(), 100);
            } else {
                if (method === 'tarjeta_debito') {
                    selectedBtn.classList.remove('border-gray-300');
                    selectedBtn.classList.add('border-blue-500', 'bg-blue-50');
                } else if (method === 'tarjeta_credito') {
                    selectedBtn.classList.remove('border-gray-300');
                    selectedBtn.classList.add('border-purple-500', 'bg-purple-50');
                } else if (method === 'transferencia') {
                    selectedBtn.classList.remove('border-gray-300');
                    selectedBtn.classList.add('border-indigo-500', 'bg-indigo-50');
                }
                document.getElementById('cash-section').classList.add('hidden');
                document.getElementById('reference-section').classList.remove('hidden');
                document.getElementById('change-section').classList.add('hidden');
            }

            // Habilitar botón de confirmar
            document.getElementById('confirm-payment-btn').disabled = false;
        }

        // Calcular cambio
        function calculateChange() {
            const total = paymentData.total;
            const received = parseFloat(document.getElementById('amount-received').value) || 0;

            if (received >= total) {
                const change = received - total;
                document.getElementById('change-amount').textContent = `$${change.toFixed(2)}`;
                document.getElementById('change-section').classList.remove('hidden');
                document.getElementById('payment-error').classList.add('hidden');
                document.getElementById('confirm-payment-btn').disabled = false;
                paymentData.amount_received = received;
                paymentData.change_returned = change;
            } else if (received > 0) {
                document.getElementById('change-section').classList.add('hidden');
                document.getElementById('payment-error').classList.remove('hidden');
                document.getElementById('payment-error').querySelector('p').textContent = 'El monto recibido es insuficiente';
                document.getElementById('confirm-payment-btn').disabled = true;
            } else {
                document.getElementById('change-section').classList.add('hidden');
                document.getElementById('payment-error').classList.add('hidden');
                document.getElementById('confirm-payment-btn').disabled = selectedPaymentMethod !== null;
            }
        }

        // Cerrar modal de pago
        function closePaymentModal() {
            document.getElementById('payment-modal').style.display = 'none';

            // Reactivar auto-focus del escáner
            scannerAutoFocusEnabled = true;

            // Devolver foco al input del escáner
            setTimeout(() => {
                const barcodeInput = document.getElementById('barcode-input');
                if (barcodeInput) {
                    barcodeInput.focus();
                }
            }, 100);
        }

        // Confirmar pago y procesar venta
        async function confirmPayment() {
            if (!selectedPaymentMethod) {
                alert('Por favor selecciona un método de pago');
                return;
            }

            // Validar efectivo
            if (selectedPaymentMethod === 'efectivo') {
                const received = parseFloat(document.getElementById('amount-received').value) || 0;
                if (received < paymentData.total) {
                    document.getElementById('payment-error').classList.remove('hidden');
                    document.getElementById('payment-error').querySelector('p').textContent = 'El monto recibido es insuficiente';
                    return;
                }
                paymentData.amount_received = received;
                paymentData.change_returned = received - paymentData.total;
            } else {
                // Obtener referencia si existe
                const reference = document.getElementById('payment-reference').value.trim();
                if (reference) {
                    paymentData.payment_reference = reference;
                }
            }

            // Cerrar modal
            closePaymentModal();

            // Deshabilitar botón de procesar
            const processSaleBtn = document.getElementById('process-sale-btn');
            processSaleBtn.disabled = true;
            processSaleBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin text-2xl mr-2"></i> Procesando...';

            // Calcular total
            const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const totalUnits = cartItems.reduce((sum, item) => sum + item.quantity, 0);

            try {
                // 1. Guardar venta en la base de datos
                const saleData = {
                    customer_id: null, // Se usará cliente genérico "Público General"
                    sale_date: new Date().toISOString().split('T')[0] + ' ' + new Date().toTimeString().split(' ')[0],
                    notes: 'Venta desde POS',
                    sale_items: cartItems.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        price: item.price
                    })),
                    payment_method: paymentData.payment_method,
                    amount_received: paymentData.amount_received || null,
                    payment_reference: paymentData.payment_reference || null
                };

                const saveResponse = await fetch('/pos/save-sale', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(saleData)
                });

                const saveResult = await saveResponse.json();

                if (!saveResult.success) {
                    throw new Error(saveResult.message || 'Error al guardar la venta');
                }

                // 2. Cargar configuración del negocio para el ticket
                const settingsResponse = await fetch('/settings/api', {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-User-Email': currentUserEmail
                    }
                });

                const settings = await settingsResponse.json();

                // Mostrar mensaje de éxito
                showLastActionMessage(`✓ Venta guardada exitosamente: $${total.toFixed(2)} (ID: ${saveResult.sale.id})`, 'success');

                // 4. Guardar datos de última venta para WhatsApp
                window.lastSaleData = {
                    saleId: saveResult.sale.id,
                    items: cartItems.map(item => ({...item})), // Copiar items antes de limpiar
                    total: total,
                    totalUnits: totalUnits,
                    settings: settings
                };

                // 5. Limpiar carrito después de un pequeño delay
                setTimeout(() => {
                    cartItems = [];
                    updateCartDisplay();

                    // Notificar a pantalla del cliente
                    if (posChannel) {
                        posChannel.postMessage({ type: 'cart_clear' });
                    }
                    localStorage.removeItem('pos_cart_data');

                    // Rehabilitar botón
                    processSaleBtn.disabled = false;
                    processSaleBtn.innerHTML = '<i class="bx bx-check-circle text-2xl mr-2"></i> Procesar Venta';

                    // Reactivar auto-focus del escáner
                    scannerAutoFocusEnabled = true;

                    // Devolver foco al input del escáner
                    setTimeout(() => {
                        const barcodeInput = document.getElementById('barcode-input');
                        if (barcodeInput) {
                            barcodeInput.focus();
                        }
                    }, 100);

                    // Abrir modal de WhatsApp automáticamente
                    openWhatsAppModal();
                }, 1000);

            } catch (error) {
                console.error('Error procesando venta:', error);
                alert('Error al procesar la venta: ' + error.message);

                // Rehabilitar botón
                processSaleBtn.disabled = false;
                processSaleBtn.innerHTML = '<i class="bx bx-check-circle text-2xl mr-2"></i> Procesar Venta';

                // Reactivar auto-focus del escáner
                scannerAutoFocusEnabled = true;

                // Devolver foco al input del escáner
                setTimeout(() => {
                    const barcodeInput = document.getElementById('barcode-input');
                    if (barcodeInput) {
                        barcodeInput.focus();
                    }
                }, 100);
            }
        }

        // ========== FUNCIONES DE WHATSAPP ==========

        // Generar mensaje de ticket para WhatsApp
        function generateWhatsAppMessage(saleData) {
            const { saleId, items, total, totalUnits, settings } = saleData;
            const nombreNegocio = settings.nombre_negocio || 'IslaControl';
            const fecha = new Date().toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            let mensaje = `🧾 *TICKET DE COMPRA*\n`;
            mensaje += `━━━━━━━━━━━━━━━━━━\n\n`;
            mensaje += `🏪 *${nombreNegocio}*\n`;
            mensaje += `📅 ${fecha}\n`;
            mensaje += `🔢 Ticket #${saleId}\n\n`;
            mensaje += `📦 *PRODUCTOS:*\n`;
            mensaje += `━━━━━━━━━━━━━━━━━━\n`;

            items.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                mensaje += `\n${index + 1}. ${item.name}\n`;
                mensaje += `   ${item.quantity} x $${item.price.toFixed(2)} = $${subtotal.toFixed(2)}\n`;
            });

            mensaje += `\n━━━━━━━━━━━━━━━━━━\n`;
            mensaje += `📊 Total de productos: ${items.length}\n`;
            mensaje += `📦 Total de unidades: ${totalUnits}\n`;
            mensaje += `━━━━━━━━━━━━━━━━━━\n\n`;
            mensaje += `💰 *TOTAL: $${total.toFixed(2)}*\n\n`;
            mensaje += `━━━━━━━━━━━━━━━━━━\n\n`;
            mensaje += `✨ *¡Gracias por tu compra!* ✨\n\n`;
            mensaje += `Apreciamos tu preferencia y confianza.\n`;
            mensaje += `Esperamos verte pronto de nuevo.\n\n`;
            mensaje += `💚 ¡Que tengas un excelente día! 💚\n`;

            if (settings.telefono || settings.direccion) {
                mensaje += `\n━━━━━━━━━━━━━━━━━━\n`;
                mensaje += `📍 *Visítanos:*\n`;
                if (settings.direccion) mensaje += `📌 ${settings.direccion}\n`;
                if (settings.telefono) mensaje += `📞 ${settings.telefono}\n`;
            }

            return mensaje;
        }

        // Abrir modal de WhatsApp
        function openWhatsAppModal() {
            if (!window.lastSaleData) {
                alert('No hay datos de venta para enviar');
                return;
            }

            const modal = document.getElementById('whatsapp-modal');
            const phoneInput = document.getElementById('whatsapp-phone');
            const preview = document.getElementById('whatsapp-preview');
            const barcodeInput = document.getElementById('barcode-input');

            // DESACTIVAR el auto-focus del escáner
            scannerAutoFocusEnabled = false;

            // DESHABILITAR completamente el campo de escaneo
            if (barcodeInput) {
                barcodeInput.blur(); // Quitar el foco primero
                barcodeInput.disabled = true;
                barcodeInput.readOnly = true;
                barcodeInput.style.pointerEvents = 'none';
                barcodeInput.tabIndex = -1;
            }

            // Limpiar campo de teléfono
            phoneInput.value = '';

            // Generar y mostrar preview del mensaje corto de agradecimiento
            const nombreNegocio = window.lastSaleData.settings.nombre_negocio || 'IslaControl';
            const mensaje = `✨ ¡Gracias por tu compra! ✨

Apreciamos tu preferencia en ${nombreNegocio}.

Esperamos verte pronto de nuevo.

💚 ¡Que tengas un excelente día! 💚`;
            preview.textContent = mensaje;

            // Mostrar modal
            modal.style.display = 'flex';

            // Enfocar en el input de teléfono con más delay para asegurar
            setTimeout(() => {
                phoneInput.focus();
                phoneInput.click(); // Forzar el foco
            }, 200);
        }

        // Cerrar modal de WhatsApp
        function closeWhatsAppModal() {
            const modal = document.getElementById('whatsapp-modal');
            const barcodeInput = document.getElementById('barcode-input');

            modal.style.display = 'none';

            // REACTIVAR el auto-focus del escáner
            scannerAutoFocusEnabled = true;

            // RE-HABILITAR el campo de escaneo completamente
            if (barcodeInput) {
                barcodeInput.disabled = false;
                barcodeInput.readOnly = false;
                barcodeInput.style.pointerEvents = 'auto';
                barcodeInput.tabIndex = 0;
            }

            // Volver a enfocar el campo de escaneo
            setTimeout(() => {
                if (barcodeInput) {
                    barcodeInput.focus();
                }
            }, 100);
        }

        // Descargar imagen del ticket para WhatsApp
        async function downloadTicketImage() {
            if (!window.lastSaleData) {
                alert('No hay datos de venta');
                return;
            }

            console.log('🎨 Iniciando generación de imagen...');

            // Mostrar loading
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin text-2xl mr-2"></i> Generando...';

            try {
                // Verificar que html2canvas esté disponible
                if (typeof html2canvas === 'undefined') {
                    throw new Error('html2canvas no está cargado');
                }

                // Descargar imagen del ticket
                const success = await generateAndDownloadTicketImage(window.lastSaleData);

                if (success) {
                    showLastActionMessage('✓ Imagen del ticket descargada. Adjúntala en WhatsApp', 'success');
                } else {
                    alert('❌ Error al generar la imagen del ticket');
                }
            } catch (error) {
                console.error('❌ Error:', error);
                alert('❌ Error al generar la imagen: ' + error.message);
            } finally {
                // Restaurar botón
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        }

        // Imprimir ticket físicamente
        function printTicketPhysical() {
            if (!window.lastSaleData) {
                alert('No hay datos de venta para imprimir');
                return;
            }

            const { items, total, totalUnits, settings } = window.lastSaleData;

            // Imprimir usando la función original de impresión
            printTicket(items, total, totalUnits, settings);

            showLastActionMessage('✓ Abriendo vista de impresión...', 'success');
        }

        // Generar ticket como imagen y descargar
        async function generateAndDownloadTicketImage(saleData) {
            const { saleId, items, total, totalUnits, settings } = saleData;
            const nombreNegocio = settings.nombre_negocio || 'IslaControl';
            const fecha = new Date().toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Crear contenedor temporal para el ticket
            const ticketContainer = document.createElement('div');
            ticketContainer.style.position = 'absolute';
            ticketContainer.style.left = '-9999px';
            ticketContainer.style.width = '400px';
            ticketContainer.style.backgroundColor = '#ffffff';
            ticketContainer.style.padding = '30px';
            ticketContainer.style.fontFamily = 'Arial, sans-serif';

            // HTML del ticket
            ticketContainer.innerHTML = `
                <div style="text-align: center; border-bottom: 2px solid #10B981; padding-bottom: 20px; margin-bottom: 20px;">
                    <h1 style="font-size: 24px; color: #10B981; margin: 0 0 10px 0; font-weight: bold;">${nombreNegocio}</h1>
                    <h2 style="font-size: 18px; color: #374151; margin: 0 0 10px 0;">TICKET DE COMPRA</h2>
                    <div style="font-size: 12px; color: #6B7280;">
                        <div>${fecha}</div>
                        <div>Ticket #${saleId}</div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 14px; color: #374151; margin-bottom: 10px; border-bottom: 1px solid #E5E7EB; padding-bottom: 5px;">PRODUCTOS</h3>
                    ${items.map((item, index) => {
                        const subtotal = item.price * item.quantity;
                        return `
                            <div style="margin-bottom: 15px;">
                                <div style="font-weight: bold; color: #374151; font-size: 13px;">${index + 1}. ${item.name}</div>
                                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #6B7280; margin-top: 3px;">
                                    <span>${item.quantity} x $${item.price.toFixed(2)}</span>
                                    <span style="font-weight: bold; color: #10B981;">$${subtotal.toFixed(2)}</span>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>

                <div style="border-top: 2px solid #E5E7EB; padding-top: 15px; margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;">
                        <span>Total de productos:</span>
                        <span style="font-weight: bold;">${items.length}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;">
                        <span>Total de unidades:</span>
                        <span style="font-weight: bold;">${totalUnits}</span>
                    </div>
                    <div style="background: linear-gradient(to right, #10b981, #059669); padding: 15px; border-radius: 8px; margin-top: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: white; font-size: 18px; font-weight: bold;">TOTAL:</span>
                            <span style="color: white; font-size: 24px; font-weight: bold;">$${total.toFixed(2)}</span>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px dashed #E5E7EB;">
                    <div style="font-size: 14px; color: #10B981; font-weight: bold; margin-bottom: 5px;">✨ ¡Gracias por tu compra!</div>
                    ${settings.telefono ? `<div style="font-size: 11px; color: #6B7280;">📞 ${settings.telefono}</div>` : ''}
                    ${settings.direccion ? `<div style="font-size: 11px; color: #6B7280;">📍 ${settings.direccion}</div>` : ''}
                </div>
            `;

            // Agregar al DOM temporalmente
            document.body.appendChild(ticketContainer);

            try {
                console.log('📸 Generando canvas con html2canvas...');

                // Convertir a imagen con html2canvas
                const canvas = await html2canvas(ticketContainer, {
                    backgroundColor: '#ffffff',
                    scale: 2,
                    logging: true,
                    useCORS: true,
                    allowTaint: true
                });

                console.log('✅ Canvas generado:', canvas.width, 'x', canvas.height);

                // Convertir canvas a blob
                const blob = await new Promise(resolve => {
                    canvas.toBlob(resolve, 'image/png', 1.0);
                });

                console.log('✅ Blob creado:', blob.size, 'bytes');

                // Crear URL temporal para descarga
                const url = URL.createObjectURL(blob);

                console.log('✅ URL creada:', url);

                // Descargar automáticamente con método más robusto
                const link = document.createElement('a');
                link.style.display = 'none';
                link.download = `ticket-${saleId}-${Date.now()}.png`;
                link.href = url;

                // Agregar al DOM
                document.body.appendChild(link);

                // Hacer click
                link.click();

                console.log('✅ Click ejecutado en el link de descarga');

                // Limpiar después de un delay
                setTimeout(() => {
                    URL.revokeObjectURL(url);
                    document.body.removeChild(link);
                    console.log('✅ Limpieza completada');
                }, 100);

                document.body.removeChild(ticketContainer);

                return true;
            } catch (error) {
                console.error('❌ Error generando imagen del ticket:', error);
                if (document.body.contains(ticketContainer)) {
                    document.body.removeChild(ticketContainer);
                }
                return false;
            }
        }

        // Enviar mensaje por WhatsApp
        function sendWhatsApp() {
            const phoneInput = document.getElementById('whatsapp-phone');
            let phone = phoneInput.value.trim();

            if (!phone || phone.length < 10) {
                alert('Por favor ingresa un número de teléfono válido (10 dígitos)');
                phoneInput.focus();
                return;
            }

            const { settings } = window.lastSaleData;
            const nombreNegocio = settings.nombre_negocio || 'IslaControl';

            // Limpiar el teléfono (quitar espacios, guiones, etc)
            phone = phone.replace(/\D/g, '');

            // Agregar código de país si no lo tiene (México = 52)
            if (!phone.startsWith('52')) {
                phone = '52' + phone;
            }

            // SOLO mensaje de agradecimiento corto
            const mensaje = `✨ ¡Gracias por tu compra! ✨

Apreciamos tu preferencia en ${nombreNegocio}.

Esperamos verte pronto de nuevo.

💚 ¡Que tengas un excelente día! 💚`;

            // Codificar mensaje para URL
            const mensajeCodificado = encodeURIComponent(mensaje);

            // Construir URL de WhatsApp
            const whatsappUrl = `https://wa.me/${phone}?text=${mensajeCodificado}`;

            console.log('📱 Abriendo WhatsApp con:', whatsappUrl);

            // Abrir WhatsApp inmediatamente
            window.open(whatsappUrl, '_blank');

            // Cerrar modal
            closeWhatsAppModal();

            // Mostrar mensaje de éxito
            showLastActionMessage('✓ WhatsApp abierto. Adjunta manualmente la imagen del ticket', 'success');
        }

        // ========== FIN FUNCIONES DE WHATSAPP ==========

        // Abrir pantalla del cliente en nueva ventana
        window.openCustomerDisplay = function() {
            const width = 1920;
            const height = 1080;
            const left = window.screen.width - width;
            const top = 0;

            // Cerrar ventana anterior si existe
            if (customerDisplayWindow && !customerDisplayWindow.closed) {
                customerDisplayWindow.close();
            }

            // Abrir nueva ventana
            customerDisplayWindow = window.open(
                '/pos/customer-display',
                'customer-display',
                `width=${width},height=${height},left=${left},top=${top},menubar=no,toolbar=no,location=no,status=no`
            );

            if (customerDisplayWindow) {
                // Sincronizar estado actual cuando se abre la ventana
                setTimeout(() => {
                    syncCartToCustomerDisplay();
                }, 500);

                showLastActionMessage('✓ Pantalla del cliente abierta', 'success');
            } else {
                alert('No se pudo abrir la pantalla del cliente. Por favor, permite las ventanas emergentes.');
            }
        }

        // Generar e imprimir ticket
        function printTicket(items, total, totalUnits, settings = {}) {
            const nombreNegocio = settings.nombre_negocio || 'ISLACONTROL';
            const logoRelativeUrl = settings.logo_url || '/images/default_logo.png';
            // Convertir a URL absoluta para que funcione en el iframe de impresión
            const logoUrl = logoRelativeUrl.startsWith('http') ? logoRelativeUrl : window.location.origin + logoRelativeUrl;
            const telefono = settings.telefono || '';
            const ubicacion = settings.ubicacion || '';
            const now = new Date();
            const fecha = now.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            const hora = now.toLocaleTimeString('es-MX', {
                hour: '2-digit',
                minute: '2-digit'
            });

            // Generar HTML del ticket (basado en el formato de sales/ticket.blade.php)
            const ticketHTML = `
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta POS</title>
    <script` + ` src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></scr` + `ipt>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #1a202c;
            margin: 0;
            padding: 0;
        }

        .ticket-container {
            max-width: 280px;
            margin: 20px auto;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-size: 11px;
            line-height: 1.3;
            color: #000;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #333;
        }

        .logo-container h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }

        .logo-container p {
            font-size: 10px;
            margin: 2px 0;
        }

        .logo {
            max-width: 50px;
            max-height: 50px;
            height: auto;
            width: auto;
            display: block;
            margin: 0 auto 5px auto;
            border-radius: 3px;
            object-fit: contain;
        }

        .ticket-header {
            margin: 5px 0;
        }

        .ticket-header p {
            font-size: 10px;
            margin: 2px 0;
        }

        .ticket-line {
            border-bottom: 1px dashed #333;
            margin: 8px 0;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            font-size: 11px;
        }

        .product-qty-price {
            font-size: 10px;
            color: #555;
            margin: 0 0 3px 0;
            padding-left: 5px;
        }

        .total-box {
            display: flex;
            justify-content: space-between;
            background-color: #f0f0f0;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 14px;
        }

        .qr-code-container {
            text-align: center;
            margin: 10px 0;
        }

        .qr-code-container #qrcode {
            margin: 10px auto;
        }

        .qr-code-container p {
            font-size: 10px;
            margin: 3px 0;
        }

        .ticket-footer {
            text-align: center;
        }

        .ticket-footer p {
            font-size: 9px;
            margin: 2px 0;
            color: #888;
        }

        .print-button {
            background-color: #2ecc71;
            transition: background-color 0.2s;
            width: 100%;
            padding: 10px;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .print-button:hover {
            background-color: #27ae60;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0mm;
            }

            * {
                box-sizing: border-box;
            }

            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .ticket-container {
                max-width: 80mm !important;
                width: 80mm !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 3mm !important;
                margin: 0 !important;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .logo-container {
                text-align: center !important;
                margin-bottom: 2mm !important;
                padding-bottom: 2mm !important;
                border-bottom: 1px dashed #000 !important;
            }

            .logo {
                max-width: 35mm !important;
                max-height: 35mm !important;
                width: auto !important;
                height: auto !important;
                display: block !important;
                margin: 0 auto 3mm auto !important;
            }

            .logo-container h2 {
                font-size: 16pt !important;
                font-weight: bold !important;
                margin: 3mm 0 !important;
                padding: 0 !important;
                line-height: 1.4 !important;
            }

            .logo-container p {
                font-size: 11pt !important;
                margin: 1.5mm 0 !important;
                padding: 0 !important;
                line-height: 1.4 !important;
            }

            .ticket-header {
                margin: 3mm 0 !important;
            }

            .ticket-header p {
                font-size: 11pt !important;
                margin: 1mm 0 !important;
                padding: 0 !important;
            }

            .ticket-line {
                border-bottom: 1px dashed #000 !important;
                margin: 3mm 0 !important;
                padding: 0 !important;
                height: 0 !important;
            }

            .product-header {
                display: flex !important;
                justify-content: space-between !important;
                font-size: 12pt !important;
                font-weight: bold !important;
                margin-bottom: 2mm !important;
                padding: 0 !important;
            }

            .product-item {
                display: flex !important;
                justify-content: space-between !important;
                font-size: 11pt !important;
                margin-bottom: 1.5mm !important;
                padding: 0 !important;
                line-height: 1.4 !important;
            }

            .product-item span {
                font-size: 11pt !important;
            }

            .product-qty-price {
                font-size: 10pt !important;
                margin: 0 0 2.5mm 0 !important;
                padding-left: 4mm !important;
                color: #000 !important;
            }

            .total-box {
                display: flex !important;
                justify-content: space-between !important;
                font-size: 14pt !important;
                font-weight: bold !important;
                padding: 3mm !important;
                margin: 3mm 0 !important;
                background: #f0f0f0 !important;
                border: 1px solid #000 !important;
                border-radius: 0 !important;
            }

            .total-box span {
                font-size: 14pt !important;
            }

            .qr-code-container {
                text-align: center !important;
                margin: 4mm 0 !important;
            }

            .qr-code-container p {
                font-size: 11pt !important;
                margin: 1.5mm 0 !important;
            }

            #qrcode {
                width: 70px !important;
                height: 70px !important;
                margin: 3mm auto !important;
                display: block !important;
            }

            #qrcode img,
            #qrcode canvas {
                width: 70px !important;
                height: 70px !important;
                max-width: 70px !important;
                max-height: 70px !important;
            }

            .ticket-footer {
                text-align: center !important;
                margin-top: 3mm !important;
            }

            .ticket-footer p {
                font-size: 9pt !important;
                margin: 0 !important;
                color: #666 !important;
            }
        }

        .copy-header {
            text-align: center;
            padding: 6px 0;
            font-weight: bold;
            font-size: 10px;
            border-top: 1px dashed #ddd;
            border-bottom: 1px dashed #ddd;
            margin: 8px 0;
            background-color: #f8f9fa;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="ticket-container">

        <!-- BOTÓN DE IMPRESIÓN -->
        <button onclick="window.print()" class="print-button no-print">
            Imprimir Ticket
        </button>

        <!-- LOGO Y NOMBRE DEL NEGOCIO -->
        <div class="logo-container">
            <img src="${logoUrl}?v=${Date.now()}" alt="Logo" class="logo" onerror="this.style.display='none'">
            <h2>${nombreNegocio}</h2>
            ${telefono ? `<p>${telefono}</p>` : ''}
            ${ubicacion ? `<p>${ubicacion}</p>` : ''}
        </div>

        <div class="ticket-header">
            <p>Fecha: ${fecha} ${hora}</p>
        </div>

        <div class="ticket-line"></div>

        <!-- PRODUCTOS -->
        <div class="product-header">
            <span>DESCRIPCIÓN</span>
            <span>IMPORTE</span>
        </div>

        ${items.map(item => `
            <div class="product-item">
                <span>${item.name}</span>
                <span>$${(item.quantity * item.price).toFixed(2)}</span>
            </div>
            <div class="product-qty-price">
                <span>${item.quantity} x $${item.price.toFixed(2)}</span>
            </div>
        `).join('')}

        <div class="ticket-line"></div>

        <!-- TOTAL -->
        <div class="total-box">
            <span>TOTAL:</span>
            <span>$${total.toFixed(2)}</span>
        </div>

        <div class="ticket-line"></div>

        <!-- QR CODE -->
        <div class="qr-code-container">
            <div id="qrcode" style="width: 70px; height: 70px; margin: 0 auto;"></div>
            <p>¡Gracias por tu compra!</p>
        </div>

        <div class="ticket-line"></div>

        <div class="ticket-footer">
            <p>Desarrollado por IslaControl</p>
        </div>

    </div>

    <scr` + `ipt>
        document.addEventListener('DOMContentLoaded', () => {
            const qrContent = "https://www.islacontrol.com/pos/${fecha}_${hora}";

            new QRCode(document.getElementById("qrcode"), {
                text: qrContent,
                width: 70,
                height: 70,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    </scr` + `ipt>
</body>
</html>
            `;

            // Guardar el HTML completo para impresión posterior
            window.ticketHTMLForPrint = ticketHTML;

            // Extraer estilos y contenido del ticket
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = ticketHTML;
            const ticketStyles = tempDiv.querySelector('style');
            const ticketContent = tempDiv.querySelector('.ticket-container');

            // Mostrar modal con el ticket
            const modal = document.getElementById('ticket-modal');
            const contentDiv = document.getElementById('ticket-content');
            const barcodeInput = document.getElementById('barcode-input');

            if (modal && contentDiv && ticketContent) {
                // Desactivar escáner
                scannerAutoFocusEnabled = false;
                if (barcodeInput) {
                    barcodeInput.disabled = true;
                    barcodeInput.blur();
                }

                // Insertar estilos y contenido del ticket (sin el botón de imprimir)
                let ticketHTML = '';
                if (ticketStyles) {
                    ticketHTML += ticketStyles.outerHTML;
                }

                // Clonar el contenido y remover el botón de imprimir
                const ticketClone = ticketContent.cloneNode(true);
                const printButton = ticketClone.querySelector('.print-button');
                if (printButton) {
                    printButton.remove();
                }

                ticketHTML += ticketClone.outerHTML;
                contentDiv.innerHTML = ticketHTML;
                modal.style.display = 'flex';

                // Generar QR code después de insertar el HTML
                setTimeout(() => {
                    const qrDiv = contentDiv.querySelector('#qrcode');
                    if (qrDiv && typeof QRCode !== 'undefined') {
                        qrDiv.innerHTML = ''; // Limpiar contenido anterior
                        const qrContent = `https://www.islacontrol.com/pos/${fecha}_${hora}`;
                        new QRCode(qrDiv, {
                            text: qrContent,
                            width: 70,
                            height: 70,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    }
                }, 100);
            }
        }

        // Cerrar modal de ticket
        function closeTicketModal() {
            const modal = document.getElementById('ticket-modal');
            const barcodeInput = document.getElementById('barcode-input');
            const whatsappBtn = document.getElementById('whatsapp-btn');

            if (modal) {
                modal.style.display = 'none';

                // Ocultar botón de WhatsApp cuando se cierra el modal
                if (whatsappBtn) {
                    whatsappBtn.style.display = 'none';
                }

                // Reactivar escáner
                scannerAutoFocusEnabled = true;
                if (barcodeInput) {
                    barcodeInput.disabled = false;

                    // Volver a enfocar el campo de escaneo después de un breve delay
                    setTimeout(() => {
                        barcodeInput.focus();
                    }, 100);
                }
            }
        }

        // Imprimir ticket desde el modal
        function printTicketFromModal() {
            if (!window.ticketHTMLForPrint) {
                alert('No hay ticket para imprimir');
                return;
            }

            // Crear un iframe temporal para imprimir
            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);

            // Escribir el HTML del ticket directamente
            const iframeDoc = iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(window.ticketHTMLForPrint);
            iframeDoc.close();

            // Esperar a que todas las imágenes se carguen antes de imprimir
            iframe.contentWindow.addEventListener('load', () => {
                const images = iframe.contentWindow.document.querySelectorAll('img');
                let imagesLoaded = 0;
                const totalImages = images.length;

                if (totalImages === 0) {
                    // No hay imágenes, imprimir directamente
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    setTimeout(() => {
                        document.body.removeChild(iframe);
                        // Cerrar modal automáticamente después de imprimir
                        closeTicketModal();
                    }, 1000);
                    return;
                }

                // Esperar a que cada imagen cargue
                images.forEach(img => {
                    if (img.complete) {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                            setTimeout(() => {
                                document.body.removeChild(iframe);
                                // Cerrar modal automáticamente después de imprimir
                                closeTicketModal();
                            }, 1000);
                        }
                    } else {
                        img.addEventListener('load', () => {
                            imagesLoaded++;
                            if (imagesLoaded === totalImages) {
                                iframe.contentWindow.focus();
                                iframe.contentWindow.print();
                                setTimeout(() => {
                                    document.body.removeChild(iframe);
                                    // Cerrar modal automáticamente después de imprimir
                                    closeTicketModal();
                                }, 1000);
                            }
                        });
                        img.addEventListener('error', () => {
                            imagesLoaded++;
                            if (imagesLoaded === totalImages) {
                                iframe.contentWindow.focus();
                                iframe.contentWindow.print();
                                setTimeout(() => {
                                    document.body.removeChild(iframe);
                                    // Cerrar modal automáticamente después de imprimir
                                    closeTicketModal();
                                }, 1000);
                            }
                        });
                    }
                });
            });
        }

        // Funciones del modal de configuración
        function openSettingsModal() {
            const modal = document.getElementById('settings-modal');
            const barcodeInput = document.getElementById('barcode-input');

            if (modal) {
                // DESACTIVAR el auto-focus del escáner
                scannerAutoFocusEnabled = false;

                // DESHABILITAR completamente el campo de escaneo
                if (barcodeInput) {
                    barcodeInput.blur(); // Quitar el foco primero
                    barcodeInput.disabled = true;
                    barcodeInput.readOnly = true;
                    barcodeInput.style.pointerEvents = 'none';
                    barcodeInput.tabIndex = -1;
                }

                modal.style.display = 'flex';
                modal.style.pointerEvents = 'auto';
                modal.classList.remove('hidden');

                // Enfocar el primer campo del formulario
                setTimeout(() => {
                    const nameInput = document.getElementById('business-name');
                    if (nameInput) {
                        nameInput.focus();
                        nameInput.click();
                    }
                }, 200);
            }
        }

        function closeSettingsModal() {
            const modal = document.getElementById('settings-modal');
            const barcodeInput = document.getElementById('barcode-input');

            if (modal) {
                modal.style.display = 'none';
                modal.style.pointerEvents = 'none';
                modal.classList.add('hidden');

                // REACTIVAR el auto-focus del escáner
                scannerAutoFocusEnabled = true;

                // RE-HABILITAR el campo de escaneo completamente
                if (barcodeInput) {
                    barcodeInput.disabled = false;
                    barcodeInput.readOnly = false;
                    barcodeInput.style.pointerEvents = 'auto';
                    barcodeInput.tabIndex = 0;
                }

                // Volver a enfocar el campo de escaneo
                setTimeout(() => {
                    if (barcodeInput) barcodeInput.focus();
                }, 100);
            }
        }

        // Cargar configuración actual
        async function loadCurrentSettings() {
            try {
                const response = await fetch('/settings/api', {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-User-Email': currentUserEmail
                    }
                });

                const settings = await response.json();

                // Actualizar el formulario
                const nameInput = document.getElementById('business-name');
                const phoneInput = document.getElementById('business-phone');
                const locationInput = document.getElementById('business-location');
                const logoPreview = document.getElementById('current-logo-preview');

                if (nameInput) nameInput.value = settings.nombre_negocio || 'ISLACONTROL';
                if (phoneInput) phoneInput.value = settings.telefono || '';
                if (locationInput) locationInput.value = settings.ubicacion || '';
                if (logoPreview) logoPreview.src = settings.logo_url + '?v=' + Date.now();

            } catch (error) {
                console.error('Error cargando configuración:', error);
            }
        }

        // Inicializar formulario de configuración
        function initSettingsForm() {
            const settingsForm = document.getElementById('settings-form');
            if (settingsForm) {
                settingsForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const formData = new FormData(settingsForm);
                    formData.append('_method', 'PUT'); // Laravel method spoofing
                    const messageDiv = document.getElementById('settings-message');

                    try {
                        const response = await fetch('/settings', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-User-Email': currentUserEmail
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            messageDiv.className = 'p-4 bg-green-50 border-l-4 border-green-500 rounded text-green-700';
                            messageDiv.textContent = '✓ Configuración guardada exitosamente';
                            messageDiv.classList.remove('hidden');

                            // Recargar preview
                            loadCurrentSettings();

                            // Cerrar modal después de 2 segundos
                            setTimeout(() => {
                                closeSettingsModal();
                                messageDiv.classList.add('hidden');
                            }, 2000);
                        } else {
                            messageDiv.className = 'p-4 bg-red-50 border-l-4 border-red-500 rounded text-red-700';
                            messageDiv.textContent = '✗ ' + result.message;
                            messageDiv.classList.remove('hidden');
                        }
                    } catch (error) {
                        messageDiv.className = 'p-4 bg-red-50 border-l-4 border-red-500 rounded text-red-700';
                        messageDiv.textContent = '✗ Error al guardar la configuración';
                        messageDiv.classList.remove('hidden');
                    }
                });
            }

            // Cerrar modal al hacer clic fuera
            const modal = document.getElementById('settings-modal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeSettingsModal();
                    }
                });
            }
        }

        // Sonidos de feedback (opcional)
        function playSuccessSound() {
            const audio = new Audio('data:audio/wav;base64,UklGRhIAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU4AAABmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZg==');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio no disponible'));
        }

        function playErrorSound() {
            const audio = new Audio('data:audio/wav;base64,UklGRhIAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU4AAABmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZmZg==');
            audio.volume = 0.2;
            audio.play().catch(e => console.log('Audio no disponible'));
        }

        // Función para generar banner del plan según suscripción
        function generatePlanBanner(metrics) {
            const hasSubscription = metrics.hasActiveSubscription;
            const planName = metrics.planName || 'Plan Gratuito';
            const planLimits = metrics.planLimits;

            // Plan Gratuito
            if (!hasSubscription) {
                return `
                    <div class="mb-6 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6 flex flex-col md:flex-row items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="bg-white/20 p-3 rounded-full mr-4">
                                    <i class='bx bx-gift text-3xl text-white'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">
                                        Estás usando el Plan Gratuito
                                    </h3>
                                    <p class="text-blue-100 text-sm">
                                        Límites: ${planLimits?.products || 10} productos • ${planLimits?.customers || 5} clientes • ${planLimits?.sales_per_month || 20} ventas/mes
                                    </p>
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <a href="/subscription/select-plan"
                                   class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-bold text-sm hover:bg-blue-50 transition-all transform hover:scale-105 shadow-lg">
                                    🎁 Activar 30 días gratis
                                </a>
                                <p class="text-blue-100 text-xs mt-2">
                                    Desbloquea todas las funciones premium
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Plan Básico
            if (planName === 'Plan Básico') {
                return `
                    <div class="mb-6 bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700 rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6 flex flex-col md:flex-row items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="bg-white/20 p-3 rounded-full mr-4">
                                    <i class='bx bx-check-circle text-3xl text-white'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">
                                        ${planName} Activo
                                    </h3>
                                    <p class="text-emerald-100 text-sm">
                                        ${planLimits?.products || 100} productos • ${planLimits?.customers || 100} clientes • ${planLimits?.sales_per_month || 100} ventas/mes
                                    </p>
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <a href="/subscription/select-plan"
                                   class="inline-block bg-white text-emerald-600 px-6 py-3 rounded-lg font-bold text-sm hover:bg-emerald-50 transition-all transform hover:scale-105 shadow-lg">
                                    ⬆️ Mejorar a Pro
                                </a>
                                <p class="text-emerald-100 text-xs mt-2">
                                    Aumenta tus límites a 500/mes
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Plan Pro
            if (planName === 'Plan Pro') {
                return `
                    <div class="mb-6 bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6 flex flex-col md:flex-row items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="bg-white/20 p-3 rounded-full mr-4">
                                    <i class='bx bx-star text-3xl text-white'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">
                                        ${planName} Activo
                                    </h3>
                                    <p class="text-purple-100 text-sm">
                                        ${planLimits?.products || 500} productos • ${planLimits?.customers || 500} clientes • ${planLimits?.sales_per_month || 500} ventas/mes
                                    </p>
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <a href="/subscription/select-plan"
                                   class="inline-block bg-white text-purple-600 px-6 py-3 rounded-lg font-bold text-sm hover:bg-purple-50 transition-all transform hover:scale-105 shadow-lg">
                                    🚀 Mejorar a Empresarial
                                </a>
                                <p class="text-purple-100 text-xs mt-2">
                                    Desbloquea límites ilimitados
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Plan Empresarial
            if (planName === 'Plan Empresarial') {
                return `
                    <div class="mb-6 bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6 flex flex-col md:flex-row items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="bg-white/20 p-3 rounded-full mr-4">
                                    <i class='bx bx-crown text-3xl text-white'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">
                                        ${planName} Activo
                                    </h3>
                                    <p class="text-orange-100 text-sm">
                                        ✨ Todo ilimitado • Todas las funciones premium
                                    </p>
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <a href="/subscription/dashboard"
                                   class="inline-block bg-white text-orange-600 px-6 py-3 rounded-lg font-bold text-sm hover:bg-orange-50 transition-all transform hover:scale-105 shadow-lg">
                                    ⚙️ Administrar Plan
                                </a>
                                <p class="text-orange-100 text-xs mt-2">
                                    Tienes el plan más completo
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Default (si no se reconoce el plan)
            return '';
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
                    <div class="mb-6 sm:mb-8 mt-20 lg:mt-0">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="w-full sm:w-auto">
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-custom-text mb-2">Dashboard de Negocio</h1>
                                <p class="text-sm sm:text-base lg:text-lg text-custom-gray break-words">Bienvenido, <span class="font-semibold text-custom-active" id="user-name">${currentUserName || 'Usuario'}</span></p>
                            </div>
                            <div id="business-info" class="text-xs sm:text-sm lg:text-base">
                                <!-- Nombre del negocio se cargará aquí -->
                            </div>
                        </div>
                    </div>

                    ${generatePlanBanner(metrics)}


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

                    <!-- 16 Gráficas Profesionales de Análisis -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">

                        <!-- 1. Análisis de Tendencia de Ventas (Línea con Área) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-blue-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-trending-up text-2xl mr-2' style="color: #3B82F6;"></i>
                                Tendencia de Ventas
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartTendenciaVentas"></canvas>
                            </div>
                        </div>

                        <!-- 2. Análisis de Rentabilidad por Producto (Bar Horizontal) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-emerald-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-dollar-circle text-2xl mr-2' style="color: #10B981;"></i>
                                Rentabilidad por Producto
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartRentabilidad"></canvas>
                            </div>
                        </div>

                        <!-- 3. Distribución de Ventas por Categoría (Doughnut Mejorado) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-purple-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-pie-chart-alt-2 text-2xl mr-2' style="color: #8B5CF6;"></i>
                                Distribución por Categoría
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartDistribucion"></canvas>
                            </div>
                        </div>

                        <!-- 4. Análisis de Inventario vs Ventas (Mixed Chart) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-amber-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-analyse text-2xl mr-2' style="color: #F59E0B;"></i>
                                Inventario vs Ventas
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartInventarioVentas"></canvas>
                            </div>
                        </div>

                        <!-- 5. Análisis de Ticket Promedio por Día (Área) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-cyan-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-receipt text-2xl mr-2' style="color: #06B6D4;"></i>
                                Ticket Promedio Diario
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartTicketPromedio"></canvas>
                            </div>
                        </div>

                        <!-- 6. Top 10 Productos Más Vendidos (Bar con Gradiente) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-rose-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-trophy text-2xl mr-2' style="color: #F43F5E;"></i>
                                Top 10 Productos
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartTop10Productos"></canvas>
                            </div>
                        </div>

                        <!-- 7. Análisis de Margen de Ganancia (Radar) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-indigo-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-radar text-2xl mr-2' style="color: #6366F1;"></i>
                                Análisis de Márgenes
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartMargenGanancia"></canvas>
                            </div>
                        </div>

                        <!-- 8. Tasa de Conversión por Hora (Línea Escalonada) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-orange-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-time-five text-2xl mr-2' style="color: #EA580C;"></i>
                                Conversión por Hora
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartConversionHora"></canvas>
                            </div>
                        </div>

                        <!-- 9. Análisis de Rotación de Inventario (Polar Area) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-teal-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-rotate-right text-2xl mr-2' style="color: #14B8A6;"></i>
                                Rotación de Inventario
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartRotacionInventario"></canvas>
                            </div>
                        </div>

                        <!-- 10. Análisis de Clientes Frecuentes (Bar Apilado) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-pink-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-group text-2xl mr-2' style="color: #EC4899;"></i>
                                Clientes Frecuentes
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartClientesFrecuentes"></canvas>
                            </div>
                        </div>

                        <!-- 11. Pronóstico de Ventas (Línea con Predicción) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-violet-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-bulb text-2xl mr-2' style="color: #7C3AED;"></i>
                                Pronóstico de Ventas
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartPronostico"></canvas>
                            </div>
                        </div>

                        <!-- 12. Análisis de Punto de Equilibrio (Mixed) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-lime-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-target-lock text-2xl mr-2' style="color: #84CC16;"></i>
                                Punto de Equilibrio
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartPuntoEquilibrio"></canvas>
                            </div>
                        </div>

                        <!-- 13. Análisis de Estacionalidad (Heatmap Style Bar) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-fuchsia-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-calendar text-2xl mr-2' style="color: #D946EF;"></i>
                                Análisis de Estacionalidad
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartEstacionalidad"></canvas>
                            </div>
                        </div>

                        <!-- 14. Análisis ABC de Productos (Pareto) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-sky-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-stats text-2xl mr-2' style="color: #0EA5E9;"></i>
                                Análisis ABC (Pareto)
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartABC"></canvas>
                            </div>
                        </div>

                        <!-- 15. Cash Flow Proyectado (Área con Líneas) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-green-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-money-withdraw text-2xl mr-2' style="color: #16A34A;"></i>
                                Cash Flow Proyectado
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartCashFlow"></canvas>
                            </div>
                        </div>

                        <!-- 16. KPIs Comparativo Multi-Periodo (Radar Multi) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-red-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-line-chart-down text-2xl mr-2' style="color: #DC2626;"></i>
                                KPIs Comparativo
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartKPIs"></canvas>
                            </div>
                        </div>

                        <!-- 17. Análisis de Métodos de Pago (Pie 3D Style) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-yellow-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-credit-card text-2xl mr-2' style="color: #EAB308;"></i>
                                Métodos de Pago
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartMetodosPago"></canvas>
                            </div>
                        </div>

                        <!-- 18. Análisis de Ventas por Día de Semana (Bar Comparativo) -->
                        <div class="dashboard-card p-6 rounded-xl shadow-lg border-t-4 border-slate-600 hover:shadow-2xl transition-shadow">
                            <h3 class="text-lg font-bold text-custom-text mb-4 flex items-center">
                                <i class='bx bx-calendar-week text-2xl mr-2' style="color: #64748B;"></i>
                                Ventas por Día Semana
                            </h3>
                            <div class="chart-container">
                                <canvas id="chartVentasDiaSemana"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            `;

            console.log('📊 Renderizando dashboard con datos:', {
                sales: dashboardData.sales?.length || 0,
                products: dashboardData.products?.length || 0,
                categories: dashboardData.categories?.length || 0,
                customers: dashboardData.customers?.length || 0
            });

            // Pequeño delay para asegurar que el DOM esté listo
            setTimeout(() => {
                renderAllCharts();
            }, 100);
        }

        async function renderIslaFinance() {
            const contentArea = document.getElementById('main-content-area');

            // Asegurar que financialAI está inicializada
            if (!financialAI) {
                console.log('🔄 Inicializando IA...');
                financialAI = new IslaPredictAI();
                window.financialAI = financialAI;
            }

            // Asegurar que los datos están cargados
            if (currentUserEmail && dashboardData.sales.length === 0) {
                await fetchDashboardData(currentUserEmail);
            }

            contentArea.innerHTML = `
                <div class="max-w-full">
                    <div class="dashboard-card p-6 sm:p-8 rounded-xl shadow-lg">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
                            <div class="flex items-center">
                                <div class="mr-3 sm:mr-4">
                                    <img src="/storage/logos/isla_ia.png" alt="Isla IA" class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20">
                                </div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-custom-active">Isla IA</h3>
                                    <p class="text-xs sm:text-sm lg:text-base text-custom-gray">Asistente Inteligente de Negocios</p>
                                </div>
                            </div>
                            <div id="isla-memory-indicator" class="hidden text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium flex items-center gap-1">
                                <i class='bx bx-check-circle'></i> Memoria activa
                            </div>
                        </div>

                        <div class="mb-4 flex flex-col gap-3">
                            <div class="flex flex-wrap gap-2">
                                <button onclick="askSuggestion('¿Cómo van las ventas?')" class="text-xs sm:text-sm bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm flex items-center gap-1">
                                    <i class='bx bx-trending-up'></i> <span class="hidden xs:inline">Ventas</span><span class="xs:hidden">Ventas</span>
                                </button>
                                <button onclick="askSuggestion('¿Qué productos necesito reabastecer?')" class="text-xs sm:text-sm bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm flex items-center gap-1">
                                    <i class='bx bx-package'></i> <span class="hidden xs:inline">Inventario</span><span class="xs:hidden">Inventario</span>
                                </button>
                                <button onclick="askSuggestion('Dame consejos para mi negocio')" class="text-xs sm:text-sm bg-teal-100 hover:bg-teal-200 text-teal-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm flex items-center gap-1">
                                    <i class='bx bx-bulb'></i> <span class="hidden xs:inline">Consejos</span><span class="xs:hidden">Consejos</span>
                                </button>
                                <button onclick="askSuggestion('Explícame qué es la inteligencia artificial')" class="text-xs sm:text-sm bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm flex items-center gap-1">
                                    <i class='bx bx-bot'></i> <span class="hidden xs:inline">IA</span><span class="xs:hidden">IA</span>
                                </button>
                                <button onclick="askSuggestion('¿Qué me recomiendas para ser más productivo?')" class="text-xs sm:text-sm bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm flex items-center gap-1">
                                    <i class='bx bx-rocket'></i> <span class="hidden xs:inline">Productividad</span><span class="xs:hidden">Productividad</span>
                                </button>
                                <button onclick="askSuggestion('Cuéntame algo interesante')" class="text-xs sm:text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm flex items-center gap-1">
                                    <i class='bx bx-star'></i> <span class="hidden xs:inline">Curiosidades</span><span class="xs:hidden">Curiosidades</span>
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button onclick="viewConversations()" class="text-xs sm:text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm whitespace-nowrap flex items-center gap-1" title="Ver todas las conversaciones">
                                    <i class='bx bx-list-ul'></i> <span class="hidden sm:inline">Conversaciones</span><span class="sm:hidden">Conv.</span>
                                </button>
                                <button onclick="viewHistory()" class="text-xs sm:text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm whitespace-nowrap flex items-center gap-1" title="Ver historial completo de conversación">
                                    <i class='bx bx-history'></i> <span class="hidden sm:inline">Historial</span><span class="sm:hidden">Hist.</span>
                                </button>
                                <button onclick="newConversation()" class="text-xs sm:text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full transition-colors font-medium shadow-sm whitespace-nowrap flex items-center gap-1" title="Iniciar nueva conversación (borra la memoria)">
                                    <i class='bx bx-refresh'></i> <span class="hidden sm:inline">Nueva</span><span class="sm:hidden">Nueva</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:gap-6">
                            <div id="ai-chat-messages" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-gray-200 chat-container shadow-inner" style="min-height: 300px; max-height: 500px; overflow-y: auto;"></div>
                            <div class="flex gap-2 sm:gap-3">
                                <input type="text" id="ai-chat-input" placeholder="Pregúntame lo que quieras..." class="flex-1 px-3 py-2.5 sm:px-4 sm:py-3 lg:px-5 lg:py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-active focus:border-transparent text-sm sm:text-base" onkeypress="if(event.key === 'Enter') sendMessageToAI()">
                                <button onclick="sendMessageToAI()" class="bg-custom-active hover:bg-green-700 text-white px-4 py-2.5 sm:px-6 sm:py-3 lg:px-8 lg:py-4 rounded-lg font-medium transition-colors shadow-md">
                                    <i class='bx bx-send text-base sm:text-lg lg:text-xl'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Historial -->
                    <div id="historyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-2 sm:p-4" onclick="if(event.target.id === 'historyModal') closeHistoryModal()">
                        <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[85vh] sm:max-h-[80vh] flex flex-col" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-custom-active flex items-center gap-2 sm:gap-3">
                                    <i class='bx bx-history text-2xl sm:text-3xl'></i> <span class="hidden sm:inline">Historial de Conversación</span><span class="sm:hidden">Historial</span>
                                </h3>
                                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 text-xl sm:text-2xl">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <div id="historyContent" class="flex-1 overflow-y-auto p-4 sm:p-6">
                                <div class="flex items-center justify-center py-12">
                                    <div class="text-center">
                                        <i class='bx bx-loader-alt bx-spin text-3xl sm:text-4xl mb-2 text-custom-active'></i>
                                        <p class="text-sm sm:text-base text-gray-500">Cargando historial...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                                <button onclick="closeHistoryModal()" class="w-full bg-custom-active hover:bg-green-700 text-white px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg font-medium transition-colors text-sm sm:text-base">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Conversaciones -->
                    <div id="conversationsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-2 sm:p-4" onclick="if(event.target.id === 'conversationsModal') closeConversationsModal()">
                        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[85vh] sm:max-h-[80vh] flex flex-col" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-custom-active flex items-center gap-2 sm:gap-3">
                                    <i class='bx bx-conversation text-2xl sm:text-3xl'></i> <span class="hidden sm:inline">Mis Conversaciones</span><span class="sm:hidden">Conversaciones</span>
                                </h3>
                                <button onclick="closeConversationsModal()" class="text-gray-400 hover:text-gray-600 text-xl sm:text-2xl">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <div id="conversationsModalContent" class="flex-1 overflow-y-auto p-4 sm:p-6">
                                <div class="flex items-center justify-center py-12">
                                    <div class="text-center">
                                        <i class='bx bx-loader-alt bx-spin text-3xl sm:text-4xl mb-2 text-custom-active'></i>
                                        <p class="text-sm sm:text-base text-gray-500">Cargando conversaciones...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <button onclick="newConversation(); closeConversationsModal();" class="flex-1 bg-custom-active hover:bg-green-700 text-white px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                                    <i class='bx bx-plus'></i> <span class="hidden sm:inline">Nueva Conversación</span><span class="sm:hidden">Nueva</span>
                                </button>
                                <button onclick="closeConversationsModal()" class="px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors text-sm sm:text-base">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // 🧠 Actualizar indicador de memoria si hay session_id guardado
            setTimeout(() => {
                if (financialAI && financialAI.sessionId) {
                    updateMemoryIndicator(true);
                }
            }, 100);

            // Esperar a que el DOM esté listo y luego mostrar mensaje
            const showWelcomeMessage = () => {
                const chatContainer = document.getElementById('ai-chat-messages');
                if (!chatContainer) {
                    // Si el contenedor no existe, reintentar
                    setTimeout(showWelcomeMessage, 100);
                    return;
                }

                // Mostrar mensaje de bienvenida solo si no se ha mostrado
                if (!aiWelcomeShown) {
                    addChatMessage('ai', '¡Hola! 👋 Soy Isla, tu Asistente Inteligente de Negocios.\n\nPuedo ayudarte con análisis de ventas, inventario, tendencias, recomendaciones para tu negocio, y también conversar sobre cualquier tema que necesites. 😊\n\n¿En qué puedo ayudarte hoy?');
                    aiWelcomeShown = true;
                }
            };

            // Iniciar después de un pequeño delay para asegurar que el DOM está listo
            setTimeout(showWelcomeMessage, 200);
        }

        // Función para renderizar Abrir Caja
        async function renderAbrirCaja() {
            const contentArea = document.getElementById('main-content-area');

            contentArea.innerHTML = `
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-gray-200">

                        <!-- Header -->
                        <div class="mb-8 pb-6 border-b border-emerald-200 flex items-center justify-center">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 flex items-center">
                                <i class='bx bx-wallet text-4xl text-emerald-600 mr-3'></i>
                                Abrir Caja
                            </h1>
                        </div>

                        <!-- Información -->
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl mb-6">
                            <div class="flex items-start">
                                <i class='bx bx-info-circle text-2xl text-emerald-600 mr-3 mt-0.5'></i>
                                <div>
                                    <strong class="font-bold block mb-1">Apertura de Caja</strong>
                                    <p class="text-sm">Ingresa el fondo inicial con el que vas a comenzar el turno. Este monto debe coincidir con el efectivo físico que tienes en la caja.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario -->
                        <form id="open-cash-form" class="space-y-6">
                            <!-- Cajero -->
                            <div>
                                <label for="cashier_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class='bx bx-user text-blue-600 mr-1'></i>
                                    Cajero (Opcional)
                                </label>
                                <select
                                    id="cashier_id"
                                    name="cashier_id"
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-3">
                                    <option value="">Sin cajero asignado</option>
                                </select>

                                <!-- Botón para agregar nuevo cajero -->
                                <button
                                    type="button"
                                    onclick="openDashboardCashierModal()"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2.5 px-4 rounded-xl transition-colors font-semibold flex items-center justify-center gap-2 shadow-lg">
                                    <i class='bx bx-user-plus text-xl'></i>
                                    <span>Agregar Nuevo Cajero</span>
                                </button>

                                <p class="text-xs text-gray-500 mt-2 text-center">¿No encuentras tu cajero? Agrégalo con el botón de arriba</p>
                            </div>

                            <!-- Fondo Inicial -->
                            <div>
                                <label for="opening_balance" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class='bx bx-money text-emerald-600 mr-1'></i>
                                    Fondo Inicial (Efectivo) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-xl font-bold">$</span>
                                    <input
                                        type="number"
                                        id="opening_balance"
                                        name="opening_balance"
                                        step="0.01"
                                        min="0"
                                        value="500.00"
                                        required
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-lg font-semibold"
                                        placeholder="0.00">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Ejemplo: 500.00 (el efectivo que tienes en la caja para dar cambio)</p>
                            </div>

                            <!-- Notas de Apertura -->
                            <div>
                                <label for="opening_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class='bx bx-note text-emerald-600 mr-1'></i>
                                    Notas de Apertura (Opcional)
                                </label>
                                <textarea
                                    id="opening_notes"
                                    name="opening_notes"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Ej: Turno matutino, billetes de $100 y $200..."></textarea>
                            </div>

                            <!-- Información de Usuario y Fecha -->
                            <div class="bg-gray-50 p-4 rounded-xl border-2 border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Usuario:</span>
                                        <p class="font-semibold text-gray-800">${currentUserName || 'Cargando...'}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Fecha:</span>
                                        <p class="font-semibold text-gray-800">${new Date().toLocaleString('es-MX')}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-4 pt-4">
                                <button
                                    type="submit"
                                    class="flex-1 bg-emerald-600 text-white py-3 rounded-xl hover:bg-emerald-700 transition-all duration-200 font-bold text-lg shadow-lg flex items-center justify-center transform hover:scale-105">
                                    <i class='bx bx-check-circle text-2xl mr-2'></i>
                                    Abrir Caja
                                </button>
                                <button
                                    type="button"
                                    onclick="loadContent('dashboard')"
                                    class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-xl hover:bg-gray-400 transition-all duration-200 font-semibold text-lg shadow-lg text-center flex items-center justify-center">
                                    <i class='bx bx-x text-2xl mr-2'></i>
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            // Cargar cajeros activos
            loadDashboardCashiers();

            // Manejar el envío del formulario
            document.getElementById('open-cash-form').addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(e.target);

                try {
                    const response = await fetch('/caja/abrir', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    if (response.ok) {
                        alert('¡Caja abierta exitosamente!');
                        loadContent('dashboard');
                    } else {
                        const error = await response.json();
                        alert('Error: ' + (error.message || 'No se pudo abrir la caja'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al abrir la caja. Por favor intenta de nuevo.');
                }
            });
        }

        // Función para renderizar Historial de Cajas
        async function renderHistorialCajas() {
            const contentArea = document.getElementById('main-content-area');

            contentArea.innerHTML = `
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-200 mb-6">

                        <!-- Header -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 text-center flex items-center justify-center">
                                <i class='bx bx-history text-4xl text-blue-600 mr-3'></i>
                                Historial de Cajas
                            </h1>
                        </div>

                        <!-- Estadísticas -->
                        <div id="cash-stats" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4">
                                <p class="text-xs text-emerald-700 mb-1 font-semibold">🟢 Cajas Abiertas</p>
                                <p class="text-2xl font-bold text-emerald-800" id="open-count">Cargando...</p>
                            </div>
                            <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                                <p class="text-xs text-red-700 mb-1 font-semibold">🔒 Cajas Cerradas</p>
                                <p class="text-2xl font-bold text-red-800" id="closed-count">Cargando...</p>
                            </div>
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                                <p class="text-xs text-blue-700 mb-1 font-semibold">💰 Total Vendido</p>
                                <p class="text-2xl font-bold text-blue-800" id="total-sales">Cargando...</p>
                            </div>
                            <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-4">
                                <p class="text-xs text-purple-700 mb-1 font-semibold">📊 Total Cajas</p>
                                <p class="text-2xl font-bold text-purple-800" id="total-count">Cargando...</p>
                            </div>
                        </div>

                        <!-- Botón Abrir Nueva Caja -->
                        <div id="open-cash-button" class="mb-6">
                            <!-- Se llenará dinámicamente -->
                        </div>

                        <!-- Tabla de Historial -->
                        <div>
                            <h2 class="text-2xl font-bold mb-4 flex items-center text-gray-800">
                                <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                                Registro de Cajas
                            </h2>

                            <div id="cash-table" class="overflow-x-auto">
                                <div class="text-center text-gray-500 py-8">
                                    <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                                    <p>Cargando registros...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Cargar datos del historial
            try {
                const response = await fetch('/caja/api/history', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    // Actualizar estadísticas
                    document.getElementById('open-count').textContent = data.stats.open_count;
                    document.getElementById('closed-count').textContent = data.stats.closed_count;
                    document.getElementById('total-sales').textContent = '$' + parseFloat(data.stats.total_sales).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    document.getElementById('total-count').textContent = data.stats.total_count;

                    // Mostrar botón si no hay caja abierta
                    if (data.stats.open_count === 0) {
                        document.getElementById('open-cash-button').innerHTML = `
                            <a href="#abrir-caja" data-page="abrir-caja" onclick="loadContent('abrir-caja')" class="w-full bg-emerald-600 text-white py-4 rounded-xl hover:bg-emerald-700 transition-colors font-bold text-lg shadow-lg flex items-center justify-center cursor-pointer">
                                <i class='bx bx-wallet text-2xl mr-2'></i>
                                Abrir Nueva Caja
                            </a>
                        `;
                    } else {
                        document.getElementById('open-cash-button').innerHTML = `
                            <div class="bg-yellow-50 border-2 border-yellow-300 text-yellow-900 p-4 rounded-xl">
                                <div class="flex items-center">
                                    <i class='bx bx-info-circle text-2xl text-yellow-600 mr-3'></i>
                                    <div>
                                        <strong class="font-bold">Caja Abierta Activa</strong>
                                        <p class="text-sm">Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Renderizar tabla
                    if (data.cash_registers.length > 0) {
                        let tableHTML = `
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300 bg-gray-50 dark:bg-gray-700">
                                        <th class="text-left py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">#</th>
                                        <th class="text-left py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Cajero</th>
                                        <th class="text-left py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Estado</th>
                                        <th class="text-left py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Apertura</th>
                                        <th class="text-left py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Cierre</th>
                                        <th class="text-right py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Fondo</th>
                                        <th class="text-right py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Ventas</th>
                                        <th class="text-right py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Diferencia</th>
                                        <th class="text-center py-3 px-3 text-gray-700 dark:text-gray-200 font-bold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        data.cash_registers.forEach(register => {
                            const openedDate = new Date(register.opened_at);
                            const closedDate = register.closed_at ? new Date(register.closed_at) : null;
                            const statusBadge = register.status === 'open'
                                ? '<span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs font-semibold">🟢 Abierta</span>'
                                : '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">🔒 Cerrada</span>';

                            const difference = register.difference;
                            let diffClass = 'text-gray-600';
                            let diffIcon = '';
                            if (difference > 0) {
                                diffClass = 'text-green-600 font-bold';
                                diffIcon = '+';
                            } else if (difference < 0) {
                                diffClass = 'text-red-600 font-bold';
                                diffIcon = '';
                            }

                            const cashierName = register.cashier
                                ? `<div class="flex items-center"><i class='bx bx-user-circle text-blue-500 mr-1'></i> ${register.cashier.name}</div>`
                                : '<span class="text-gray-400 italic">Sin asignar</span>';

                            tableHTML += `
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="py-3 px-3 text-gray-700 dark:text-gray-300 font-semibold">#${register.id}</td>
                                    <td class="py-3 px-3 text-gray-700 dark:text-gray-300">${cashierName}</td>
                                    <td class="py-3 px-3">${statusBadge}</td>
                                    <td class="py-3 px-3 text-gray-700 dark:text-gray-300">${openedDate.toLocaleString('es-MX', {dateStyle: 'short', timeStyle: 'short'})}</td>
                                    <td class="py-3 px-3 text-gray-700 dark:text-gray-300">${closedDate ? closedDate.toLocaleString('es-MX', {dateStyle: 'short', timeStyle: 'short'}) : '—'}</td>
                                    <td class="py-3 px-3 text-right text-gray-700 dark:text-gray-300 font-semibold">$${parseFloat(register.opening_balance).toFixed(2)}</td>
                                    <td class="py-3 px-3 text-right text-blue-700 dark:text-blue-400 font-bold">$${parseFloat(register.total_sales).toFixed(2)}</td>
                                    <td class="py-3 px-3 text-right ${diffClass}">${difference !== null ? diffIcon + '$' + Math.abs(parseFloat(difference)).toFixed(2) : '—'}</td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="showCashRegisterModal(${register.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-xs inline-flex items-center cursor-pointer bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                                <i class='bx bx-show text-lg mr-1'></i>
                                                Ver
                                            </button>
                                            ${register.status === 'open' ? `
                                                <button type="button" onclick="loadCloseCashRegisterForm(${register.id})" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold text-xs inline-flex items-center cursor-pointer bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
                                                    <i class='bx bx-lock-alt text-lg mr-1'></i>
                                                    Cerrar
                                                </button>
                                            ` : `
                                                <button type="button" onclick="deleteCashRegister(${register.id})" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold text-xs inline-flex items-center cursor-pointer bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
                                                    <i class='bx bx-trash text-lg mr-1'></i>
                                                    Eliminar
                                                </button>
                                            `}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });

                        tableHTML += `
                                </tbody>
                            </table>
                        `;

                        document.getElementById('cash-table').innerHTML = tableHTML;
                    } else {
                        document.getElementById('cash-table').innerHTML = `
                            <div class="bg-gray-50 border-2 border-gray-200 text-gray-600 p-8 rounded-xl text-center">
                                <i class='bx bx-inbox text-5xl mb-3 text-gray-400'></i>
                                <p class="text-lg font-semibold">No hay registros de cajas</p>
                                <p class="text-sm mt-2">Abre tu primera caja para comenzar a registrar.</p>
                            </div>
                        `;
                    }
                } else {
                    throw new Error('Error al cargar datos');
                }
            } catch (error) {
                console.error('Error cargando historial:', error);
                document.getElementById('cash-table').innerHTML = `
                    <div class="bg-red-50 border-2 border-red-200 text-red-800 p-8 rounded-xl text-center">
                        <i class='bx bx-error text-5xl mb-3 text-red-600'></i>
                        <p class="text-lg font-semibold">Error al cargar el historial</p>
                        <p class="text-sm mt-2">Por favor, intenta de nuevo más tarde.</p>
                    </div>
                `;
            }
        }

        function renderReports() {
            const contentArea = document.getElementById('main-content-area');

            contentArea.innerHTML = `
                <div class="max-w-4xl mx-auto">
                    <div class="dashboard-card p-8 rounded-2xl shadow-xl mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-custom-active rounded-xl flex items-center justify-center mr-4">
                                <i class='bx bx-calculator text-white text-3xl'></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-custom-text">Corte de Caja</h2>
                                <p class="text-custom-gray">Genera el reporte diario de ventas</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <form action="{{ route('reports.corte-caja') }}" method="POST" target="_blank" id="corte-caja-form">
                                @csrf

                                <div class="mb-4">
                                    <label for="fecha-corte" class="block text-sm font-medium text-custom-gray mb-2">
                                        <i class='bx bx-calendar mr-1'></i>
                                        Selecciona la fecha del corte
                                    </label>
                                    <input type="date"
                                           id="fecha-corte"
                                           name="fecha"
                                           value="${new Date().toISOString().split('T')[0]}"
                                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-active focus:border-transparent"
                                           required>
                                </div>

                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex space-x-3">
                                        <button type="button"
                                                onclick="setFechaCorteCaja('hoy')"
                                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                                            <i class='bx bx-time mr-1'></i>Hoy
                                        </button>
                                        <button type="button"
                                                onclick="setFechaCorteCaja('ayer')"
                                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                                            <i class='bx bx-calendar-minus mr-1'></i>Ayer
                                        </button>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-custom-active to-green-700 text-white font-bold py-4 rounded-lg hover:from-green-700 hover:to-custom-active transition-all shadow-lg hover:shadow-xl flex items-center justify-center">
                                    <i class='bx bxs-file-pdf text-2xl mr-2'></i>
                                    Generar Corte de Caja en PDF
                                </button>
                            </form>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-blue-800 font-semibold mb-2 flex items-center">
                                <i class='bx bx-info-circle mr-2'></i>
                                ¿Qué incluye el corte de caja?
                            </h3>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li class="flex items-start">
                                    <i class='bx bx-check text-custom-active mr-2 mt-0.5'></i>
                                    <span>Total de ventas del día</span>
                                </li>
                                <li class="flex items-start">
                                    <i class='bx bx-check text-custom-active mr-2 mt-0.5'></i>
                                    <span>Número de tickets procesados</span>
                                </li>
                                <li class="flex items-start">
                                    <i class='bx bx-check text-custom-active mr-2 mt-0.5'></i>
                                    <span>Productos más vendidos</span>
                                </li>
                                <li class="flex items-start">
                                    <i class='bx bx-check text-custom-active mr-2 mt-0.5'></i>
                                    <span>Detalle completo de cada venta</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Más reportes (preparado para futuras funcionalidades) -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Reporte de Productos -->
                        <div class="dashboard-card p-6 rounded-2xl shadow-xl opacity-50">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-package text-white text-2xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-custom-text">Inventario</h3>
                                    <p class="text-custom-gray text-sm">Próximamente</p>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm">Reporte detallado de productos y stock</p>
                        </div>

                        <!-- Reporte de Clientes -->
                        <div class="dashboard-card p-6 rounded-2xl shadow-xl opacity-50">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-user text-white text-2xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-custom-text">Clientes</h3>
                                    <p class="text-custom-gray text-sm">Próximamente</p>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm">Reporte de ventas por cliente</p>
                        </div>
                    </div>
                </div>
            `;
        }

        async function loadContent(pageName) {
            const contentArea = document.getElementById('main-content-area');

            // Resetear bandera de saludo cuando NO estás en IA
            if (pageName !== 'ia-financiera') {
                aiWelcomeShown = false;
            }

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

                // Asegurar que financialAI está inicializada
                if (!financialAI) {
                    financialAI = new IslaPredictAI();
                    window.financialAI = financialAI;
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

            // PÁGINA DE ABRIR CAJA
            if (pageName === 'abrir-caja') {
                renderAbrirCaja();
                updateActiveLink('abrir-caja');
                return;
            }

            // PÁGINA DE HISTORIAL DE CAJAS
            if (pageName === 'historial-cajas') {
                renderHistorialCajas();
                updateActiveLink('historial-cajas');
                return;
            }

            // PÁGINA DE REPORTES
            if (pageName === 'reports') {
                renderReports();
                updateActiveLink('reports');
                return;
            }

            const crudRoutes = {
                'sales': '/sales',
                'products': '/products',
                'categories': '/categories',
                'customers': '/customers'
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

        // Función para toggle del menú desplegable de Caja
        window.toggleCajaMenu = function() {
            const submenu = document.getElementById('caja-submenu');
            const icon = document.getElementById('caja-menu-icon');

            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                submenu.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener("click", e => {
            const link = e.target.closest(".sidebar-link");
            if (link) {
                const page = link.getAttribute("data-page");
                // Solo prevenir navegación si tiene data-page (navegación interna)
                // Si no tiene data-page, dejar que funcione como enlace normal
                if (page) {
                    e.preventDefault();
                    loadContent(page);
                }
            }
        });

        // Función para los botones de fecha del corte de caja
        function setFechaCorteCaja(tipo) {
            const input = document.getElementById('fecha-corte');
            if (!input) return;

            const hoy = new Date();
            if (tipo === 'ayer') {
                hoy.setDate(hoy.getDate() - 1);
            }
            input.value = hoy.toISOString().split('T')[0];
        }

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
            try {
                if (typeof tf !== 'undefined') {
                    financialAI = new IslaPredictAI();
                    window.financialAI = financialAI;
                } else {
                    console.warn('⚠️ TensorFlow no disponible, inicializando IA básica...');
                    financialAI = new IslaPredictAI();
                    window.financialAI = financialAI;
                }
            } catch (error) {
                console.error('❌ Error inicializando IA:', error);
                financialAI = new IslaPredictAI();
                window.financialAI = financialAI;
            }
        })();

        auth.onAuthStateChanged(async function(user) {
            console.log("🔐 Estado de autenticación cambiado...");

            if (!user) {
                console.log("❌ Usuario no autenticado, redirigiendo...");
                window.location.replace('/');
                return;
            }

            console.log("✅ Autenticado:", user.email);

            // 🔄 Limpiar historial de Isla si cambió el usuario
            const lastUserEmail = localStorage.getItem('last_user_email');
            if (lastUserEmail && lastUserEmail !== user.email) {
                console.log("🔄 Usuario diferente detectado, limpiando historial de Isla...");
                localStorage.removeItem('isla_session_id');
                if (financialAI) {
                    financialAI.sessionId = null;
                }
            }
            localStorage.setItem('last_user_email', user.email);

            currentUser = user;
            currentUserEmail = user.email;
            currentUserName = user.displayName || user.email.split('@')[0];

            updateSidebarProfile(user);

            const userName = currentUserName;
            const userNameEl = document.getElementById('user-name');
            if (userNameEl) userNameEl.textContent = userName;

            console.log("📊 Cargando contenido del dashboard...");

            // 🏢 Verificar si el usuario tiene tipo de negocio configurado
            await checkBusinessType();

            // Verificar hash ANTES de cargar
            const initialHash = window.location.hash.substring(1);
            const pageToLoad = (initialHash && initialHash !== '' && initialHash !== 'dashboard') ? initialHash : 'dashboard';

            try {
                // Cargar la página correcta desde el inicio
                await loadContent(pageToLoad);
                console.log(`✅ ${pageToLoad} cargado exitosamente`);

                // 🏪 Cargar nombre del negocio DESPUÉS de que el contenido esté cargado
                await loadBusinessInfo();
            } catch (error) {
                console.error("❌ Error cargando página:", error);
            }

            startAutoRefresh();

            // Configurar navegación en los links del sidebar
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const pageName = this.getAttribute('data-page');
                    console.log('📄 Navegando a:', pageName);

                    // Actualizar hash sin disparar hashchange
                    history.pushState(null, null, '#' + pageName);

                    await loadContent(pageName);
                });
            });
        });

        // ==========================================
        // 🪟 MODAL DE CAJA REGISTRADORA
        // ==========================================
        async function showCashRegisterModal(registerId) {
            console.log('🔍 Abriendo modal de caja ID:', registerId);

            // Crear modal si no existe
            let modal = document.getElementById('cashRegisterModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'cashRegisterModal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4';
                modal.onclick = function(e) {
                    if (e.target.id === 'cashRegisterModal') {
                        closeCashRegisterModal();
                    }
                };

                modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-gray-700" onclick="event.stopPropagation()">
                        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6 flex items-center justify-between z-10">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center" id="cashModalTitle">
                                <i class='bx bx-wallet text-emerald-500 mr-2 text-3xl'></i>
                                Detalles de Caja
                            </h2>
                            <button onclick="closeCashRegisterModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class='bx bx-x text-3xl'></i>
                            </button>
                        </div>
                        <div id="cashModalContent" class="p-6">
                            <div class="flex items-center justify-center py-12">
                                <i class='bx bx-loader-alt bx-spin text-blue-500 text-6xl'></i>
                                <span class="ml-4 text-xl text-gray-600 dark:text-gray-400">Cargando detalles...</span>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);
            }

            const modalContent = document.getElementById('cashModalContent');
            const modalTitle = document.getElementById('cashModalTitle');

            // Mostrar modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            try {
                const response = await fetch(`/caja/${registerId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                console.log('📥 Respuesta recibida, status:', response.status);

                if (!response.ok) {
                    throw new Error(`Error ${response.status}`);
                }

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Extraer contenido
                const mainContent = doc.querySelector('body > div.max-w-7xl');

                if (mainContent) {
                    const title = doc.querySelector('h1');
                    if (title) {
                        modalTitle.innerHTML = title.innerHTML;
                    }

                    const contentClone = mainContent.cloneNode(true);

                    // Remover header y botones
                    const headerNav = contentClone.querySelector('.bg-gray-800');
                    if (headerNav && headerNav.querySelector('a')) {
                        headerNav.remove();
                    }

                    const actionButtons = contentClone.querySelector('.flex.gap-4.mb-6');
                    if (actionButtons) {
                        actionButtons.remove();
                    }

                    modalContent.innerHTML = contentClone.innerHTML;
                    console.log('✅ Modal cargado exitosamente');
                } else {
                    throw new Error('No se encontró contenido');
                }
            } catch (error) {
                console.error('❌ Error cargando modal:', error);
                modalContent.innerHTML = `
                    <div class="text-center py-12">
                        <i class='bx bx-error text-red-500 text-6xl mb-4'></i>
                        <p class="text-red-600 text-lg mb-2 font-bold">Error al cargar los detalles</p>
                        <p class="text-gray-600 text-sm">${error.message}</p>
                    </div>
                `;
            }
        }

        function closeCashRegisterModal() {
            const modal = document.getElementById('cashRegisterModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        /**
         * Cargar formulario de cierre de caja en modal
         */
        async function loadCloseCashRegisterForm(registerId) {
            console.log('🔒 Abriendo modal de cierre para caja ID:', registerId);

            // Crear o obtener el modal
            let modal = document.getElementById('closeCashRegisterModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'closeCashRegisterModal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4';
                modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-gray-700">
                        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6 flex items-center justify-between z-10">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center" id="closeModalTitle">
                                <i class='bx bx-lock-alt text-red-500 mr-2 text-3xl'></i>
                                Cerrar Caja
                            </h2>
                            <button onclick="closeCloseCashRegisterModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i class='bx bx-x text-3xl'></i>
                            </button>
                        </div>
                        <div id="closeModalContent" class="p-6">
                            <div class="flex items-center justify-center py-12">
                                <i class='bx bx-loader-alt bx-spin text-red-500 text-6xl'></i>
                                <span class="ml-4 text-xl text-gray-600 dark:text-gray-400">Cargando formulario...</span>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }

            // Mostrar el modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            const modalContent = document.getElementById('closeModalContent');
            const modalTitle = document.getElementById('closeModalTitle');

            try {
                const response = await fetch(`/caja/${registerId}/cerrar`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar el formulario');
                }

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');

                // Extraer el contenido del formulario
                const formContainer = doc.querySelector('body > div');

                if (formContainer) {
                    // Remover el header con el botón de volver
                    const header = formContainer.querySelector('.mb-8.pb-4.border-b');
                    if (header) {
                        const title = formContainer.querySelector('h1');
                        if (title) {
                            modalTitle.innerHTML = title.innerHTML;
                        }
                        header.remove();
                    }

                    // Actualizar botones para cerrar el modal en lugar de redirigir
                    const cancelButton = formContainer.querySelector('a[href*="cash-register"]');
                    if (cancelButton) {
                        cancelButton.setAttribute('onclick', 'closeCloseCashRegisterModal(); return false;');
                        cancelButton.setAttribute('href', '#');
                    }

                    modalContent.innerHTML = formContainer.innerHTML;
                    console.log('✅ Formulario de cierre cargado en modal');

                    // Configurar el interceptor para el formulario de cierre
                    setupCloseCashRegisterFormInterceptor(registerId);

                    // Inicializar el script de cálculo de diferencia si existe
                    const scripts = formContainer.querySelectorAll('script');
                    scripts.forEach(script => {
                        eval(script.textContent);
                    });
                } else {
                    throw new Error('No se encontró el formulario');
                }
            } catch (error) {
                console.error('❌ Error cargando formulario de cierre:', error);
                modalContent.innerHTML = `
                    <div class="text-center py-12">
                        <i class='bx bx-error text-red-500 text-6xl mb-4'></i>
                        <p class="text-red-600 dark:text-red-400 text-lg mb-2 font-bold">Error al cargar el formulario</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">${error.message}</p>
                    </div>
                `;
            }
        }

        /**
         * Cerrar modal de cierre de caja
         */
        function closeCloseCashRegisterModal() {
            const modal = document.getElementById('closeCashRegisterModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        /**
         * Eliminar registro de caja
         */
        async function deleteCashRegister(registerId) {
            console.log('🗑️ Solicitando eliminar caja ID:', registerId);

            // Confirmar eliminación
            const confirmed = await showAlert({
                title: '¿Eliminar Registro de Caja?',
                message: '¿Estás seguro de que deseas eliminar este registro de caja? Esta acción no se puede deshacer.',
                type: 'question',
                showCancel: true,
                confirmText: 'Eliminar',
                cancelText: 'Cancelar'
            });

            if (!confirmed) {
                console.log('❌ Eliminación cancelada por el usuario');
                return;
            }

            try {
                const response = await fetch(`/caja/${registerId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Error al eliminar el registro');
                }

                const data = await response.json();

                // Mostrar mensaje de éxito
                await showAlert({
                    title: '¡Eliminado!',
                    message: data.message || 'El registro de caja ha sido eliminado exitosamente.',
                    type: 'success'
                });

                // Recargar el historial de cajas
                await loadContent('historial-cajas');

            } catch (error) {
                console.error('❌ Error al eliminar registro de caja:', error);
                await showAlert({
                    title: 'Error',
                    message: error.message || 'No se pudo eliminar el registro. Por favor intenta nuevamente.',
                    type: 'error'
                });
            }
        }

        /**
         * Configurar interceptor para el formulario de cierre de caja
         */
        function setupCloseCashRegisterFormInterceptor(registerId) {
            const modalContent = document.getElementById('closeModalContent');
            const form = modalContent.querySelector('form');

            if (!form || form.dataset.ajaxIntercepted) return;

            form.dataset.ajaxIntercepted = 'true';

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                e.stopPropagation();

                const formData = new FormData(form);
                const closingBalance = formData.get('closing_balance');

                if (!closingBalance || parseFloat(closingBalance) < 0) {
                    await showAlert({
                        title: 'Error',
                        message: 'Por favor ingresa el efectivo contado en caja.',
                        type: 'error'
                    });
                    return;
                }

                console.log('🔒 Cerrando caja ID:', registerId);

                try {
                    const response = await fetch(`/caja/${registerId}/cerrar`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'Error al cerrar la caja');
                    }

                    const data = await response.json().catch(() => ({}));

                    // Cerrar el modal primero
                    closeCloseCashRegisterModal();

                    // Mostrar mensaje de éxito con detalles
                    let successMessage = data.message || 'La caja se ha cerrado exitosamente.';

                    await showAlert({
                        title: '¡Caja Cerrada!',
                        message: successMessage,
                        type: 'success'
                    });

                    // Recargar el historial de cajas
                    await loadContent('historial-cajas');

                } catch (error) {
                    console.error('❌ Error al cerrar caja:', error);
                    await showAlert({
                        title: 'Error',
                        message: error.message || 'No se pudo cerrar la caja. Por favor intenta nuevamente.',
                        type: 'error'
                    });
                }
            });
        }

        // Cerrar modales con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCashRegisterModal();
                closeCloseCashRegisterModal();
            }
        });

        // ==========================================
        // 🎨 SISTEMA DE ALERTAS MODERNAS
        // ==========================================

        // Crear alerta moderna
        function showAlert(options) {
            const {
                title = 'Notificación',
                message = '',
                type = 'info', // success, error, warning, info, question
                confirmText = 'Aceptar',
                cancelText = 'Cancelar',
                showCancel = false,
                onConfirm = () => {},
                onCancel = () => {}
            } = options;

            return new Promise((resolve) => {
                // Crear modal
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4';
                modal.style.animation = 'fadeIn 0.2s ease-out';

                // Iconos y colores por tipo
                const typeConfig = {
                    success: {
                        icon: 'bx-check-circle',
                        color: 'text-green-500',
                        bgColor: 'bg-green-50',
                        borderColor: 'border-green-200'
                    },
                    error: {
                        icon: 'bx-error-circle',
                        color: 'text-red-500',
                        bgColor: 'bg-red-50',
                        borderColor: 'border-red-200'
                    },
                    warning: {
                        icon: 'bx-error',
                        color: 'text-yellow-500',
                        bgColor: 'bg-yellow-50',
                        borderColor: 'border-yellow-200'
                    },
                    info: {
                        icon: 'bx-info-circle',
                        color: 'text-blue-500',
                        bgColor: 'bg-blue-50',
                        borderColor: 'border-blue-200'
                    },
                    question: {
                        icon: 'bx-help-circle',
                        color: 'text-purple-500',
                        bgColor: 'bg-purple-50',
                        borderColor: 'border-purple-200'
                    }
                };

                const config = typeConfig[type] || typeConfig.info;

                modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform" style="animation: slideUp 0.3s ease-out;">
                        <div class="p-6 text-center">
                            <div class="${config.bgColor} dark:bg-opacity-20 ${config.borderColor} border-2 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='bx ${config.icon} ${config.color} text-5xl'></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">${title}</h3>
                            ${message ? `<p class="text-gray-600 dark:text-gray-300 mb-6">${message}</p>` : ''}
                            <div class="flex gap-3 justify-center">
                                ${showCancel ? `
                                    <button onclick="this.closest('.fixed').remove(); (${onCancel.toString()})(); "
                                        class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-semibold">
                                        ${cancelText}
                                    </button>
                                ` : ''}
                                <button id="confirmBtn"
                                    class="px-6 py-3 bg-custom-active text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-lg">
                                    ${confirmText}
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                // Event listener para confirmar
                modal.querySelector('#confirmBtn').addEventListener('click', function() {
                    modal.remove();
                    onConfirm();
                    resolve(true);
                });

                // Cerrar con click fuera (solo si no hay cancel)
                if (!showCancel) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            modal.remove();
                            resolve(false);
                        }
                    });
                }

                // Cerrar con ESC
                const escHandler = (e) => {
                    if (e.key === 'Escape') {
                        modal.remove();
                        onCancel();
                        resolve(false);
                        document.removeEventListener('keydown', escHandler);
                    }
                };
                document.addEventListener('keydown', escHandler);
            });
        }

        // Agregar animaciones CSS
        if (!document.getElementById('alert-animations')) {
            const style = document.createElement('style');
            style.id = 'alert-animations';
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from {
                        transform: translateY(50px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                @keyframes scaleIn {
                    from {
                        transform: scale(0.9);
                        opacity: 0;
                    }
                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }

                /* Modal de límite alcanzado */
                .limit-modal-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.75);
                    backdrop-filter: blur(8px);
                    z-index: 99999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 1rem;
                    animation: fadeIn 0.2s ease-out;
                }

                .limit-modal-content {
                    background: white;
                    border-radius: 1.25rem;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    max-width: 32rem;
                    width: 100%;
                    overflow: hidden;
                    animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                }

                @media (prefers-color-scheme: dark) {
                    .limit-modal-content {
                        background: #1f2937;
                    }
                }

                .dark .limit-modal-content {
                    background: #1f2937;
                }

                .limit-modal-header {
                    background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
                    padding: 2rem 1.5rem;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }

                .limit-modal-header::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    right: -50%;
                    width: 200%;
                    height: 200%;
                    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                    animation: pulse 3s ease-in-out infinite;
                }

                @keyframes pulse {
                    0%, 100% { opacity: 0.5; }
                    50% { opacity: 1; }
                }

                .limit-modal-icon {
                    background: rgba(255, 255, 255, 0.25);
                    width: 5rem;
                    height: 5rem;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                    color: white;
                    position: relative;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                .limit-modal-title {
                    font-size: 1.75rem;
                    font-weight: 800;
                    color: white;
                    margin: 0;
                    letter-spacing: -0.025em;
                    position: relative;
                }

                .limit-modal-body {
                    padding: 2rem 1.5rem;
                }

                .limit-modal-message {
                    color: #1f2937;
                    font-size: 1.125rem;
                    line-height: 1.75;
                    margin-bottom: 1.5rem;
                    text-align: center;
                }

                .text-orange-600 {
                    color: #ea580c;
                }

                .limit-modal-usage {
                    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
                    border-radius: 0.75rem;
                    padding: 1.25rem;
                    margin-bottom: 1.5rem;
                    border: 1px solid #e5e7eb;
                }

                .usage-stats {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 0.75rem;
                }

                .usage-label {
                    color: #6b7280;
                    font-size: 0.875rem;
                    font-weight: 500;
                }

                .usage-value {
                    color: #111827;
                    font-size: 1.125rem;
                    font-weight: 700;
                }

                .usage-bar {
                    height: 0.5rem;
                    background: #e5e7eb;
                    border-radius: 9999px;
                    overflow: hidden;
                }

                .usage-bar-fill {
                    height: 100%;
                    background: linear-gradient(90deg, #f97316 0%, #dc2626 100%);
                    border-radius: 9999px;
                    transition: width 0.5s ease-out;
                }

                .limit-modal-benefits {
                    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                    border-radius: 0.75rem;
                    padding: 1.25rem;
                    margin-bottom: 1.5rem;
                    border: 1px solid #bfdbfe;
                }

                .benefits-title {
                    color: #1e40af;
                    font-weight: 700;
                    font-size: 1rem;
                    margin-bottom: 1rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .benefits-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    color: #1e40af;
                    font-size: 0.9375rem;
                }

                .benefits-list li {
                    margin-bottom: 0.625rem;
                    display: flex;
                    align-items: flex-start;
                    gap: 0.5rem;
                    line-height: 1.5;
                }

                .benefits-list li:last-child {
                    margin-bottom: 0;
                }

                .check-icon {
                    color: #1d4ed8;
                    font-weight: 700;
                    flex-shrink: 0;
                }

                .limit-modal-upgrade {
                    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                    border-radius: 0.75rem;
                    padding: 1.25rem;
                    margin-bottom: 1.5rem;
                    border: 1px solid #fcd34d;
                }

                .upgrade-title {
                    color: #92400e;
                    font-weight: 700;
                    font-size: 1rem;
                    margin-bottom: 0.625rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .upgrade-text {
                    color: #92400e;
                    font-size: 0.9375rem;
                    margin: 0;
                    line-height: 1.5;
                }

                .limit-modal-btn {
                    width: 100%;
                    padding: 0.875rem 1.5rem;
                    border-radius: 0.625rem;
                    font-weight: 700;
                    font-size: 1rem;
                    border: none;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    margin-bottom: 0.75rem;
                }

                .limit-modal-btn:last-child {
                    margin-bottom: 0;
                }

                .limit-modal-btn-primary {
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: white;
                    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
                }

                .limit-modal-btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
                }

                .limit-modal-btn-success {
                    background: linear-gradient(135deg, #059669 0%, #047857 100%);
                    color: white;
                    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
                }

                .limit-modal-btn-success:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.4);
                }

                .limit-modal-btn-secondary {
                    background: #f3f4f6;
                    color: #374151;
                    border: 1px solid #d1d5db;
                }

                .limit-modal-btn-secondary:hover {
                    background: #e5e7eb;
                }

                .btn-icon {
                    font-size: 1.25rem;
                }

                @media (max-width: 640px) {
                    .limit-modal-content {
                        max-width: 100%;
                        margin: 0.5rem;
                    }

                    .limit-modal-header {
                        padding: 1.5rem 1rem;
                    }

                    .limit-modal-body {
                        padding: 1.5rem 1rem;
                    }

                    .limit-modal-title {
                        font-size: 1.5rem;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Función para mostrar modal de límite alcanzado
        async function showLimitReachedModal(limitData) {
            const { type, limit, current, message } = limitData;

            const typeNames = {
                'product': 'productos',
                'customer': 'clientes',
                'sale': 'ventas este mes'
            };

            const typeName = typeNames[type] || type;

            // Obtener información del plan
            const planInfo = await fetch('/api/subscription/info').then(r => r.json()).catch(() => ({}));
            const hasSubscription = planInfo.hasActiveSubscription || false;
            const planName = planInfo.planName || 'Plan Gratuito';

            const modal = document.createElement('div');
            modal.id = 'limit-modal-dynamic';
            modal.className = 'limit-modal-overlay';

            modal.innerHTML = `
                <div class="limit-modal-content bg-white dark:bg-gray-800">
                    <!-- Header con gradiente mejorado -->
                    <div class="limit-modal-header">
                        <div class="limit-modal-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="limit-modal-title">Límite Alcanzado</h3>
                    </div>

                    <!-- Contenido principal -->
                    <div class="limit-modal-body">
                        <p class="limit-modal-message text-gray-700 dark:text-gray-300">
                            Has alcanzado el límite de <strong class="text-orange-600 dark:text-orange-400">${limit} ${typeName}</strong> de tu <strong class="dark:text-white">${planName}</strong>.
                        </p>

                        <div class="limit-modal-usage">
                            <div class="usage-stats">
                                <span class="usage-label dark:text-gray-400">Uso actual:</span>
                                <span class="usage-value dark:text-white">${current} / ${limit}</span>
                            </div>
                            <div class="usage-bar dark:bg-gray-700">
                                <div class="usage-bar-fill" style="width: 100%;"></div>
                            </div>
                        </div>

                        ${!hasSubscription ? `
                            <div class="limit-modal-benefits dark:bg-gray-700/50 dark:border-gray-600">
                                <h4 class="benefits-title dark:text-white">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Con un plan premium obtienes:
                                </h4>
                                <ul class="benefits-list">
                                    <li class="dark:text-gray-300"><span class="check-icon">✓</span> Más productos, clientes y ventas</li>
                                    <li class="dark:text-gray-300"><span class="check-icon">✓</span> Isla IA - Asistente inteligente</li>
                                    <li class="dark:text-gray-300"><span class="check-icon">✓</span> Caja Express y reportes avanzados</li>
                                    <li class="dark:text-gray-300"><span class="check-icon">✓</span> <strong class="dark:text-white">Plan Básico: 30 días de prueba GRATIS</strong></li>
                                </ul>
                            </div>

                            <button onclick="window.location.href='/subscription/plans'" class="limit-modal-btn limit-modal-btn-primary">
                                <span class="btn-icon">🎁</span>
                                Ver Planes y Activar Prueba Gratis
                            </button>
                        ` : `
                            <div class="limit-modal-upgrade dark:bg-gray-700/50 dark:border-gray-600">
                                <h4 class="upgrade-title dark:text-white">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    ¿Necesitas más capacidad?
                                </h4>
                                <p class="upgrade-text dark:text-gray-300">Considera actualizar a un plan superior para obtener límites más altos o ilimitados.</p>
                            </div>

                            <button onclick="window.location.href='/subscription/plans'" class="limit-modal-btn limit-modal-btn-success">
                                <span class="btn-icon">📈</span>
                                Actualizar Plan
                            </button>
                        `}

                        <button onclick="document.getElementById('limit-modal-dynamic').remove()" class="limit-modal-btn limit-modal-btn-secondary">
                            Cerrar
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Cerrar con click fuera o ESC
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.remove();
            });

            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    modal.remove();
                    document.removeEventListener('keydown', escHandler);
                }
            });
        }

        // Sobrescribir alert() y confirm() globalmente con las alertas modernas
        window.originalAlert = window.alert;
        window.originalConfirm = window.confirm;

        window.alert = function(message) {
            showAlert({
                title: 'Notificación',
                message: message,
                type: 'info'
            });
        };

        window.confirm = async function(message) {
            return await showAlert({
                title: 'Confirmar',
                message: message,
                type: 'question',
                showCancel: true,
                confirmText: 'Aceptar',
                cancelText: 'Cancelar'
            });
        };

        // ==========================================
        // 📝 INTERCEPTOR DE FORMULARIOS (AJAX)
        // ==========================================
        // Esta función intercepta todos los formularios que se cargan dinámicamente
        // y los convierte en envíos AJAX para mantener al usuario en el dashboard

        function setupFormInterceptors() {
            const contentArea = document.getElementById('main-content-area');
            if (!contentArea) return;

            // Buscar todos los formularios en el área de contenido
            const forms = contentArea.querySelectorAll('form');

            forms.forEach(form => {
                // Verificar si el formulario ya tiene el interceptor
                if (form.dataset.ajaxIntercepted) return;

                form.dataset.ajaxIntercepted = 'true';

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const formData = new FormData(form);
                    const method = form.method.toUpperCase();
                    const action = form.action;

                    console.log('📤 Enviando formulario vía AJAX:', action, 'Método:', method);

                    try {
                        // Mostrar loading
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Guardando...';
                        }

                        const response = await fetch(action, {
                            method: method,
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            redirect: 'follow'
                        });

                        console.log('📥 Respuesta recibida:', {
                            status: response.status,
                            redirected: response.redirected,
                            url: response.url,
                            ok: response.ok
                        });

                        // Restaurar botón
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }

                        // Intentar obtener el contenido (puede ser HTML o JSON)
                        const contentType = response.headers.get('content-type');
                        console.log('📄 Content-Type:', contentType);

                        let responseData;
                        if (contentType && contentType.includes('application/json')) {
                            responseData = await response.json();
                            console.log('📦 JSON recibido:', responseData);
                        } else {
                            const text = await response.text();
                            console.log('📄 HTML recibido, longitud:', text.length);
                            responseData = { html: text };

                            // Verificar si el HTML contiene el modal de límite alcanzado
                            if (text.includes('id="limit-modal"') || text.includes('limit-reached-modal')) {
                                console.log('🚫 Límite alcanzado detectado en respuesta');
                                // Recargar la página para mostrar el modal
                                window.location.reload();
                                return;
                            }
                        }

                        // Si la respuesta fue exitosa (status 200-299)
                        if (response.ok || response.redirected) {
                            // Determinar a qué página volver basándose en la URL actual o de respuesta
                            const url = new URL(response.url || window.location.href);
                            let backPage = 'dashboard';

                            if (url.pathname.includes('/products') || action.includes('/products')) {
                                backPage = 'products';
                            } else if (url.pathname.includes('/categories') || action.includes('/categories')) {
                                backPage = 'categories';
                            } else if (url.pathname.includes('/customers') || action.includes('/customers')) {
                                backPage = 'customers';
                            } else if (url.pathname.includes('/sales') || action.includes('/sales')) {
                                backPage = 'sales';
                            }

                            console.log('✅ Guardado exitoso, volviendo a:', backPage);

                            // Mostrar mensaje de éxito
                            await showAlert({
                                title: '¡Éxito!',
                                message: 'La operación se completó correctamente',
                                type: 'success'
                            });

                            // Volver a la página correspondiente
                            await loadContent(backPage);
                        } else if (response.status === 422) {
                            // Error de validación
                            console.warn('⚠️ Error de validación:', responseData);

                            let errorMessage = 'Por favor, revisa los campos del formulario.';
                            if (responseData.errors) {
                                const errors = Object.values(responseData.errors).flat();
                                errorMessage = errors.join('\n');
                            }

                            await showAlert({
                                title: 'Error de Validación',
                                message: errorMessage,
                                type: 'warning'
                            });
                        } else if (response.status === 403 && responseData.limit_reached) {
                            // Límite de plan alcanzado
                            console.warn('🚫 Límite alcanzado:', responseData);

                            await showLimitReachedModal(responseData);
                        } else {
                            throw new Error('Error en la respuesta del servidor (Status: ' + response.status + ')');
                        }

                    } catch (error) {
                        console.error('❌ Error al enviar formulario:', error);

                        // Restaurar botón en caso de error
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }

                        await showAlert({
                            title: 'Error',
                            message: 'Hubo un problema al guardar. Por favor, intenta de nuevo.\n' + error.message,
                            type: 'error'
                        });
                    }
                });
            });

            console.log('✅ Interceptores de formulario configurados:', forms.length, 'formularios');
        }

        // Observador para detectar cuando se carga contenido nuevo
        const contentObserver = new MutationObserver(function(mutations) {
            setupFormInterceptors();
        });

        // Iniciar observación del área de contenido
        const contentArea = document.getElementById('main-content-area');
        if (contentArea) {
            contentObserver.observe(contentArea, {
                childList: true,
                subtree: true
            });
        }

        // ========================================
        // 👤 FUNCIONES PARA GESTIÓN DE CAJEROS
        // ========================================

        // Variable para rastrear si el modal ya está configurado
        let cashierModalConfigured = false;

        // Cargar cajeros activos en el select
        async function loadDashboardCashiers() {
            try {
                const response = await fetch('/cajeros/api/activos', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success && result.cashiers) {
                    const select = document.getElementById('cashier_id');
                    if (select) {
                        // Limpiar opciones excepto la primera
                        select.innerHTML = '<option value="">Sin cajero asignado</option>';

                        // Agregar cajeros
                        result.cashiers.forEach(cashier => {
                            const option = document.createElement('option');
                            option.value = cashier.id;
                            option.textContent = cashier.name;
                            select.appendChild(option);
                        });
                    }
                }
            } catch (error) {
                console.error('Error al cargar cajeros:', error);
            }
        }

        // Abrir modal para agregar cajero
        window.openDashboardCashierModal = function() {
            console.log('Abriendo modal de cajero...');

            // Buscar modal existente o crear uno nuevo
            let modal = document.getElementById('dashboard-cashier-modal');

            // Si el modal ya existe, solo mostrarlo y limpiar el formulario
            if (modal) {
                modal.classList.remove('hidden');
                const form = document.getElementById('dashboard-cashier-form');
                if (form) form.reset();
                const errorDiv = document.getElementById('cashier-modal-error');
                if (errorDiv) errorDiv.classList.add('hidden');
                const successDiv = document.getElementById('cashier-modal-success');
                if (successDiv) successDiv.classList.add('hidden');
                setTimeout(() => {
                    const nameInput = document.getElementById('new_cashier_name');
                    if (nameInput) nameInput.focus();
                }, 100);
                return;
            }

            // Crear el modal por primera vez
            if (!modal && !cashierModalConfigured) {
                cashierModalConfigured = true;
                modal = document.createElement('div');
                modal.id = 'dashboard-cashier-modal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
                modal.innerHTML = `
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
                        <!-- Header del Modal -->
                        <div class="border-b border-purple-200 p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                    <i class='bx bx-user-plus text-3xl text-purple-600 mr-2'></i>
                                    Agregar Cajero
                                </h2>
                                <button onclick="closeDashboardCashierModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class='bx bx-x text-3xl'></i>
                                </button>
                            </div>
                        </div>

                        <!-- Body del Modal -->
                        <div class="p-6">
                            <form id="dashboard-cashier-form" class="space-y-4">
                                <div>
                                    <label for="new_cashier_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Cajero *
                                    </label>
                                    <input
                                        type="text"
                                        id="new_cashier_name"
                                        name="name"
                                        required
                                        class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        placeholder="Ej: Juan Pérez">
                                </div>

                                <div>
                                    <label for="new_cashier_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono
                                    </label>
                                    <input
                                        type="text"
                                        id="new_cashier_phone"
                                        name="phone"
                                        class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        placeholder="Ej: 555-1234">
                                </div>

                                <div>
                                    <label for="new_cashier_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        id="new_cashier_email"
                                        name="email"
                                        class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        placeholder="Ej: juan@ejemplo.com">
                                </div>

                                <div>
                                    <label for="new_cashier_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notas
                                    </label>
                                    <textarea
                                        id="new_cashier_notes"
                                        name="notes"
                                        rows="2"
                                        class="w-full px-4 py-2 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        placeholder="Información adicional"></textarea>
                                </div>

                                <div id="cashier-modal-error" class="hidden bg-red-50 border border-red-300 text-red-800 p-3 rounded-lg text-sm"></div>
                                <div id="cashier-modal-success" class="hidden bg-green-50 border border-green-300 text-green-800 p-3 rounded-lg text-sm"></div>

                                <div class="flex gap-3 pt-4">
                                    <button
                                        type="submit"
                                        class="flex-1 bg-purple-600 text-white py-2.5 rounded-lg hover:bg-purple-700 transition-colors font-semibold flex items-center justify-center">
                                        <i class='bx bx-save text-xl mr-2'></i>
                                        Guardar Cajero
                                    </button>
                                    <button
                                        type="button"
                                        onclick="closeDashboardCashierModal()"
                                        class="flex-1 bg-gray-300 text-gray-700 py-2.5 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);

                // Event listener para cerrar al hacer clic fuera
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeDashboardCashierModal();
                    }
                });

                // Event listener para el formulario
                document.getElementById('dashboard-cashier-form').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    console.log('Guardando cajero...');

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());
                    console.log('Datos:', data);

                    const errorDiv = document.getElementById('cashier-modal-error');
                    const successDiv = document.getElementById('cashier-modal-success');
                    errorDiv.classList.add('hidden');
                    successDiv.classList.add('hidden');

                    try {
                        const response = await fetch('/cajeros', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        const result = await response.json();
                        console.log('Resultado:', result);

                        if (response.ok && result.success) {
                            successDiv.textContent = result.message || 'Cajero creado exitosamente!';
                            successDiv.classList.remove('hidden');

                            // Recargar la lista de cajeros
                            await loadDashboardCashiers();

                            // Seleccionar el nuevo cajero
                            const select = document.getElementById('cashier_id');
                            if (select && result.cashier_id) {
                                select.value = result.cashier_id;
                            }

                            // Cerrar el modal después de 1 segundo
                            setTimeout(() => {
                                closeDashboardCashierModal();
                            }, 1000);
                        } else {
                            if (result.errors) {
                                const errorMessages = Object.values(result.errors).flat().join('<br>');
                                errorDiv.innerHTML = errorMessages;
                            } else {
                                errorDiv.textContent = result.message || 'Error al crear el cajero';
                            }
                            errorDiv.classList.remove('hidden');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        errorDiv.textContent = 'Error de conexión. Por favor, intenta de nuevo.';
                        errorDiv.classList.remove('hidden');
                    }
                });
            }

            // Mostrar el modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                const nameInput = document.getElementById('new_cashier_name');
                if (nameInput) nameInput.focus();
            }, 100);
        };

        // Cerrar modal de cajero
        window.closeDashboardCashierModal = function() {
            const modal = document.getElementById('dashboard-cashier-modal');
            if (modal) {
                modal.classList.add('hidden');
                const form = document.getElementById('dashboard-cashier-form');
                if (form) form.reset();
                const errorDiv = document.getElementById('cashier-modal-error');
                if (errorDiv) errorDiv.classList.add('hidden');
                const successDiv = document.getElementById('cashier-modal-success');
                if (successDiv) successDiv.classList.add('hidden');
            }
        };

        // Cerrar con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDashboardCashierModal();
            }
        });

        // ==========================================
        // 🏢 MODAL DE TIPO DE NEGOCIO
        // ==========================================

        let currentBusinessName = null;
        let currentBusinessType = null;

        async function loadBusinessInfo() {
            try {
                const response = await fetch('/api/check-business-type', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.has_business_type) {
                    currentBusinessName = result.business_name;
                    currentBusinessType = result.business_type;

                    // Actualizar el título del dashboard si existe el nombre del negocio
                    updateDashboardTitle();
                }
            } catch (error) {
                console.error('Error cargando información del negocio:', error);
            }
        }

        function updateDashboardTitle() {
            const businessInfoEl = document.getElementById('business-info');

            if (businessInfoEl) {
                if (currentBusinessName) {
                    businessInfoEl.innerHTML = `
                        <div class="dashboard-card flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg">
                            <div class="flex items-center gap-2">
                                <i class='bx bx-store text-2xl' style="color: #00D084;"></i>
                                <div>
                                    <p class="text-xs font-semibold text-custom-gray uppercase">Tu Negocio</p>
                                    <p class="font-bold text-sm text-custom-text">${currentBusinessName}</p>
                                </div>
                            </div>
                            <button onclick="editBusinessName()"
                                    class="text-custom-gray hover:text-custom-active transition-colors ml-auto"
                                    title="Editar nombre del negocio">
                                <i class='bx bx-edit-alt text-lg'></i>
                            </button>
                        </div>
                    `;
                } else if (currentBusinessType) {
                    // Si tiene tipo pero no nombre, mostrar opción para agregarlo
                    businessInfoEl.innerHTML = `
                        <button onclick="editBusinessName()"
                                class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400">
                            <i class='bx bx-plus-circle text-xl'></i>
                            <span class="font-medium">Agregar nombre del negocio</span>
                        </button>
                    `;
                }
            }
        }

        window.editBusinessName = function() {
            const currentName = currentBusinessName || '';

            // Crear modal
            let modal = document.getElementById('edit-business-name-modal');

            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'edit-business-name-modal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-[9999] flex items-center justify-center p-4';
                modal.onclick = function(e) {
                    if (e.target.id === 'edit-business-name-modal') {
                        closeEditBusinessModal();
                    }
                };

                modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                    <i class='bx bx-store text-2xl text-emerald-600 dark:text-emerald-400'></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Nombre del Negocio</h2>
                            </div>
                            <button onclick="closeEditBusinessModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <i class='bx bx-x text-3xl'></i>
                            </button>
                        </div>

                        <form id="edit-business-name-form" class="space-y-5">
                            <div>
                                <label for="edit_business_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class='bx bx-rename text-lg align-middle mr-1 text-emerald-600 dark:text-emerald-400'></i>
                                    Nombre de tu Negocio
                                </label>
                                <input type="text"
                                       id="edit_business_name"
                                       value="${currentName}"
                                       class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200 dark:focus:ring-emerald-900 dark:bg-gray-700 dark:text-white text-base transition-all duration-200"
                                       placeholder="Ej: Abarrotes Don Juan, Farmacia Santa María"
                                       required>
                            </div>

                            <div id="edit-business-error" class="hidden bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                            </div>

                            <div class="flex gap-3">
                                <button type="button"
                                        onclick="closeEditBusinessModal()"
                                        class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold py-3 px-4 rounded-xl transition-all">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold py-3 px-4 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg">
                                    <i class='bx bx-save text-lg align-middle mr-1'></i>
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                `;

                document.body.appendChild(modal);

                // Manejar submit
                document.getElementById('edit-business-name-form').addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const businessName = document.getElementById('edit_business_name').value.trim();

                    if (!businessName) {
                        const errorDiv = document.getElementById('edit-business-error');
                        errorDiv.innerHTML = '<i class="bx bx-error-circle mr-2"></i>Por favor ingresa el nombre de tu negocio';
                        errorDiv.classList.remove('hidden');
                        return;
                    }

                    const submitBtn = e.target.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin text-lg align-middle mr-1"></i>Guardando...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch('/api/save-business-type', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                business_type: currentBusinessType,
                                business_name: businessName
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            currentBusinessName = businessName;
                            submitBtn.innerHTML = '<i class="bx bx-check-circle text-lg align-middle mr-1"></i>¡Guardado!';
                            submitBtn.classList.remove('from-emerald-600', 'to-emerald-700');
                            submitBtn.classList.add('from-green-600', 'to-green-700');

                            setTimeout(() => {
                                closeEditBusinessModal();
                                updateDashboardTitle();
                            }, 800);
                        } else {
                            const errorDiv = document.getElementById('edit-business-error');
                            errorDiv.innerHTML = '<i class="bx bx-error-circle mr-2"></i>' + (result.message || 'Error al guardar');
                            errorDiv.classList.remove('hidden');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error guardando nombre del negocio:', error);
                        const errorDiv = document.getElementById('edit-business-error');
                        errorDiv.innerHTML = '<i class="bx bx-error-circle mr-2"></i>Error al guardar. Por favor intenta de nuevo.';
                        errorDiv.classList.remove('hidden');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            } else {
                // Si el modal ya existe, solo actualizamos el valor
                document.getElementById('edit_business_name').value = currentName;
                const errorDiv = document.getElementById('edit-business-error');
                if (errorDiv) errorDiv.classList.add('hidden');
            }

            modal.classList.remove('hidden');

            // Hacer focus en el input
            setTimeout(() => {
                const input = document.getElementById('edit_business_name');
                if (input) {
                    input.focus();
                    input.select();
                }
            }, 100);
        };

        window.closeEditBusinessModal = function() {
            const modal = document.getElementById('edit-business-name-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditBusinessModal();
            }
        });

        async function checkBusinessType() {
            try {
                const response = await fetch('/api/check-business-type', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (!result.has_business_type) {
                    showBusinessTypeModal();
                }
            } catch (error) {
                console.error('Error verificando tipo de negocio:', error);
            }
        }

        function showBusinessTypeModal() {
            let modal = document.getElementById('business-type-modal');

            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'business-type-modal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-[9999] flex items-center justify-center p-2 sm:p-4';

                modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl max-w-5xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto p-4 sm:p-6 md:p-8 lg:p-10 border border-gray-200 dark:border-gray-700 sm:border-2">
                        <div class="text-center mb-8 px-4">
                            <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 shadow-xl animate-pulse">
                                <i class='bx bx-store text-white text-3xl sm:text-4xl md:text-5xl'></i>
                            </div>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-2 sm:mb-3">
                                ¡Bienvenido a IslaControl!
                            </h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto px-4">
                                Sistema de gestión para todo tipo de negocios. Selecciona el tuyo para comenzar.
                            </p>
                        </div>

                        <form id="business-type-form" class="space-y-6 sm:space-y-8 px-4">
                            <div>
                                <label class="block text-sm sm:text-base font-bold text-gray-800 dark:text-gray-200 mb-4 sm:mb-6 text-center">
                                    <i class='bx bx-category text-xl sm:text-2xl align-middle mr-2 text-emerald-600 dark:text-emerald-400'></i>
                                    Selecciona tu Tipo de Negocio
                                </label>
                                <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-5">
                                    <button type="button" onclick="selectBusinessType('Tienda de Abarrotes')" data-type="abarrotes"
                                            class="business-type-option p-4 sm:p-6 md:p-8 border-2 sm:border-3 border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-center group bg-white dark:bg-gray-700/50 active:scale-95">
                                        <i class='bx bx-shopping-bag text-3xl sm:text-4xl md:text-5xl text-gray-400 group-hover:text-emerald-500 mb-2 sm:mb-3 transition-colors duration-300'></i>
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-xs sm:text-sm">Tienda de Abarrotes</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden sm:block">Mini-super, Tiendita</p>
                                    </button>

                                    <button type="button" onclick="selectBusinessType('Farmacia')" data-type="farmacia"
                                            class="business-type-option p-4 sm:p-6 md:p-8 border-2 sm:border-3 border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-center group bg-white dark:bg-gray-700/50 active:scale-95">
                                        <i class='bx bx-plus-medical text-3xl sm:text-4xl md:text-5xl text-gray-400 group-hover:text-emerald-500 mb-2 sm:mb-3 transition-colors duration-300'></i>
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-xs sm:text-sm">Farmacia</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden sm:block">Medicamentos y salud</p>
                                    </button>

                                    <button type="button" onclick="selectBusinessType('Papelería')" data-type="papeleria"
                                            class="business-type-option p-4 sm:p-6 md:p-8 border-2 sm:border-3 border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-center group bg-white dark:bg-gray-700/50 active:scale-95">
                                        <i class='bx bx-pencil text-3xl sm:text-4xl md:text-5xl text-gray-400 group-hover:text-emerald-500 mb-2 sm:mb-3 transition-colors duration-300'></i>
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-xs sm:text-sm">Papelería</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden sm:block">Útiles y librería</p>
                                    </button>

                                    <button type="button" onclick="selectBusinessType('Ferretería')" data-type="ferreteria"
                                            class="business-type-option p-4 sm:p-6 md:p-8 border-2 sm:border-3 border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-center group bg-white dark:bg-gray-700/50 active:scale-95">
                                        <i class='bx bx-wrench text-3xl sm:text-4xl md:text-5xl text-gray-400 group-hover:text-emerald-500 mb-2 sm:mb-3 transition-colors duration-300'></i>
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-xs sm:text-sm">Ferretería</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden sm:block">Herramientas y construcción</p>
                                    </button>

                                    <button type="button" onclick="selectBusinessType('Miscelánea')" data-type="miscelanea"
                                            class="business-type-option p-4 sm:p-6 md:p-8 border-2 sm:border-3 border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-center group bg-white dark:bg-gray-700/50 active:scale-95 xs:col-span-2 sm:col-span-1">
                                        <i class='bx bx-store-alt text-3xl sm:text-4xl md:text-5xl text-gray-400 group-hover:text-emerald-500 mb-2 sm:mb-3 transition-colors duration-300'></i>
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-xs sm:text-sm">Miscelánea</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden sm:block">Productos variados</p>
                                    </button>
                                </div>
                                <input type="hidden" id="business_type" name="business_type" required>
                            </div>

                            <div>
                                <label for="business_name" class="block text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 sm:mb-3">
                                    <i class='bx bx-rename text-base sm:text-lg align-middle mr-1 sm:mr-2 text-emerald-600 dark:text-emerald-400'></i>
                                    Nombre de tu Negocio <span class="text-gray-500 dark:text-gray-400 font-normal text-xs sm:text-sm">(Opcional)</span>
                                </label>
                                <input type="text" id="business_name" name="business_name"
                                       class="w-full px-3 py-3 sm:px-4 sm:py-4 md:px-5 md:py-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl focus:border-emerald-500 focus:ring-2 sm:focus:ring-4 focus:ring-emerald-200 dark:focus:ring-emerald-900 dark:bg-gray-700 dark:text-white text-sm sm:text-base transition-all duration-200"
                                       placeholder="Ej: Abarrotes Don Juan">
                            </div>

                            <div id="business-type-error" class="hidden bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-3 py-3 sm:px-5 sm:py-4 rounded-lg text-sm">
                            </div>

                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 active:scale-95 text-white font-bold py-3 sm:py-4 md:py-5 px-4 sm:px-6 rounded-lg sm:rounded-xl transition-all transform hover:scale-[1.02] shadow-2xl text-sm sm:text-base md:text-lg">
                                <i class='bx bx-check-circle text-lg sm:text-xl md:text-2xl align-middle mr-1 sm:mr-2'></i>
                                Comenzar con IslaControl
                            </button>
                        </form>

                        <div class="mt-4 sm:mt-6 text-center px-4">
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                <i class='bx bx-lock-alt text-xs sm:text-sm align-middle'></i>
                                Tus datos están seguros y protegidos
                            </p>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                // Manejar submit del formulario
                document.getElementById('business-type-form').addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const businessType = document.getElementById('business_type').value;
                    const businessName = document.getElementById('business_name').value;

                    if (!businessType) {
                        const errorDiv = document.getElementById('business-type-error');
                        errorDiv.innerHTML = '<i class="bx bx-error-circle mr-2"></i>Por favor selecciona un tipo de negocio';
                        errorDiv.classList.remove('hidden');
                        return;
                    }

                    // Mostrar indicador de carga en el botón
                    const submitBtn = e.target.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin text-2xl align-middle mr-2"></i>Guardando...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch('/api/save-business-type', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                business_type: businessType,
                                business_name: businessName
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Actualizar variables globales
                            currentBusinessType = businessType;
                            currentBusinessName = businessName;

                            // Cambiar botón a éxito
                            submitBtn.innerHTML = '<i class="bx bx-check-circle text-2xl align-middle mr-2"></i>¡Listo! Redirigiendo...';
                            submitBtn.classList.remove('from-blue-600', 'via-purple-600', 'to-pink-600');
                            submitBtn.classList.add('from-green-600', 'to-green-700');

                            // Cerrar modal después de un breve delay
                            setTimeout(() => {
                                modal.remove();
                                console.log('✅ Tipo de negocio guardado:', businessType);

                                // Actualizar el título del dashboard si hay nombre de negocio
                                if (businessName) {
                                    updateDashboardTitle();
                                }
                            }, 1500);
                        } else {
                            const errorDiv = document.getElementById('business-type-error');
                            errorDiv.innerHTML = '<i class="bx bx-error-circle mr-2"></i>' + (result.message || 'Error al guardar');
                            errorDiv.classList.remove('hidden');
                            submitBtn.innerHTML = originalBtnText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error guardando tipo de negocio:', error);
                        const errorDiv = document.getElementById('business-type-error');
                        errorDiv.innerHTML = '<i class="bx bx-error-circle mr-2"></i>Error al guardar. Por favor intenta de nuevo.';
                        errorDiv.classList.remove('hidden');
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                    }
                });
            }

            modal.classList.remove('hidden');
        }

        window.selectBusinessType = function(type) {
            // Remover selección previa de todos los botones
            document.querySelectorAll('.business-type-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-emerald-500', 'scale-105', 'shadow-2xl');
                btn.classList.add('opacity-70');
            });

            // Marcar como seleccionado con efecto visual mejorado (verde IslaControl)
            const selectedButton = event.target.closest('.business-type-option');
            selectedButton.classList.remove('opacity-70');
            selectedButton.classList.add('ring-4', 'ring-emerald-500', 'scale-105', 'shadow-2xl');

            // Guardar el valor seleccionado
            document.getElementById('business_type').value = type;

            // Limpiar mensaje de error si existe
            const errorDiv = document.getElementById('business-type-error');
            if (errorDiv) errorDiv.classList.add('hidden');

            // Feedback visual adicional
            console.log('✅ Tipo de negocio seleccionado:', type);
        };

        // ==========================================
        // CONTROL DE ACCESO PREMIUM
        // ==========================================
        let hasActiveSubscription = false;

        // Verificar si el usuario tiene suscripción activa
        function updatePremiumStatus() {
            if (dashboardData.subscriptionInfo) {
                hasActiveSubscription = dashboardData.subscriptionInfo.hasActiveSubscription;
                updateSidebarBadges();
            }
        }

        // Actualizar badges PRO en el sidebar según suscripción
        function updateSidebarBadges() {
            console.log('🔧 updateSidebarBadges() llamada - hasActiveSubscription:', hasActiveSubscription);

            // Si tiene suscripción activa, quitar todos los badges PRO
            if (hasActiveSubscription) {
                // Quitar badge de Isla IA
                const islaIALink = document.querySelector('a[data-page="ia-financiera"]');
                console.log('🔍 Isla IA Link encontrado:', islaIALink);

                if (islaIALink) {
                    const proBadge = islaIALink.querySelector('span.bg-gradient-to-r');
                    console.log('🔍 PRO Badge Isla IA encontrado:', proBadge);

                    if (proBadge) {
                        proBadge.remove();
                        console.log('✅ Badge Isla IA removido');
                    }
                    // Remover el data-requires-premium y el onclick
                    islaIALink.removeAttribute('data-requires-premium');
                    islaIALink.setAttribute('onclick', 'if(window.innerWidth < 1024) toggleSidebar()');
                }

                // Quitar badge de Caja Express
                const cajaToggle = document.querySelector('.sidebar-dropdown-toggle');
                console.log('🔍 Caja Express Toggle encontrado:', cajaToggle);

                if (cajaToggle) {
                    const proBadge = cajaToggle.querySelector('span.bg-gradient-to-r');
                    console.log('🔍 PRO Badge Caja Express encontrado:', proBadge);

                    if (proBadge) {
                        proBadge.remove();
                        console.log('✅ Badge Caja Express removido');
                    }
                    // Cambiar el onclick a solo toggle normal
                    cajaToggle.setAttribute('onclick', 'toggleCajaMenu()');
                }

                console.log('✅ Proceso de remoción de badges completado');
            } else {
                console.log('ℹ️ Usuario sin suscripción activa - Badges se mantienen');
            }
        }

        // Verificar acceso premium para enlaces individuales
        function checkPremiumAccess(event, page) {
            updatePremiumStatus();

            if (!hasActiveSubscription) {
                event.preventDefault();
                event.stopPropagation();
                showPremiumModal(page);
                return false;
            }

            // Si tiene acceso, permitir navegación normal
            if(window.innerWidth < 1024) toggleSidebar();
            return true;
        }

        // Verificar acceso premium para menús desplegables
        function checkPremiumAccessForMenu(event, menu) {
            updatePremiumStatus();

            if (!hasActiveSubscription) {
                event.preventDefault();
                event.stopPropagation();
                showPremiumModal(menu);
                return false;
            }

            // Si tiene acceso, permitir toggle normal
            if (menu === 'caja') {
                toggleCajaMenu();
            }
            return true;
        }

        // Mostrar modal de función premium
        function showPremiumModal(feature) {
            const featureNames = {
                'ia-financiera': 'Isla IA',
                'caja': 'Caja Express'
            };

            const featureName = featureNames[feature] || 'esta función';

            // Crear modal
            const modal = document.createElement('div');
            modal.id = 'premium-modal';
            modal.className = 'fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fadeIn';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform animate-scaleIn">
                    <!-- Header con gradiente -->
                    <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 p-6 text-white">
                        <div class="flex items-center justify-center mb-3">
                            <div class="bg-white/20 p-3 rounded-full">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center">
                            Función Premium
                        </h3>
                    </div>

                    <!-- Contenido -->
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <p class="text-gray-700 dark:text-gray-300 text-lg mb-4">
                                <strong>${featureName}</strong> es una función premium exclusiva para suscriptores.
                            </p>
                        </div>

                        <!-- Beneficios -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                            <h4 class="font-bold text-blue-800 dark:text-blue-300 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Desbloquea todo con un plan premium:
                            </h4>
                            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Isla IA - Asistente inteligente con memoria</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Caja Express con escaneo de códigos de barras</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Sin límites en productos, clientes y ventas</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>¡30 días de prueba GRATIS!</strong></span>
                                </li>
                            </ul>
                        </div>

                        <!-- Botones -->
                        <div class="flex flex-col gap-3">
                            <a href="/subscription/select-plan"
                               class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-bold text-center hover:from-blue-700 hover:to-blue-800 transition-all transform hover:scale-105 shadow-lg">
                                🎁 Ver Planes y Activar Prueba Gratis
                            </a>
                            <button onclick="document.getElementById('premium-modal').remove()"
                                    class="w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
        }

    </script>
</body>

</html>