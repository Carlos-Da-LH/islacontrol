<!DOCTYPE html>
<html lang="es" class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
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

        /* ============================================
       MODO OSCURO - CATEGORÍAS
       ============================================ */

        /* TÍTULOS */
        html.dark h1,
        html.dark h2,
        html.dark h3 {
            color: #F9FAFB !important;
        }

        /* TABLA - Header */
        html.dark table thead tr th {
            color: #1F2937 !important;
            background-color: #F3F4F6 !important;
            font-weight: 700 !important;
        }

        /* TABLA - Cuerpo con fondo oscuro */
        html.dark table tbody {
            background-color: #1F2937 !important;
        }

        /* TABLA - TODAS las celdas en BLANCO */
        html.dark tbody tr td {
            color: #FFFFFF !important;
            background-color: transparent !important;
        }

        html.dark tbody td,
        html.dark tbody td *,
        html.dark tbody td span,
        html.dark tbody td div {
            color: #FFFFFF !important;
        }

        /* Hover en filas */
        html.dark table tbody tr:hover {
            background-color: rgba(139, 92, 246, 0.2) !important;
        }

        html.dark table tbody tr:hover td,
        html.dark table tbody tr:hover td * {
            color: #FFFFFF !important;
        }

        /* Estado vacío */
        html.dark #empty-state-row td {
            color: #9CA3AF !important;
        }
    </style>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen pt-52 lg:pt-8 pb-8 flex justify-center transition-colors duration-300">

    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden p-6 md:p-8 border border-gray-200 dark:border-gray-700">

            <div class="flex items-center justify-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                <svg class="w-8 h-8 mr-3 text-purple-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white text-center">
                    Gestión de Categorías
                </h1>
            </div>

            @if (session('success'))
            <div class="bg-purple-500 p-3 rounded-lg mb-4 text-white font-medium text-center shadow-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-6 p-4 rounded-xl bg-purple-500 shadow-xl border-b-4 border-purple-600 transform hover:scale-[1.005] transition duration-300 ease-in-out cursor-default">
                <div class="flex justify-between items-center">
                    <span class="text-base font-medium text-white uppercase tracking-wider opacity-90">Total de Categorías Registradas</span>
                    <svg class="w-8 h-8 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
                <p class="text-5xl font-extrabold text-white mt-1 drop-shadow-lg" id="total-categories-count">{{ $categories->count() }}</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="mb-8 p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-700/50 shadow-xl" id="category-form">
                @csrf
                <input type="hidden" name="from" value="dashboard">
                <h2 class="text-xl font-bold text-purple-600 dark:text-purple-400 mb-4 border-b border-gray-200 dark:border-gray-600 pb-2">
                    Agregar Nueva Categoría
                </h2>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Categoría</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            placeholder="Ej. Productos Electrónicos"
                            class="mt-1 block w-full rounded-lg shadow-sm light-input dark:bg-gray-600 dark:text-white dark:border-gray-500 text-sm h-9">
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" id="submit-button"
                        class="mt-6 w-full py-2.5 px-4 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white 
                                 bg-purple-600 hover:bg-purple-700 active:bg-purple-800 
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 
                                 transition duration-300 ease-in-out transform">
                        Guardar Categoría
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
                        placeholder="Buscar categorías..."
                        class="flex-1 bg-transparent text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none text-sm">
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                Lista de Categorías
            </h2>

            <div class="overflow-x-auto rounded-xl shadow-2xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[20%]">
                                ID</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-[60%]">
                                Nombre</th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-[20%]">
                                Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="category-table-body">
                        @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-purple-900/20 transition duration-150 ease-in-out border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-white">{{ $category->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <!-- Botón de Editar (Abre el modal) -->
                                <button type="button"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ e($category->name) }}"
                                    onclick="showEditModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white 
                     bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out mr-2">
                                    Editar
                                </button>

                                <!-- Botón para Eliminar -->
                                <button type="button"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ e($category->name) }}"
                                    onclick="showDeleteModal(this)"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-md text-white 
                     bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                    Eliminar
                                </button>

                                <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="from" value="dashboard">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-state-row">
                            <td colspan="3" class="px-6 py-8 text-center text-lg text-gray-500 dark:text-gray-400 font-medium">
                                No hay categorías registradas. ¡Usa el formulario de arriba para agregar una!
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

            const formatNumber = (number) => {
                return number.toString();
            };

            // Función para actualizar el contador de categorías
            const updateTotalCategories = () => {
                try {
                    let totalCount = 0;
                    const categoryRows = document.querySelectorAll('#category-table-body tr:not(#empty-state-row)');

                    categoryRows.forEach(row => {
                        if (row.style.display !== 'none') {
                            totalCount++;
                        }
                    });

                    const countElement = document.getElementById('total-categories-count');
                    if (countElement) {
                        countElement.textContent = totalCount;
                    }
                } catch (e) {
                    console.error('Error updating total:', e);
                }
            };

            // Función de filtrado del lado del cliente
            const filterTableCategories = () => {
                try {
                    const input = document.querySelector('input[name="search"]');
                    if (!input) {
                        console.warn('⚠️ Input de búsqueda no encontrado');
                        return;
                    }

                    const filter = input.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('#category-table-body tr:not(#empty-state-row)');

                    console.log('🔍 Filtrando categorías con término:', filter);

                    if (!filter) {
                        rows.forEach(row => row.style.display = '');
                        updateTotalCategories();
                        return;
                    }

                    let visibleCount = 0;

                    rows.forEach(row => {
                        const id = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                        const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';

                        const matches = id.includes(filter) || name.includes(filter);

                        row.style.display = matches ? '' : 'none';

                        if (matches) {
                            visibleCount++;
                        }
                    });

                    updateTotalCategories();
                    console.log('✅ Filtrado completado:', visibleCount, 'categorías visibles');
                } catch (e) {
                    console.error('❌ Error filtering categories:', e);
                }
            };

            function showNotification(message, type = 'info') {
                try {
                    const notification = document.createElement('div');
                    let baseClasses = 'p-4 rounded-lg shadow-xl text-white font-semibold transition-opacity duration-300';
                    let colorClasses = type === 'success' ? 'bg-purple-500' : type === 'error' ? 'bg-red-600' : 'bg-blue-500';

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
            }

            window.showEditModal = function(buttonElement) {
                try {
                    const id = buttonElement.dataset.categoryId;
                    const name = buttonElement.dataset.categoryName;

                    const modalContainer = document.getElementById('modal-container');
                    if (!modalContainer) return;

                    modalContainer.innerHTML = '';

                    const modal = document.createElement('div');
                    modal.id = 'edit-modal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-y-auto';

                    const formAction = '{{ route("categories.update", "") }}/' + id;
                    const csrfToken = '{{ csrf_token() }}';

                    modal.innerHTML = '<div class="modal-content bg-white p-8 rounded-2xl shadow-2xl max-w-2xl w-full border border-gray-200 my-8">' +
                        '<div class="flex items-center justify-center mb-6 border-b border-gray-200 pb-4">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-500 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>' +
                        '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>' +
                        '</svg>' +
                        '<h2 class="text-2xl font-extrabold text-gray-800">Editar Categoría</h2>' +
                        '</div>' +
                        '<form action="' + formAction + '" method="POST" class="p-6 border border-gray-200 rounded-xl bg-gray-50 shadow-xl">' +
                        '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                        '<input type="hidden" name="_method" value="PUT">' +
                        '<input type="hidden" name="from" value="dashboard">' +
                        '<h3 class="text-lg font-bold text-purple-600 mb-4 border-b border-gray-200 pb-2">Actualizar Datos de la Categoría #' + id + '</h3>' +
                        '<div class="grid grid-cols-1 gap-4">' +
                        '<div>' +
                        '<label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría</label>' +
                        '<input type="text" name="name" id="edit-name" value="' + name + '" required placeholder="Ej. Productos Electrónicos" class="mt-1 block w-full rounded-lg shadow-sm light-input text-sm h-10">' +
                        '</div>' +
                        '</div>' +
                        '<div class="mt-6 flex justify-end gap-3">' +
                        '<button type="submit" class="py-2.5 px-5 border border-transparent shadow-lg text-sm font-semibold rounded-lg text-white bg-purple-600 hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-300 ease-in-out transform hover:scale-[1.02]">Actualizar Categoría</button>' +
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
                    const id = buttonElement.dataset.categoryId;
                    const name = buttonElement.dataset.categoryName;

                    const modalContainer = document.getElementById('modal-container');
                    if (!modalContainer) return;

                    modalContainer.innerHTML = '';

                    const modal = document.createElement('div');
                    modal.id = 'confirmation-modal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50';

                    modal.innerHTML = '<div class="modal-content bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full border border-gray-200">' +
                        '<h3 class="text-xl font-bold text-red-600 mb-4 flex items-center">' +
                        '<svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />' +
                        '</svg>' +
                        'Confirmar Eliminación' +
                        '</h3>' +
                        '<p class="text-gray-800 text-lg font-medium mb-6">¿Estás seguro de que quieres eliminar la categoría "' + name + '"? Eliminar una categoría puede afectar a los productos asociados. Esta acción no se puede deshacer.</p>' +
                        '<div class="flex justify-end space-x-3">' +
                        '<button id="modal-cancel" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Cancelar</button>' +
                        '<button id="modal-confirm" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">Sí, Eliminar</button>' +
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
                        searchInput.addEventListener('input', filterTableCategories);
                        console.log('✅ Event listener de búsqueda configurado');
                    } else {
                        console.warn('⚠️ Input de búsqueda no encontrado');
                    }

                    // Prevenir envío del formulario (ya no necesitamos el botón Buscar)
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
                    updateTotalCategories();
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
</body>

</html>