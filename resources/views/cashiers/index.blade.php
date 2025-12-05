<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cajeros</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/dark-mode.css">
    <script src="/js/dark-mode.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .light-input {
            background-color: #ffffff;
            border-color: #d1d5db;
            color: #1f2937;
            transition: all 0.15s ease-in-out;
        }

        .light-input:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 1px #a855f7;
            outline: none;
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
    </style>
</head>

<body class="bg-gray-100 min-h-screen pt-28 lg:pt-8 pb-8 flex justify-center">

    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden p-4 sm:p-6 lg:p-8 border border-gray-200">

            <div class="flex flex-col sm:flex-row items-center justify-center mb-6 border-b border-gray-200 pb-3 gap-2">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-800 text-center">
                    Gestión de Cajeros
                </h1>
            </div>

            @if (session('success'))
            <div class="bg-purple-500 p-3 rounded-lg mb-4 text-white font-medium text-center shadow-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-6 p-4 rounded-xl bg-purple-500 shadow-xl border-b-4 border-purple-600 transform hover:scale-[1.005] transition duration-300 ease-in-out cursor-default">
                <div class="flex justify-between items-center">
                    <span class="text-base font-medium text-white uppercase tracking-wider opacity-90">Total de Cajeros Registrados</span>
                    <svg class="w-8 h-8 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <p class="text-5xl font-extrabold text-white mt-1 drop-shadow-lg" id="total-cashiers-count">{{ count($cashiers) }}</p>
            </div>

            <form action="{{ route('cashiers.store') }}" method="POST" class="mb-8 p-6 border border-gray-200 rounded-xl bg-gray-50 shadow-xl" id="cashier-form">
                @csrf
                <h2 class="text-xl font-bold text-purple-600 mb-4 border-b border-gray-200 pb-2">
                    Agregar Nuevo Cajero
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nombre del Cajero *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            placeholder="Ej. Juan Pérez"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            placeholder="Ej. 555-1234"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="Ej. juan@example.com"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-medium text-gray-700 mb-1">Notas</label>
                        <input type="text" name="notes" id="notes" value="{{ old('notes') }}"
                            placeholder="Información adicional"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-9">
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" id="submit-button"
                        class="mt-6 w-full py-2.5 px-4 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white
                                 bg-purple-600 hover:bg-purple-700 active:bg-purple-800
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500
                                 transition duration-300 ease-in-out transform">
                        Guardar Cajero
                    </button>
                </div>
            </form>

            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                Lista de Cajeros
            </h2>

            <div class="overflow-x-auto rounded-xl shadow-2xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                ID</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Nombre</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Teléfono</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Email</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Estado</th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200" id="cashier-table-body">
                        @forelse ($cashiers as $cashier)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out border-b border-gray-200 last:border-b-0">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $cashier->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $cashier->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $cashier->phone ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $cashier->email ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($cashier->status === 'active')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <!-- Botón de Editar (Abre el modal) -->
                                <button type="button"
                                    data-cashier-id="{{ $cashier->id }}"
                                    data-cashier-name="{{ e($cashier->name) }}"
                                    data-cashier-phone="{{ e($cashier->phone) }}"
                                    data-cashier-email="{{ e($cashier->email) }}"
                                    data-cashier-status="{{ $cashier->status }}"
                                    data-cashier-notes="{{ e($cashier->notes) }}"
                                    onclick="showEditModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white
                                         bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out mr-2">
                                    Editar
                                </button>

                                <!-- Botón para Eliminar -->
                                <button type="button"
                                    data-cashier-id="{{ $cashier->id }}"
                                    data-cashier-name="{{ e($cashier->name) }}"
                                    onclick="showDeleteModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white
                                         bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                    Eliminar
                                </button>

                                <form id="delete-form-{{ $cashier->id }}" action="{{ route('cashiers.destroy', $cashier->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-state-row">
                            <td colspan="6" class="px-6 py-8 text-center text-lg text-gray-500 font-medium">
                                No hay cajeros registrados. ¡Usa el formulario de arriba para agregar uno!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="notification-container" class="fixed bottom-5 right-5 z-50 space-y-2"></div>
            <div id="modal-container"></div>

        </div>
    </div>

    <script>
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            let baseClasses = 'p-4 rounded-lg shadow-xl text-white font-semibold transition-opacity duration-300';
            let colorClasses;

            switch (type) {
                case 'success':
                    colorClasses = 'bg-purple-500';
                    break;
                case 'error':
                    colorClasses = 'bg-red-600';
                    break;
                default:
                    colorClasses = 'bg-blue-500';
            }

            notification.className = `${baseClasses} ${colorClasses}`;
            notification.textContent = message;

            document.getElementById('notification-container').appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        /**
         * Muestra el modal de edición de cajero
         */
        function showEditModal(buttonElement) {
            const id = buttonElement.dataset.cashierId;
            const name = buttonElement.dataset.cashierName;
            const phone = buttonElement.dataset.cashierPhone;
            const email = buttonElement.dataset.cashierEmail;
            const status = buttonElement.dataset.cashierStatus;
            const notes = buttonElement.dataset.cashierNotes;

            const modalContainer = document.getElementById('modal-container');
            modalContainer.innerHTML = '';

            const modal = document.createElement('div');
            modal.id = 'edit-modal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto';
            modal.innerHTML = `
                <div class="modal-content bg-white p-8 rounded-2xl shadow-2xl max-w-2xl w-full border border-gray-200 my-8">
                    <!-- Título con Icono -->
                    <div class="flex items-center justify-center mb-6 border-b border-gray-200 pb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-500 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <h2 class="text-2xl font-extrabold text-gray-800">Editar Cajero</h2>
                    </div>

                    <!-- Formulario de Edición -->
                    <form action="{{ route('cashiers.update', '') }}/${id}" method="POST" class="p-6 border border-gray-200 rounded-xl bg-gray-50 shadow-xl">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-bold text-purple-600 mb-4 border-b border-gray-200 pb-2">
                            Actualizar Datos del Cajero #${id}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Campo Nombre -->
                            <div>
                                <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Cajero *</label>
                                <input type="text" name="name" id="edit-name" value="${name}" required
                                    placeholder="Ej. Juan Pérez"
                                    class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">
                            </div>

                            <!-- Campo Teléfono -->
                            <div>
                                <label for="edit-phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input type="text" name="phone" id="edit-phone" value="${phone || ''}"
                                    placeholder="Ej. 555-1234"
                                    class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">
                            </div>

                            <!-- Campo Email -->
                            <div>
                                <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="edit-email" value="${email || ''}"
                                    placeholder="Ej. juan@example.com"
                                    class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">
                            </div>

                            <!-- Campo Estado -->
                            <div>
                                <label for="edit-status" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                                <select name="status" id="edit-status" required
                                    class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">
                                    <option value="active" ${status === 'active' ? 'selected' : ''}>Activo</option>
                                    <option value="inactive" ${status === 'inactive' ? 'selected' : ''}>Inactivo</option>
                                </select>
                            </div>

                            <!-- Campo Notas -->
                            <div class="md:col-span-2">
                                <label for="edit-notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                                <textarea name="notes" id="edit-notes" rows="2"
                                    placeholder="Información adicional"
                                    class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm">${notes || ''}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <!-- Botón Actualizar -->
                            <button type="submit"
                                class="py-2.5 px-5 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white
                                       bg-purple-600 hover:bg-purple-700 active:bg-purple-800
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500
                                       transition duration-300 ease-in-out transform hover:scale-[1.02]">
                                Actualizar Cajero
                            </button>

                            <!-- Botón Cancelar -->
                            <button type="button" onclick="closeEditModal()"
                                class="py-2.5 px-5 text-sm font-medium rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300
                                       transition duration-150 ease-in-out shadow-md">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            `;

            modalContainer.appendChild(modal);

            // Cerrar modal al hacer clic fuera del contenido
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeEditModal();
                }
            });
        }

        /**
         * Cierra el modal de edición
         */
        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            if (modal) modal.remove();
        }

        /**
         * Muestra el modal de confirmación de eliminación
         */
        function showDeleteModal(buttonElement) {
            const id = buttonElement.dataset.cashierId;
            const name = buttonElement.dataset.cashierName;

            const modalContainer = document.getElementById('modal-container');
            modalContainer.innerHTML = '';

            const modal = document.createElement('div');
            modal.id = 'confirmation-modal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 transition-opacity duration-300';
            modal.innerHTML = `
                <div class="modal-content bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full border border-gray-200">
                    <h3 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Confirmar Eliminación
                    </h3>
                    <p class="text-gray-800 text-lg font-medium mb-6">¿Estás seguro de que quieres eliminar al cajero "${name}"? Esta acción no se puede deshacer.</p>
                    <div class="flex justify-end space-x-3">
                        <button id="modal-cancel" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Cancelar
                        </button>
                        <button id="modal-confirm" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">
                            Sí, Eliminar
                        </button>
                    </div>
                </div>
            `;

            modalContainer.appendChild(modal);

            document.getElementById('modal-confirm').addEventListener('click', function() {
                document.getElementById('delete-form-' + id).submit();
                closeModal();
            });
            document.getElementById('modal-cancel').addEventListener('click', closeModal);

            // Cerrar modal al hacer clic fuera
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        /**
         * Cierra el modal de confirmación
         */
        function closeModal() {
            const modal = document.getElementById('confirmation-modal');
            if (modal) modal.remove();
        }

        // Cerrar modales con la tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closeModal();
            }
        });
    </script>
</body>

</html>
