<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Historial de Cajas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/css/dark-mode.css?v={{ time() }}">
    <script src="/js/dark-mode.js?v={{ time() }}"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at center, #1f2937, #111827);
        }
    </style>
</head>
<body class="text-white min-h-screen p-4 pt-52 lg:pt-4">

    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-700 mb-6 mt-28 lg:mt-0">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('dashboard') }}"
                    class="text-gray-400 hover:text-blue-400 transition-colors transform hover:scale-110 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight flex-grow text-center">
                    <i class='bx bx-history text-4xl text-blue-500 mr-2'></i>
                    Historial de Cajas
                </h1>
                <div class="w-10">
                    <!-- Indicador de script cargado -->
                    <span id="script-status" class="text-xs text-gray-600">⚠️</span>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-emerald-900/30 border border-emerald-600/50 rounded-lg p-3">
                    <p class="text-xs text-emerald-300 mb-1">🟢 Cajas Abiertas</p>
                    <p class="text-2xl font-bold text-emerald-200">{{ $cashRegisters->where('status', 'open')->count() }}</p>
                </div>
                <div class="bg-red-900/30 border border-red-600/50 rounded-lg p-3">
                    <p class="text-xs text-red-300 mb-1">🔒 Cajas Cerradas</p>
                    <p class="text-2xl font-bold text-red-200">{{ $cashRegisters->where('status', 'closed')->count() }}</p>
                </div>
                <div class="bg-blue-900/30 border border-blue-600/50 rounded-lg p-3">
                    <p class="text-xs text-blue-300 mb-1">💰 Total Vendido</p>
                    <p class="text-2xl font-bold text-blue-200">${{ number_format($cashRegisters->sum('total_sales'), 2) }}</p>
                </div>
                <div class="bg-purple-900/30 border border-purple-600/50 rounded-lg p-3">
                    <p class="text-xs text-purple-300 mb-1">📊 Total Cajas</p>
                    <p class="text-2xl font-bold text-purple-200">{{ $cashRegisters->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Botón Abrir Nueva Caja -->
        @if($cashRegisters->where('status', 'open')->isEmpty())
        <div class="mb-6">
            <a href="{{ route('cash-register.create') }}"
                class="w-full bg-emerald-600 text-white py-4 rounded-lg hover:bg-emerald-700 transition-colors font-bold text-lg shadow-lg flex items-center justify-center">
                <i class='bx bx-wallet text-2xl mr-2'></i>
                Abrir Nueva Caja
            </a>
        </div>
        @else
        <div class="bg-yellow-900/30 border border-yellow-600 text-yellow-100 p-4 rounded-xl mb-6">
            <div class="flex items-center">
                <i class='bx bx-info-circle text-2xl text-yellow-400 mr-3'></i>
                <div>
                    <strong class="font-bold">Caja Abierta Activa</strong>
                    <p class="text-sm">Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabla de Historial -->
        <div class="bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-700">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <i class='bx bx-list-ul text-blue-500 mr-2'></i>
                Registro de Cajas
            </h2>

            @if($cashRegisters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-600">
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">#</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Estado</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Cajero</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Apertura</th>
                                <th class="text-left py-3 px-2 text-gray-400 font-semibold">Cierre</th>
                                <th class="text-right py-3 px-2 text-gray-400 font-semibold">Fondo</th>
                                <th class="text-right py-3 px-2 text-gray-400 font-semibold">Ventas</th>
                                <th class="text-right py-3 px-2 text-gray-400 font-semibold">Diferencia</th>
                                <th class="text-center py-3 px-2 text-gray-400 font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashRegisters as $register)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition-colors">
                                <td class="py-3 px-2 text-white font-bold">#{{ $register->id }}</td>
                                <td class="py-3 px-2">
                                    @if($register->status === 'open')
                                        <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-xs font-semibold flex items-center w-fit">
                                            <i class='bx bx-check-circle mr-1'></i>
                                            Abierta
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-600 text-white rounded-full text-xs font-semibold flex items-center w-fit">
                                            <i class='bx bx-lock-alt mr-1'></i>
                                            Cerrada
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-gray-300">
                                    @if($register->cashier)
                                        <i class='bx bx-user text-blue-400 mr-1'></i>
                                        {{ $register->cashier->name }}
                                    @else
                                        <span class="text-gray-500">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-gray-300">
                                    {{ $register->opened_at->format('d/m/Y') }}<br>
                                    <span class="text-xs text-gray-500">{{ $register->opened_at->format('H:i') }}</span>
                                </td>
                                <td class="py-3 px-2 text-gray-300">
                                    @if($register->closed_at)
                                        {{ $register->closed_at->format('d/m/Y') }}<br>
                                        <span class="text-xs text-gray-500">{{ $register->closed_at->format('H:i') }}</span>
                                    @else
                                        <span class="text-yellow-400">En curso...</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-right text-white">${{ number_format($register->opening_balance, 2) }}</td>
                                <td class="py-3 px-2 text-right text-white font-bold">${{ number_format($register->total_sales, 2) }}</td>
                                <td class="py-3 px-2 text-right">
                                    @if($register->status === 'closed')
                                        @if($register->difference > 0)
                                            <span class="text-green-400 font-bold">+${{ number_format($register->difference, 2) }}</span>
                                        @elseif($register->difference < 0)
                                            <span class="text-red-400 font-bold">-${{ number_format(abs($register->difference), 2) }}</span>
                                        @else
                                            <span class="text-emerald-400 font-bold">$0.00</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                            class="btn-view-cash-register text-blue-400 hover:text-blue-300 transition-colors cursor-pointer"
                                            data-register-id="{{ $register->id }}"
                                            title="Ver detalles">
                                            <i class='bx bx-show text-xl'></i>
                                        </button>
                                        @if($register->status === 'open')
                                            <a href="{{ route('cash-register.close-form', $register->id) }}"
                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                title="Cerrar caja">
                                                <i class='bx bx-lock-alt text-xl'></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Componente de paginación -->
                @if($cashRegisters->hasPages() || count($paginationOptions) > 1)
                    <x-pagination-controls
                        :paginator="$cashRegisters"
                        :paginationOptions="$paginationOptions"
                    />
                @endif

            @else
                <div class="text-center py-12 text-gray-400">
                    <i class='bx bx-wallet text-6xl mb-3'></i>
                    <p class="text-lg mb-2">No hay cajas registradas</p>
                    <p class="text-sm">Abre tu primera caja para comenzar a operar</p>
                    <a href="{{ route('cash-register.create') }}"
                        class="inline-block mt-4 bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition-colors font-bold">
                        <i class='bx bx-wallet mr-2'></i>
                        Abrir Primera Caja
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- Modal de Detalles de Caja -->
    <div id="cashRegisterModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4" onclick="closeModalOnOutsideClick(event)">
        <div class="modal-container bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-gray-700" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6 flex items-center justify-between z-10">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center" id="modalTitle">
                    <i class='bx bx-wallet text-emerald-500 mr-2 text-3xl'></i>
                    Detalles de Caja
                </h2>
                <button onclick="closeModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <i class='bx bx-x text-3xl'></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div id="modalContent" class="p-6">
                <div class="flex items-center justify-center py-12">
                    <i class='bx bx-loader-alt bx-spin text-blue-500 text-6xl'></i>
                    <span class="ml-4 text-xl text-gray-600 dark:text-gray-400">Cargando detalles...</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos personalizados para paginación de Laravel */
        nav[role="navigation"] {
            margin-top: 1rem;
        }
        nav[role="navigation"] ul {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
        }
        nav[role="navigation"] ul li a,
        nav[role="navigation"] ul li span {
            padding: 0.5rem 1rem;
            background-color: #374151;
            color: #e5e7eb;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        nav[role="navigation"] ul li a:hover {
            background-color: #4b5563;
        }
        nav[role="navigation"] ul li .active {
            background-color: #10b981;
            color: white;
            font-weight: bold;
        }

        /* Modal dark mode support */
        .dark #cashRegisterModal .modal-container {
            background-color: #1f2937 !important;
        }

        .dark #cashRegisterModal .bg-gray-700\/50 {
            background-color: rgba(55, 65, 81, 0.5) !important;
        }

        /* Light mode adaptations for modal content */
        html:not(.dark) #modalContent .bg-gray-800 {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
        }

        html:not(.dark) #modalContent .bg-gray-700\/50 {
            background-color: #f3f4f6 !important;
        }

        html:not(.dark) #modalContent .text-white {
            color: #1f2937 !important;
        }

        html:not(.dark) #modalContent .text-gray-300,
        html:not(.dark) #modalContent .text-gray-400 {
            color: #6b7280 !important;
        }

        html:not(.dark) #modalContent .border-gray-600,
        html:not(.dark) #modalContent .border-gray-700 {
            border-color: #d1d5db !important;
        }

        html:not(.dark) #modalContent .hover\:bg-gray-700\/30:hover {
            background-color: #f9fafb !important;
        }

        /* Smooth modal animation */
        #cashRegisterModal {
            transition: opacity 0.3s ease;
        }

        #cashRegisterModal.show {
            opacity: 1;
        }

        #cashRegisterModal > div {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <script>
        console.log('⚡ SCRIPT CARGADO - Versión: ' + new Date().getTime());
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Función para abrir el modal y cargar los detalles
        async function showCashRegisterDetails(registerId) {
            console.log('🔍 Abriendo modal para caja ID:', registerId);

            const modal = document.getElementById('cashRegisterModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');

            // Mostrar modal con loading
            modal.classList.remove('hidden');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            console.log('✅ Modal abierto, cargando contenido...');

            try {
                // Hacer fetch al endpoint para obtener los detalles
                const url = `/caja/${registerId}`;
                console.log('📡 Haciendo fetch a:', url);

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                console.log('📥 Respuesta recibida, status:', response.status);

                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }

                const html = await response.text();
                console.log('📄 HTML recibido, tamaño:', html.length, 'caracteres');

                // Crear un contenedor temporal para parsear el HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Extraer el contenido principal
                const mainContent = doc.querySelector('body > div.max-w-7xl');
                console.log('🔍 Contenido principal encontrado:', !!mainContent);

                if (mainContent) {
                    // Extraer el número de caja del título
                    const title = doc.querySelector('h1');
                    if (title) {
                        modalTitle.innerHTML = title.innerHTML;
                        console.log('📝 Título actualizado:', title.textContent);
                    }

                    // Clonar el contenido
                    const contentClone = mainContent.cloneNode(true);

                    // Remover el header de navegación (primer hijo)
                    const headerNav = contentClone.querySelector('.bg-gray-800');
                    if (headerNav && headerNav.querySelector('a')) {
                        console.log('🗑️ Removiendo header de navegación');
                        headerNav.remove();
                    }

                    // Remover botones de acción del final
                    const actionButtons = contentClone.querySelector('.flex.gap-4.mb-6');
                    if (actionButtons) {
                        console.log('🗑️ Removiendo botones de acción');
                        actionButtons.remove();
                    }

                    modalContent.innerHTML = contentClone.innerHTML;
                    console.log('✅ Contenido cargado exitosamente');
                } else {
                    console.error('❌ No se encontró el contenedor principal');
                    modalContent.innerHTML = '<p class="text-center text-red-400 py-8">Error al cargar los detalles</p>';
                }

            } catch (error) {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="text-center py-12">
                        <i class='bx bx-error text-red-500 text-6xl mb-4'></i>
                        <p class="text-red-400 text-lg mb-2">Error al cargar los detalles</p>
                        <p class="text-gray-400 text-sm">${error.message}</p>
                    </div>
                `;
            }
        }

        // Función para cerrar el modal
        function closeModal() {
            const modal = document.getElementById('cashRegisterModal');
            modal.classList.add('hidden');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Cerrar modal al hacer clic fuera
        function closeModalOnOutsideClick(event) {
            if (event.target.id === 'cashRegisterModal') {
                closeModal();
            }
        }

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Event listeners para los botones de ver
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Página cargada, inicializando event listeners...');

            // Cambiar indicador visual
            const statusIndicator = document.getElementById('script-status');
            if (statusIndicator) {
                statusIndicator.textContent = '✅';
                statusIndicator.className = 'text-xs text-green-500';
            }

            // Agregar event listeners a todos los botones de ver
            const viewButtons = document.querySelectorAll('.btn-view-cash-register');
            console.log('🔘 Botones encontrados:', viewButtons.length);

            if (viewButtons.length === 0) {
                console.error('❌ NO SE ENCONTRARON BOTONES CON CLASE .btn-view-cash-register');
                if (statusIndicator) {
                    statusIndicator.textContent = '❌';
                    statusIndicator.className = 'text-xs text-red-500';
                }
                return;
            }

            viewButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const registerId = this.getAttribute('data-register-id');
                    console.log('🖱️ Click en botón, ID:', registerId);

                    showCashRegisterDetails(registerId);
                });
            });

            console.log('✅ Event listeners configurados correctamente');
        });
    </script>

</body>
</html>
