<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00D084" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>IslaControl - Sistema de Gestión</title>
    <link rel="icon" type="image/png" href="/storage/logos/logo_islacontrol22.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyA8VguwL3jh2lIVpBSRrOvjy-c0PfmGD-4",
            authDomain: "isla-control.firebaseapp.com",
            projectId: "isla-control",
            storageBucket: "isla-control.firebasestorage.app",
            messagingSenderId: "145410754650",
            appId: "1:145410754650:web:8d590e161d280094a6f063",
            measurementId: "G-Z5RWFK99Q8"
        };

        if (firebase.apps.length === 0) {
            window.app = firebase.initializeApp(firebaseConfig);
            window.auth = firebase.auth();
            window.provider = new firebase.auth.GoogleAuthProvider();
        } else {
            window.auth = firebase.auth();
        }
    </script>

    <style>
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overscroll-behavior: none;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:active {
            transform: scale(0.95);
        }

        .island-card {
            transition: all 0.3s ease;
        }

        .island-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .island-card:active {
            transform: translateY(-2px) scale(0.98);
        }

        img {
            -webkit-user-drag: none;
            user-select: none;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Header -->
    <div class="bg-white py-4 px-4 shadow-md border-b border-gray-200">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/storage/logos/logo_islacontrol22.png" alt="IslaControl" class="w-12 h-12">
                    <h1 class="text-2xl font-bold text-emerald-600">IslaControl</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Redes Sociales en Header -->
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="https://www.facebook.com/profile.php?id=61584523778205" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition-all transform hover:scale-110"
                           title="Síguenos en Facebook">
                            <i class='bx bxl-facebook text-white text-lg'></i>
                        </a>
                        <a href="https://www.instagram.com/isla.control/" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 hover:from-purple-700 hover:via-pink-700 hover:to-orange-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110"
                           title="Síguenos en Instagram">
                            <i class='bx bxl-instagram text-white text-lg'></i>
                        </a>
                    </div>

                    @auth
                    <a href="{{ route('dashboard') }}" class="gradient-bg text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all">
                        <i class='bx bx-grid-alt'></i> Dashboard
                    </a>
                    @else
                    <button onclick="showAuthModal()" class="gradient-bg text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all">
                        <i class='bx bx-log-in'></i> Iniciar Sesión
                    </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">

        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-emerald-600 mb-2">Bienvenido a IslaControl</h2>
            <p class="text-gray-600 text-lg">Descubre todas las islas disponibles para gestionar tu negocio</p>
        </div>

        <!-- ¿Qué es IslaControl? -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 mb-12 border-l-4 border-emerald-500">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 mx-auto sm:mx-0">
                    <i class='bx bx-info-circle text-emerald-600 text-2xl sm:text-3xl'></i>
                </div>
                <div class="w-full">
                    <h3 class="text-xl sm:text-2xl font-bold text-emerald-600 mb-3 text-center sm:text-left">¿Qué es IslaControl?</h3>
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed mb-4 text-justify sm:text-left">
                        IslaControl es un <strong>sistema integral de gestión y punto de venta</strong> diseñado especialmente para pequeñas y medianas empresas.
                        Nuestra plataforma te permite administrar tu inventario, procesar ventas, gestionar clientes y obtener análisis inteligentes
                        de tu negocio, todo desde un solo lugar.
                    </p>
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed text-justify sm:text-left">
                        Con IslaControl, transformamos la manera en que manejas tu negocio, brindándote herramientas poderosas
                        pero fáciles de usar, accesibles desde cualquier dispositivo con conexión a internet.
                    </p>
                </div>
            </div>
        </div>

        <!-- Características Principales -->
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl shadow-lg p-8 mb-12">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-emerald-600 mb-2">Características Principales</h3>
                <p class="text-gray-600 text-lg">Todo lo que necesitas para hacer crecer tu negocio</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Característica 1 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <i class='bx bx-package text-emerald-600 text-2xl'></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Gestión de Inventario</h4>
                    <p class="text-gray-600">
                        Controla tu stock en tiempo real, recibe alertas de productos con bajo inventario y mantén tu negocio siempre abastecido.
                    </p>
                </div>

                <!-- Característica 2 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class='bx bx-store text-blue-600 text-2xl'></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Punto de Venta Rápido</h4>
                    <p class="text-gray-600">
                        Procesa ventas en segundos con nuestro POS intuitivo. Escanea códigos de barras, acepta múltiples formas de pago y genera tickets al instante.
                    </p>
                </div>

                <!-- Característica 3 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class='bx bx-user-circle text-purple-600 text-2xl'></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Base de Clientes</h4>
                    <p class="text-gray-600">
                        Registra y administra tu base de clientes, conoce su historial de compras y mejora la experiencia de cada cliente.
                    </p>
                </div>

                <!-- Característica 4 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mb-4 p-2 border border-gray-200">
                        <img src="/storage/logos/isla_ia.png" alt="Isla IA" class="w-full h-full object-contain">
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Isla IA - Asistente Inteligente</h4>
                    <p class="text-gray-600">
                        Obtén recomendaciones y análisis inteligentes de tu negocio. La IA te ayuda a tomar mejores decisiones basadas en tus datos.
                    </p>
                </div>

                <!-- Característica 5 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <i class='bx bx-bar-chart-alt-2 text-orange-600 text-2xl'></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Reportes y Análisis</h4>
                    <p class="text-gray-600">
                        Visualiza gráficas de ventas, productos más vendidos, tendencias y mucho más. Toma decisiones informadas con datos reales.
                    </p>
                </div>

                <!-- Característica 6 -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                        <i class='bx bx-mobile text-teal-600 text-2xl'></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">100% Responsive</h4>
                    <p class="text-gray-600">
                        Accede desde cualquier dispositivo: computadora, tablet o celular. Tu negocio siempre a tu alcance, donde y cuando lo necesites.
                    </p>
                </div>
            </div>
        </div>

        <!-- Islands Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            <!-- Isla de Gestión + Punto de Venta -->
            <div class="island-card bg-white rounded-2xl shadow-lg p-6 border-2 border-emerald-500 cursor-pointer" onclick="handleIslandClick('gestion')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl bg-white border-2 border-gray-200 flex items-center justify-center p-2">
                        <img src="/storage/logos/iconogestion.png" alt="Isla de Gestión" class="w-full h-full object-contain">
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">ACTIVO</span>
                </div>
                <h3 class="text-xl font-bold text-emerald-600 mb-2">Isla de Gestión + Punto de Venta</h3>
                <p class="text-gray-600 mb-4 text-sm">Sistema completo de inventario, ventas y gestión de clientes.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-emerald-500'></i>
                        <span>Gestión de Productos</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-emerald-500'></i>
                        <span>Punto de Venta (POS)</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-emerald-500'></i>
                        <span>Control de Inventario</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-emerald-500'></i>
                        <span>Base de Clientes</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-emerald-500'></i>
                        <span>Escaneo de Códigos</span>
                    </div>
                </div>
            </div>

            <!-- Isla IA -->
            <div class="island-card bg-white rounded-2xl shadow-lg p-6 border-2 border-pink-500 cursor-pointer" onclick="handleIslandClick('ia')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl bg-white border-2 border-gray-200 flex items-center justify-center p-2">
                        <img src="/storage/logos/isla_ia.png" alt="Isla IA" class="w-full h-full object-contain">
                    </div>
                    <span class="bg-pink-100 text-pink-700 text-xs font-bold px-3 py-1 rounded-full">ACTIVO</span>
                </div>
                <h3 class="text-xl font-bold text-emerald-600 mb-2">Isla IA</h3>
                <p class="text-gray-600 mb-4 text-sm">Asistente inteligente para analizar y optimizar tu negocio.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-pink-500'></i>
                        <span>Chat Inteligente</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-pink-500'></i>
                        <span>Análisis de Datos</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-pink-500'></i>
                        <span>Recomendaciones</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class='bx bx-check-circle text-pink-500'></i>
                        <span>Insights del Negocio</span>
                    </div>
                </div>
            </div>

            <!-- Isla de Planificación (Próximamente) -->
            <div class="island-card bg-white rounded-2xl shadow-lg p-6 border-2 border-gray-300 opacity-75">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                        <i class='bx bx-calendar-check text-white text-3xl'></i>
                    </div>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">PRÓXIMAMENTE</span>
                </div>
                <h3 class="text-xl font-bold text-emerald-600 mb-2">Isla de Planificación</h3>
                <p class="text-gray-600 mb-4 text-sm">Planifica y organiza las operaciones de tu negocio.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class='bx bx-time-five text-gray-400'></i>
                        <span>Calendario de Eventos</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class='bx bx-time-five text-gray-400'></i>
                        <span>Gestión de Tareas</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class='bx bx-time-five text-gray-400'></i>
                        <span>Recordatorios</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class='bx bx-time-five text-gray-400'></i>
                        <span>Objetivos y Metas</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pricing Section -->
        <div class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-emerald-600 mb-2">Planes y Precios</h2>
                <p class="text-gray-600 text-lg">Elige el plan que mejor se adapte a tu negocio</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">

                <!-- Plan Gratuito -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-gray-300">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-gift text-gray-600 text-3xl'></i>
                        </div>
                        <h3 class="text-2xl font-bold text-emerald-600 mb-2">Plan Básico</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-bold text-gray-800">$19</span>
                            <span class="text-gray-600">/mes</span>
                        </div>
                        <p class="text-sm text-emerald-600 font-semibold">30 días de prueba gratis</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">1 usuario</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Hasta 100 productos</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Hasta 100 clientes</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Hasta 100 ventas/mes</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Reportes básicos</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Isla IA incluida</span>
                        </div>
                    </div>

                    <button onclick="handlePlanSelection('basico')" class="w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition-all">
                        Seleccionar Plan
                    </button>
                </div>

                <!-- Plan Pro -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-emerald-500 relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-4 py-1 rounded-full">POPULAR</span>
                    </div>

                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-rocket text-emerald-600 text-3xl'></i>
                        </div>
                        <h3 class="text-2xl font-bold text-emerald-600 mb-2">Plan Pro</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-bold text-gray-800">$49</span>
                            <span class="text-gray-600">/mes</span>
                        </div>
                        <p class="text-sm text-gray-600">Para negocios en crecimiento</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">1 usuario</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Hasta 500 productos</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Hasta 500 clientes</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Hasta 500 ventas/mes</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Reportes avanzados</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Isla IA incluida</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-emerald-500'></i>
                            <span class="text-gray-700">Exportación de datos</span>
                        </div>
                    </div>

                    <button onclick="handlePlanSelection('pro')" class="w-full gradient-bg text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                        Seleccionar Plan
                    </button>
                </div>

                <!-- Plan Empresarial -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-purple-500">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-crown text-purple-600 text-3xl'></i>
                        </div>
                        <h3 class="text-2xl font-bold text-emerald-600 mb-2">Plan Empresarial</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-bold text-gray-800">$149</span>
                            <span class="text-gray-600">/mes</span>
                        </div>
                        <p class="text-sm text-gray-600">Para empresas establecidas</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700 font-semibold">Usuarios ilimitados</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700 font-semibold">Productos ilimitados</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700 font-semibold">Clientes ilimitados</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700 font-semibold">Ventas ilimitadas</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700">Reportes premium</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700">Isla IA incluida</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700">Soporte prioritario</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-check-circle text-purple-500'></i>
                            <span class="text-gray-700">Múltiples sucursales</span>
                        </div>
                    </div>

                    <button onclick="handlePlanSelection('empresarial')" class="w-full bg-purple-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-purple-600 transition-all">
                        Seleccionar Plan
                    </button>
                </div>

            </div>
        </div>

        <!-- CTA Section -->
        @guest
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-xl p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-3">¿Listo para comenzar?</h2>
            <p class="text-white/90 mb-6">Accede a todas las islas y empieza a gestionar tu negocio de forma profesional</p>
            <button onclick="showAuthModal()" class="bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all">
                <i class='bx bx-rocket'></i> Iniciar Sesión
            </button>
        </div>
        @else
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-xl p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-3">¡Bienvenido de nuevo!</h2>
            <p class="text-white/90 mb-6">Continúa gestionando tu negocio con IslaControl</p>
            <a href="{{ route('dashboard') }}" class="inline-block bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all">
                <i class='bx bx-grid-alt'></i> Ir al Dashboard
            </a>
        </div>
        @endguest

    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-200 py-8 px-4 mt-12">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <img src="/storage/logos/logo_islacontrol22.png" alt="IslaControl" class="w-10 h-10">
                    <p class="font-bold text-emerald-600 text-lg">IslaControl</p>
                </div>

                <!-- Redes Sociales -->
                <div class="flex flex-col items-center gap-3">
                    <p class="text-gray-600 font-semibold text-sm">Síguenos en redes sociales</p>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/profile.php?id=61584523778205" target="_blank" rel="noopener noreferrer"
                           class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition-all transform hover:scale-110 shadow-md">
                            <i class='bx bxl-facebook text-white text-xl'></i>
                        </a>
                        <a href="https://www.instagram.com/isla.control/" target="_blank" rel="noopener noreferrer"
                           class="w-10 h-10 bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 hover:from-purple-700 hover:via-pink-700 hover:to-orange-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110 shadow-md">
                            <i class='bx bxl-instagram text-white text-xl'></i>
                        </a>
                    </div>
                </div>

                <div class="flex gap-6 text-sm">
                    <a href="{{ route('legal.privacy') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">Privacidad</a>
                    <a href="{{ route('legal.terms') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">Términos</a>
                    <a href="{{ route('legal.contact') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">Contacto</a>
                    <a href="{{ route('legal.about') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">Acerca de</a>
                </div>
            </div>
            <div class="text-center text-gray-500 text-sm mt-6">
                &copy; {{ date('Y') }} IslaControl. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    @guest
    @include('components.auth-modal')
    @endguest

    <script>
        function handleIslandClick(island) {
            @auth
                // Usuario autenticado, ir al dashboard
                window.location.href = "{{ route('dashboard') }}";
            @else
                // Usuario no autenticado, mostrar modal de login
                showAuthModal();
            @endauth
        }

        function handlePlanSelection(plan) {
            @auth
                // Usuario autenticado, redirigir a checkout del plan
                window.location.href = "/suscripcion/checkout/" + plan;
            @else
                // Usuario no autenticado: guardar el plan y redirigir a registro
                // Guardar el plan seleccionado en sessionStorage
                sessionStorage.setItem('selected_plan', plan);

                // Redirigir a la página de registro
                window.location.href = "/register?plan=" + plan;
            @endauth
        }

        function showAuthModal() {
            const modal = document.getElementById('auth-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideAuthModal() {
            const modal = document.getElementById('auth-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
            }
        }

        function handleLogin(event) {
            event && event.preventDefault && event.preventDefault();

            const email = document.getElementById('email') ? document.getElementById('email').value : '';
            const password = document.getElementById('password') ? document.getElementById('password').value : '';
            const rememberMe = document.getElementById('remember-me') ? document.getElementById('remember-me').checked : false;

            const persistence = rememberMe ? firebase.auth.Auth.Persistence.LOCAL : firebase.auth.Auth.Persistence.SESSION;
            if (email && password && window.auth) {
                window.auth.setPersistence(persistence)
                    .then(() => {
                        return window.auth.signInWithEmailAndPassword(email, password);
                    })
                    .then((userCredential) => {
                        window.location.replace("{{ route('dashboard') }}");
                    })
                    .catch((error) => {
                        Toastify({
                            text: "Error: " + error.message,
                            duration: 3000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                        }).showToast();
                    });
            } else {
                Toastify({
                    text: "Por favor, ingresa tu email y contraseña.",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "linear-gradient(to right, #FBBF24, #FCD34D)",
                }).showToast();
            }
        }

        function handleForgotPassword(event) {
            event && event.preventDefault && event.preventDefault();

            const email = document.getElementById('email') ? document.getElementById('email').value : '';
            if (email && window.auth) {
                window.auth.sendPasswordResetEmail(email)
                    .then(() => {
                        Toastify({
                            text: "Correo enviado! Revisa tu bandeja de entrada.",
                            duration: 5000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #22C55E, #4ADE80)",
                        }).showToast();
                    })
                    .catch((error) => {
                        Toastify({
                            text: "Error: " + error.message,
                            duration: 5000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                        }).showToast();
                    });
            } else {
                Toastify({
                    text: "Por favor, ingresa tu email.",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "linear-gradient(to right, #FBBF24, #FCD34D)",
                }).showToast();
            }
        }
    </script>

</body>
</html>
