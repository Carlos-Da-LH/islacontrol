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
            background-color: #0f172a;
        }

        .report-card {
            transition: all 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen pt-28 lg:pt-0">

    <!-- Navegación -->
    <nav class="bg-gradient-to-r from-emerald-600 to-green-700 shadow-lg mt-28 lg:mt-0">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-emerald-100 transition-colors">
                        <i class='bx bx-arrow-back text-2xl'></i>
                    </a>
                    <h1 class="text-2xl font-bold text-white">
                        <i class='bx bx-line-chart mr-2'></i>Reportes
                    </h1>
                </div>
                <div class="text-white">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mx-auto px-6 py-8">

        <!-- Corte de Caja -->
        <div class="max-w-4xl mx-auto">
            <div class="report-card bg-slate-800 rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-emerald-600 rounded-xl flex items-center justify-center mr-4">
                        <i class='bx bx-calculator text-white text-3xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Corte de Caja</h2>
                        <p class="text-gray-400">Genera el reporte diario de ventas</p>
                    </div>
                </div>

                <div class="bg-slate-700 rounded-xl p-6">
                    <form action="{{ route('reports.corte-caja') }}" method="POST" target="_blank">
                        @csrf

                        <div class="mb-4">
                            <label for="fecha" class="block text-sm font-medium text-gray-300 mb-2">
                                <i class='bx bx-calendar mr-1'></i>
                                Selecciona la fecha del corte
                            </label>
                            <input type="date"
                                   id="fecha"
                                   name="fecha"
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 bg-slate-600 border border-slate-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   required>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex space-x-3">
                                <button type="button"
                                        onclick="setFechaHoy()"
                                        class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-500 transition-colors text-sm">
                                    <i class='bx bx-time mr-1'></i>Hoy
                                </button>
                                <button type="button"
                                        onclick="setFechaAyer()"
                                        class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-500 transition-colors text-sm">
                                    <i class='bx bx-calendar-minus mr-1'></i>Ayer
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-gradient-to-r from-emerald-600 to-green-700 text-white font-bold py-4 rounded-lg hover:from-emerald-700 hover:to-green-800 transition-all shadow-lg hover:shadow-emerald-500/50 flex items-center justify-center">
                            <i class='bx bxs-file-pdf text-2xl mr-2'></i>
                            Generar Corte de Caja en PDF
                        </button>
                    </form>
                </div>

                <!-- Información adicional -->
                <div class="mt-6 p-4 bg-blue-900 bg-opacity-30 border border-blue-700 rounded-lg">
                    <h3 class="text-blue-300 font-semibold mb-2 flex items-center">
                        <i class='bx bx-info-circle mr-2'></i>
                        ¿Qué incluye el corte de caja?
                    </h3>
                    <ul class="text-gray-300 text-sm space-y-1">
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

            <!-- Más reportes (preparado para futuras funcionalidades) -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Reporte de Productos -->
                <div class="report-card bg-slate-800 rounded-2xl shadow-xl p-6 opacity-50">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-package text-white text-2xl'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Inventario</h3>
                            <p class="text-gray-400 text-sm">Próximamente</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm">Reporte detallado de productos y stock</p>
                </div>

                <!-- Reporte de Clientes -->
                <div class="report-card bg-slate-800 rounded-2xl shadow-xl p-6 opacity-50">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-user text-white text-2xl'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Clientes</h3>
                            <p class="text-gray-400 text-sm">Próximamente</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm">Reporte de ventas por cliente</p>
                </div>
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

        // Mensaje de confirmación cuando se genera el PDF
        document.querySelector('form').addEventListener('submit', function(e) {
            const fecha = document.getElementById('fecha').value;
            const fechaFormateada = new Date(fecha + 'T00:00:00').toLocaleDateString('es-MX', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            console.log('Generando corte de caja para:', fechaFormateada);

            // Opcional: Mostrar un mensaje temporal
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin text-2xl mr-2"></i>Generando PDF...';
            btn.disabled = true;

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 3000);
        });
    </script>
</body>
</html>
