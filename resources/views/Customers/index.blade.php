<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes Profesional</title>
    <!-- Carga de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/dark-mode.css">
    <script src="/js/dark-mode.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s;
        }

        .light-input {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #1f2937;
            transition: all 0.15s ease-in-out;
        }

        .light-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 1px #10b981;
            outline: none;
        }

        .save-button:hover {
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.7);
        }

        /* Animación para el modal */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content {
            animation: modalFadeIn 0.3s ease-out;
        }

        /* ============================================
   MODO OSCURO - MÁXIMA PRIORIDAD
   ============================================ */

        /* TÍTULOS Y ENCABEZADOS */
        html.dark h1,
        html.dark h2,
        html.dark h3,
        html.dark .text-3xl,
        html.dark .text-2xl,
        html.dark .text-xl {
            color: #F9FAFB !important;
        }

        /* VERDE: "Agregar Nuevo Cliente" */
        html.dark .text-green-600,
        html.dark .text-green-500,
        html.dark .text-green-400 {
            color: #34D399 !important;
        }

        /* LABELS DEL FORMULARIO */
        html.dark label {
            color: #E5E7EB !important;
        }

        /* ============================================
   TABLA - ULTRA FORZADO DE VISIBILIDAD
   ============================================ */

        /* Header de la tabla - FONDO CLARO, TEXTO OSCURO */
        html.dark table thead tr th {
            color: #1F2937 !important;
            background-color: #F3F4F6 !important;
            font-weight: 700 !important;
        }

        /* TBODY - Fondo oscuro */
        html.dark table tbody {
            background-color: #1F2937 !important;
        }

        /* TODAS LAS CELDAS - TEXTO BLANCO FORZADO */
        html.dark tbody tr td {
            color: #FFFFFF !important;
            background-color: transparent !important;
        }

        /* ULTRA ESPECÍFICO - Todo dentro de TD */
        html.dark tbody td,
        html.dark tbody td *,
        html.dark tbody td span,
        html.dark tbody td div,
        html.dark tbody td p,
        html.dark tbody td a {
            color: #FFFFFF !important;
        }

        /* Sobrescribir TODAS las clases de Tailwind */
        html.dark tbody .text-gray-900,
        html.dark tbody .text-gray-800,
        html.dark tbody .text-gray-700,
        html.dark tbody .text-gray-600,
        html.dark tbody .text-gray-500 {
            color: #FFFFFF !important;
        }

        /* Hover en filas */
        html.dark table tbody tr:hover {
            background-color: rgba(55, 65, 81, 0.5) !important;
        }

        html.dark table tbody tr:hover td,
        html.dark table tbody tr:hover td * {
            color: #FFFFFF !important;
        }

        html.dark .hover\:bg-gray-50:hover {
            background-color: rgba(55, 65, 81, 0.3) !important;
        }

        /* Badge "Cliente por Defecto" */
        html.dark .bg-gray-200 {
            background-color: #4B5563 !important;
            color: #E5E7EB !important;
        }

        html.dark .bg-gray-200 * {
            color: #E5E7EB !important;
        }

        /* Estado vacío */
        html.dark #empty-state-row td {
            color: #9CA3AF !important;
        }

        /* ============================================
   INPUTS Y FORMULARIOS
   ============================================ */

        html.dark input,
        html.dark textarea,
        html.dark select {
            background-color: #374151 !important;
            color: #F9FAFB !important;
            border-color: #4B5563 !important;
        }

        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #9CA3AF !important;
        }

        html.dark input:focus,
        html.dark textarea:focus,
        html.dark select:focus {
            border-color: #10B981 !important;
            box-shadow: 0 0 0 1px #10B981 !important;
        }

        /* ============================================
   BOTONES - NO AFECTAR EL TEXTO
   ============================================ */

        /* Botones Editar (Azul) */
        html.dark .bg-blue-600 {
            background-color: #2563EB !important;
        }

        html.dark .bg-blue-600,
        html.dark button.bg-blue-600,
        html.dark button.bg-blue-600 * {
            color: #FFFFFF !important;
        }

        /* Botones Eliminar (Rojo) */
        html.dark .bg-red-600 {
            background-color: #DC2626 !important;
        }

        html.dark .bg-red-600,
        html.dark button.bg-red-600,
        html.dark button.bg-red-600 * {
            color: #FFFFFF !important;
        }

        /* Botones Guardar (Verde) */
        html.dark .bg-green-600,
        html.dark .bg-emerald-600 {
            background-color: #10B981 !important;
        }

        html.dark .bg-green-600,
        html.dark .bg-green-600 *,
        html.dark .bg-emerald-600,
        html.dark .bg-emerald-600 *,
        html.dark button.bg-green-600,
        html.dark button.bg-green-600 * {
            color: #FFFFFF !important;
        }

        html.dark .bg-green-500,
        html.dark .bg-green-500 * {
            background-color: #10B981 !important;
            color: #FFFFFF !important;
        }

        /* ============================================
   BUSCADOR
   ============================================ */

        html.dark .placeholder-gray-400::placeholder {
            color: #9CA3AF !important;
        }

        /* Icono de búsqueda */
        html.dark svg {
            color: #9CA3AF;
        }

        /* SVG de encabezado en verde */
        html.dark .text-green-500,
        html.dark .text-green-400 {
            color: #34D399 !important;
        }

        /* ============================================
   FONDOS Y BORDES
   ============================================ */

        html.dark .bg-gray-50 {
            background-color: #374151 !important;
        }

        html.dark .border-gray-200 {
            border-color: #4B5563 !important;
        }

        html.dark .divide-gray-200>* {
            border-color: #4B5563 !important;
        }

        /* ============================================
   FORZADO NUCLEAR - ÚLTIMA OPCIÓN
   ============================================ */
        html.dark table.min-w-full tbody tr td {
            color: #FFFFFF !important;
        }

        html.dark table.min-w-full tbody tr td * {
            color: #FFFFFF !important;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen pt-52 lg:pt-8 pb-8 flex justify-center transition-colors duration-300">

    @include('components.limit-reached-modal')

    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden p-6 md:p-8 border border-gray-200 dark:border-gray-700">

            <div class="flex items-center justify-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500 dark:text-green-400 mr-3" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white text-center">
                    Gestión de Clientes
                </h1>
            </div>

            @if (session('success'))
            <div class="bg-green-500 p-3 rounded-lg mb-4 text-white font-medium text-center shadow-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-6 p-4 rounded-xl bg-green-500 shadow-xl border-b-4 border-green-600 transform hover:scale-[1.005] transition duration-300 ease-in-out cursor-default"
                id="daily-count-box">
                <div class="flex justify-between items-center">
                    <span class="text-base font-medium text-white uppercase tracking-wider opacity-90">Total de Clientes Actuales</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white opacity-80">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                </div>
                <p class="text-5xl font-extrabold text-white mt-1 drop-shadow-lg" id="client-count">{{ $customers->count() }}</p>
            </div>

            <form action="{{ route('customers.store') }}" method="POST" class="mb-8 p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-700/50 shadow-xl" id="customer-form">
                @csrf
                <input type="hidden" name="from" value="dashboard">
                <h2 class="text-xl font-bold text-green-600 dark:text-green-400 mb-4 border-b border-gray-200 dark:border-gray-600 pb-2">
                    Agregar Nuevo Cliente
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                    <div>
                        <label for="phone_number" class="block text-xs font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" id="submit-button"
                        class="save-button mt-6 w-full py-2.5 px-4 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white 
                                 bg-green-600 hover:bg-green-700 active:bg-green-800 
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 
                                 transition duration-300 ease-in-out transform">
                        Guardar Cliente
                    </button>
                </div>
            </form>

            <!-- Búsqueda del lado del servidor -->
            <!-- Búsqueda en tiempo real -->
            <div class="mb-4">
                <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        placeholder="Buscar por nombre, email o teléfono..."
                        class="flex-1 bg-transparent text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none text-sm">
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                Lista de Clientes
            </h2>

            <div class="overflow-x-auto rounded-xl shadow-2xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[5%]">
                                ID</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[30%]">
                                Nombre</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[20%]">
                                Teléfono</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[35%]">
                                Email</th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-[10%]">
                                Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="customer-table-body">
                        @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-white">
                                {{ $customer->name === 'Público General' ? '1' : $customer->id }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $customer->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $customer->phone_number ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $customer->email }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                @if($customer->name === 'Público General')
                                <!-- Badge para cliente protegido -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200">
                                    <i class='bx bx-lock-alt mr-1'></i>
                                    Cliente por Defecto
                                </span>
                                @else
                                <!-- Botón de Editar (Abre el modal) -->
                                <button type="button"
                                    data-customer-id="{{ $customer->id }}"
                                    data-customer-name="{{ e($customer->name) }}"
                                    data-customer-phone="{{ e($customer->phone_number) }}"
                                    data-customer-email="{{ e($customer->email) }}"
                                    onclick="showEditModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white
                         bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out mr-2">
                                    Editar
                                </button>

                                <!-- Botón para Eliminar -->
                                <button type="button"
                                    data-customer-id="{{ $customer->id }}"
                                    data-customer-name="{{ e($customer->name) }}"
                                    onclick="showDeleteModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white
                         bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                    Eliminar
                                </button>

                                <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="from" value="dashboard">
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-state-row">
                            <td colspan="5" class="px-6 py-8 text-center text-lg text-gray-500 dark:text-gray-400 font-medium">
                                No hay clientes registrados. ¡Usa el formulario de arriba para agregar uno!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Componente de paginación -->


            <div id="notification-container" class="fixed bottom-5 right-5 z-50 space-y-2"></div>
            <div id="modal-container"></div>

        </div>
    </div>

    <script>
        (function() {
            'use strict';

            // Función para actualizar el contador de clientes
            const updateTotalCustomers = () => {
                try {
                    let totalCount = 0;
                    const customerRows = document.querySelectorAll('#customer-table-body tr:not(#empty-state-row)');

                    customerRows.forEach(row => {
                        if (row.style.display !== 'none') {
                            totalCount++;
                        }
                    });

                    const countElement = document.getElementById('client-count');
                    if (countElement) {
                        countElement.textContent = totalCount;
                    }
                } catch (e) {
                    console.error('Error updating total:', e);
                }
            };

            // Función de filtrado del lado del cliente
            const filterTableCustomers = () => {
                try {
                    const input = document.querySelector('input[name="search"]');
                    if (!input) {
                        console.warn('⚠️ Input de búsqueda no encontrado');
                        return;
                    }

                    const filter = input.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('#customer-table-body tr:not(#empty-state-row)');

                    console.log('🔍 Filtrando clientes con término:', filter);

                    if (!filter) {
                        rows.forEach(row => row.style.display = '');
                        updateTotalCustomers();
                        return;
                    }

                    let visibleCount = 0;

                    rows.forEach(row => {
                        const id = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                        const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                        const phone = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                        const email = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';

                        const matches = id.includes(filter) ||
                            name.includes(filter) ||
                            phone.includes(filter) ||
                            email.includes(filter);

                        row.style.display = matches ? '' : 'none';

                        if (matches) {
                            visibleCount++;
                        }
                    });

                    updateTotalCustomers();
                    console.log('✅ Filtrado completado:', visibleCount, 'clientes visibles');
                } catch (e) {
                    console.error('❌ Error filtering customers:', e);
                }
            };

            window.showNotification = function(message, type = 'info') {
                try {
                    const notification = document.createElement('div');
                    let baseClasses = 'p-4 rounded-lg shadow-xl text-white font-semibold transition-opacity duration-300';
                    let colorClasses = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-600' : 'bg-blue-500';

                    notification.className = `${baseClasses} ${colorClasses}`;
                    notification.textContent = message;

                    const container = document.getElementById('notification-container');
                    if (container) {
                        container.appendChild(notification);

                        setTimeout(() => {
                            notification.style.opacity = '0';
                            setTimeout(() => notification.remove(), 300);
                        }, 3000);
                    }
                } catch (e) {
                    console.error('Error showing notification:', e);
                }
            };

            window.showEditModal = function(buttonElement) {
                try {
                    const id = buttonElement.dataset.customerId;
                    const name = buttonElement.dataset.customerName;
                    const phone = buttonElement.dataset.customerPhone;
                    const email = buttonElement.dataset.customerEmail;

                    const modalContainer = document.getElementById('modal-container');
                    if (!modalContainer) return;

                    modalContainer.innerHTML = '';

                    const modal = document.createElement('div');
                    modal.id = 'edit-modal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto';

                    const formAction = '{{ route("customers.update", "") }}/' + id;
                    const csrfToken = '{{ csrf_token() }}';

                    modal.innerHTML = '<div class="modal-content bg-white p-8 rounded-2xl shadow-2xl max-w-3xl w-full border border-gray-200 my-8">' +
                        '<div class="flex items-center justify-center mb-6 border-b border-gray-200 pb-4">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>' +
                        '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>' +
                        '</svg>' +
                        '<h2 class="text-2xl font-extrabold text-gray-800">Editar Cliente</h2>' +
                        '</div>' +
                        '<form action="' + formAction + '" method="POST" class="p-6 border border-gray-200 rounded-xl bg-gray-50 shadow-xl">' +
                        '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                        '<input type="hidden" name="_method" value="PUT">' +
                        '<input type="hidden" name="from" value="dashboard">' +
                        '<h3 class="text-lg font-bold text-green-600 mb-4 border-b border-gray-200 pb-2">Actualizar Datos del Cliente #' + id + '</h3>' +
                        '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">' +
                        '<div>' +
                        '<label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>' +
                        '<input type="text" name="name" id="edit-name" value="' + name + '" required class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                        '</div>' +
                        '<div>' +
                        '<label for="edit-phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>' +
                        '<input type="text" name="phone_number" id="edit-phone" value="' + (phone || '') + '" class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                        '</div>' +
                        '<div>' +
                        '<label for="edit-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>' +
                        '<input type="email" name="email" id="edit-email" value="' + email + '" required class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                        '</div>' +
                        '</div>' +
                        '<div class="mt-6 flex justify-end gap-3">' +
                        '<button type="submit" class="py-2.5 px-5 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:scale-[1.02]">Actualizar Cliente</button>' +
                        '<button type="button" onclick="window.closeEditModal()" class="py-2.5 px-5 text-sm font-medium rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-150 ease-in-out shadow-md">Cancelar</button>' +
                        '</div>' +
                        '</form>' +
                        '</div>';

                    modalContainer.appendChild(modal);

                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            window.closeEditModal();
                        }
                    });
                } catch (e) {
                    console.error('Error showing edit modal:', e);
                }
            };

            window.closeEditModal = function() {
                try {
                    const modal = document.getElementById('edit-modal');
                    if (modal) modal.remove();
                } catch (e) {
                    console.error('Error closing edit modal:', e);
                }
            };

            window.showDeleteModal = function(buttonElement) {
                try {
                    const id = buttonElement.dataset.customerId;
                    const name = buttonElement.dataset.customerName;

                    const modalContainer = document.getElementById('modal-container');
                    if (!modalContainer) return;

                    modalContainer.innerHTML = '';

                    const modal = document.createElement('div');
                    modal.id = 'confirmation-modal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50';

                    modal.innerHTML = '<div class="modal-content bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full border border-gray-200">' +
                        '<h3 class="text-xl font-bold text-red-600 mb-4">Confirmar Eliminación</h3>' +
                        '<p class="text-gray-800 text-lg font-medium mb-6">¿Estás seguro de que quieres eliminar a ' + name + '? Esta acción no se puede deshacer.</p>' +
                        '<div class="flex justify-end space-x-3">' +
                        '<button id="modal-cancel" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Cancelar</button>' +
                        '<button id="modal-confirm" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">Eliminar</button>' +
                        '</div>' +
                        '</div>';

                    modalContainer.appendChild(modal);

                    const confirmBtn = document.getElementById('modal-confirm');
                    const cancelBtn = document.getElementById('modal-cancel');

                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function() {
                            const form = document.getElementById('delete-form-' + id);
                            if (form) form.submit();
                            window.closeModal();
                        });
                    }

                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', window.closeModal);
                    }

                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            window.closeModal();
                        }
                    });
                } catch (e) {
                    console.error('Error showing delete modal:', e);
                }
            };

            window.closeModal = function() {
                try {
                    const modal = document.getElementById('confirmation-modal');
                    if (modal) modal.remove();
                } catch (e) {
                    console.error('Error closing modal:', e);
                }
            };

            // Inicialización
            const initializePage = () => {
                try {
                    // Agregar event listener para búsqueda en tiempo real
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput) {
                        searchInput.addEventListener('input', filterTableCustomers);
                        console.log('✅ Event listener de búsqueda configurado');
                    } else {
                        console.warn('⚠️ Input de búsqueda no encontrado');
                    }

                    // Prevenir envío del formulario
                    const searchForm = document.querySelector('form[method="GET"]');
                    if (searchForm) {
                        searchForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            console.log('🚫 Envío de formulario prevenido - usando filtrado del cliente');
                        });
                    }

                    // Agregar event listener para ESC
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            window.closeEditModal();
                            window.closeModal();
                        }
                    });

                    // Actualizar total al cargar
                    updateTotalCustomers();
                } catch (e) {
                    console.error('Error initializing page:', e);
                }
            };

            // Ejecutar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializePage);
            } else {
                initializePage();
            }

        })();
    </script>

</html>