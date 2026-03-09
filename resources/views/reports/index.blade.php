<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reportes - IslaControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/css/dark-mode.css">
    <script src="/js/dark-mode.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .report-card {
            transition: all 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }


        /* ============================================
   MODO CLARO - TEXTOS NEGROS
   ============================================ */

        /* Caja azul en modo CLARO - textos NEGROS */
        .bg-blue-50 h3 {
            color: #1e40af !important;
        }

        .bg-blue-50 ul li,
        .bg-blue-50 ul li span {
            color: #1F2937 !important;
        }

        /* ============================================
   MODO OSCURO - REPORTES
   ============================================ */

        /* Títulos principales */
        html.dark h1,
        html.dark h2,
        html.dark h3 {
            color: #F9FAFB !important;
        }

        /* Textos descriptivos */
        html.dark p {
            color: #D1D5DB !important;
        }

        /* Labels */
        html.dark label {
            color: #E5E7EB !important;
        }

        /* Input de fecha */
        html.dark input[type="date"] {
            background-color: #334155 !important;
            color: #F9FAFB !important;
            border-color: #475569 !important;
        }

        /* ===== BOTONES HOY/AYER ===== */
        /* Modo CLARO - NEGRO sobre GRIS */
        button.text-gray-900 {
            background-color: #E5E7EB !important;
            color: #1F2937 !important;
        }

        button.text-gray-900:hover {
            background-color: #D1D5DB !important;
        }

        /* Modo OSCURO - BLANCO sobre GRIS OSCURO */
        html.dark button.dark\:bg-slate-600 {
            background-color: #475569 !important;
            color: #F9FAFB !important;
        }

        html.dark button.dark\:bg-slate-600:hover {
            background-color: #64748b !important;
        }

        /* Botón PDF verde - SIEMPRE BLANCO */
        button#generate-pdf-btn,
        button.bg-gradient-to-r,
        button.bg-gradient-to-r * {
            color: #FFFFFF !important;
        }

        /* ===== CAJA AZUL DE INFORMACIÓN - MODO OSCURO ===== */
        html.dark .bg-blue-50 {
            background-color: rgba(30, 58, 138, 0.3) !important;
        }

        html.dark .border-blue-200 {
            border-color: #1e40af !important;
        }

        html.dark .text-blue-700 {
            color: #93C5FD !important;
        }

        /* Lista en modo OSCURO */
        html.dark ul li,
        html.dark ul li span {
            color: #D1D5DB !important;
        }

        /* Cards de Próximamente */
        html.dark .text-gray-600 {
            color: #9CA3AF !important;
        }

        html.dark .text-gray-700 {
            color: #D1D5DB !important;
        }

        /* Iconos en botones */
        button i.bx {
            color: inherit !important;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-slate-900 min-h-screen pt-52 lg:pt-0 transition-colors duration-300">

    <!-- Navegación -->
    <nav class="bg-gradient-to-r from-emerald-600 to-green-700 shadow-lg mt-28 lg:mt-0">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-900 dark:text-white hover:text-emerald-100 transition-colors">
                        <i class='bx bx-arrow-back text-2xl'></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <i class='bx bx-line-chart mr-2'></i>Reportes
                    </h1>
                </div>
                <div class="text-gray-900 dark:text-white">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mx-auto px-6 py-8">

        <!-- Mensajes de error/éxito -->
        @if(session('error'))
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Corte de Caja -->
        <div class="max-w-4xl mx-auto">
            <div class="report-card bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 mb-8 transition-colors duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-emerald-600 rounded-xl flex items-center justify-center mr-4">
                        <i class='bx bx-calculator text-white text-3xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Corte de Caja</h2>
                        <p class="text-gray-600 dark:text-gray-400">Genera el reporte diario de ventas</p>
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-slate-700 rounded-xl p-6 transition-colors duration-300">
                    <div class="mb-4">
                        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class='bx bx-calendar mr-1'></i>
                            Selecciona la fecha del corte
                        </label>
                        <input type="date"
                            id="fecha"
                            name="fecha"
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 bg-white dark:bg-slate-600 border border-gray-300 dark:border-slate-500 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors duration-300"
                            required>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex space-x-3">
                            <button type="button"
                                onclick="setFechaHoy()"
                                class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition-colors text-sm font-medium">
                                <i class='bx bx-time mr-1'></i>Hoy
                            </button>
                            <button type="button"
                                onclick="setFechaAyer()"
                                class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition-colors text-sm font-medium">
                                <i class='bx bx-calendar-minus mr-1'></i>Ayer
                            </button>
                        </div>
                    </div>

                    <button type="button"
                        id="generate-pdf-btn"
                        onclick="generarPDF()"
                        class="w-full bg-gradient-to-r from-emerald-600 to-green-700 text-white font-bold py-4 rounded-lg hover:from-emerald-700 hover:to-green-800 transition-all shadow-lg hover:shadow-emerald-500/50 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class='bx bxs-file-pdf text-2xl mr-2'></i>
                        Generar Corte de Caja en PDF
                    </button>
                </div>

                <!-- Información adicional -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg">
                    <h3 class="text-blue-700 dark:text-blue-300 font-semibold mb-2 flex items-center">
                        <i class='bx bx-info-circle mr-2'></i>
                        ¿Qué incluye el corte de caja?
                    </h3>
                    <ul class="text-gray-700 dark:text-gray-300 text-sm space-y-1">
                        <li class="flex items-start">
                            <i class='bx bx-check text-emerald-500 mr-2 mt-0.5'></i>
                            <span>Total de ventas del día</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-check text-emerald-500 mr-2 mt-0.5'></i>
                            <span>Número de tickets procesados</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-check text-emerald-500 mr-2 mt-0.5'></i>
                            <span>Productos más vendidos</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-check text-emerald-500 mr-2 mt-0.5'></i>
                            <span>Detalle completo de cada venta</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Más reportes -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="report-card bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 opacity-50">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-package text-white text-2xl'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Inventario</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Próximamente</p>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">Reporte detallado de productos y stock</p>
                </div>

                <div class="report-card bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 opacity-50">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-user text-white text-2xl'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Clientes</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Próximamente</p>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">Reporte de ventas por cliente</p>
                </div>
            </div>
        </div>

        <script>
            function setFechaHoy() {
                const hoy = new Date();
                document.getElementById('fecha').value = hoy.toISOString().split('T')[0];
            }

            function setFechaAyer() {
                const ayer = new Date();
                ayer.setDate(ayer.getDate() - 1);
                document.getElementById('fecha').value = ayer.toISOString().split('T')[0];
            }

            // 🔥 SOLUCIÓN DEFINITIVA: Bypass del interceptor
            function generarPDF() {
                const fecha = document.getElementById('fecha').value;

                if (!fecha) {
                    alert('Por favor selecciona una fecha');
                    return;
                }

                console.log('🔄 Generando PDF para fecha:', fecha);

                // Deshabilitar el botón
                const btn = document.getElementById('generate-pdf-btn');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader-alt animate-spin text-2xl mr-2"></i>Generando PDF...';

                // ⭐ MÉTODO 1: Crear formulario invisible que NO será interceptado
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route("reports.corte-caja-pdf") }}';
                form.target = '_blank';
                form.style.display = 'none';

                // 🚫 Marcar este formulario para que NO sea interceptado
                form.setAttribute('data-no-intercept', 'true');
                form.setAttribute('data-bypass-ajax', 'true');
                form.classList.add('pdf-download-form'); // Clase especial

                // Agregar campo de fecha
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'fecha';
                input.value = fecha;
                form.appendChild(input);

                // Agregar al DOM temporalmente
                document.body.appendChild(form);

                // Enviar usando el método nativo del navegador
                form.submit();

                // Limpiar después
                setTimeout(() => {
                    document.body.removeChild(form);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 2000);
            }
        </script>
</body>

</html>