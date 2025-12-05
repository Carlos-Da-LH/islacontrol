<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abrir Caja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at center, #1f2937, #111827);
        }
    </style>
</head>
<body class="text-white flex items-center justify-center min-h-screen p-4 pt-32 lg:pt-4">

    <div class="bg-gray-800 p-8 md:p-10 rounded-3xl shadow-[0_0_30px_rgba(16,185,129,0.15)] w-full max-w-2xl border border-gray-700">

        <!-- Header -->
        <div class="mb-8 pb-4 border-b border-emerald-600/50 flex items-center justify-between">
            <a href="{{ route('dashboard') }}"
                class="text-gray-400 hover:text-emerald-400 transition-colors transform hover:scale-110 p-2 rounded-full -ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight flex-grow text-center pr-10">
                <i class='bx bx-wallet text-4xl text-emerald-500 mr-2'></i>
                Abrir Caja
            </h1>
        </div>

        <!-- Información -->
        <div class="bg-emerald-900/30 border border-emerald-600/50 text-emerald-100 p-4 rounded-xl mb-6 shadow-inner">
            <div class="flex items-start">
                <i class='bx bx-info-circle text-2xl text-emerald-400 mr-3 mt-0.5'></i>
                <div>
                    <strong class="font-bold block mb-1">Apertura de Caja</strong>
                    <p class="text-sm">Ingresa el fondo inicial con el que vas a comenzar el turno. Este monto debe coincidir con el efectivo físico que tienes en la caja.</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-100 p-4 rounded-xl mb-6 shadow-inner" role="alert">
                <strong class="font-extrabold block mb-1 text-sm">¡Error de Validación!</strong>
                <ul class="list-disc list-inside space-y-0.5 ml-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('cash-register.store') }}" class="space-y-6">
            @csrf

            <!-- Cajero -->
            <div>
                <label for="cashier_id" class="block text-sm font-medium text-gray-300 mb-2">
                    <i class='bx bx-user text-blue-400 mr-1'></i>
                    Cajero (Opcional)
                </label>
                <select
                    id="cashier_id"
                    name="cashier_id"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3">
                    <option value="">Sin cajero asignado</option>
                    @foreach($cashiers as $cashier)
                        <option value="{{ $cashier->id }}" {{ old('cashier_id') == $cashier->id ? 'selected' : '' }}>
                            {{ $cashier->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Botón para agregar nuevo cajero -->
                <button
                    type="button"
                    onclick="openNewCashierModal()"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2.5 px-4 rounded-lg transition-colors font-semibold flex items-center justify-center gap-2 shadow-lg">
                    <i class='bx bx-user-plus text-xl'></i>
                    <span>Agregar Nuevo Cajero</span>
                </button>

                <p class="text-xs text-gray-400 mt-2 text-center">¿No encuentras tu cajero? Agrégalo con el botón de arriba</p>
            </div>

            <!-- Fondo Inicial -->
            <div>
                <label for="opening_balance" class="block text-sm font-medium text-gray-300 mb-2">
                    <i class='bx bx-money text-emerald-500 mr-1'></i>
                    Fondo Inicial (Efectivo) *
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl font-bold">$</span>
                    <input
                        type="number"
                        id="opening_balance"
                        name="opening_balance"
                        step="0.01"
                        min="0"
                        value="{{ old('opening_balance', '500.00') }}"
                        required
                        autofocus
                        class="w-full pl-10 pr-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-lg font-semibold"
                        placeholder="0.00">
                </div>
                <p class="text-xs text-gray-400 mt-2">Ejemplo: 500.00 (el efectivo que tienes en la caja para dar cambio)</p>
            </div>

            <!-- Notas de Apertura -->
            <div>
                <label for="opening_notes" class="block text-sm font-medium text-gray-300 mb-2">
                    <i class='bx bx-note text-emerald-500 mr-1'></i>
                    Notas de Apertura (Opcional)
                </label>
                <textarea
                    id="opening_notes"
                    name="opening_notes"
                    rows="3"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    placeholder="Ej: Turno matutino, billetes de $100 y $200...">{{ old('opening_notes') }}</textarea>
            </div>

            <!-- Información de Usuario y Fecha -->
            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400">Usuario:</span>
                        <p class="font-semibold text-white">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400">Fecha:</span>
                        <p class="font-semibold text-white">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <button
                    type="submit"
                    class="flex-1 bg-emerald-600 text-white py-3 rounded-lg hover:bg-emerald-700 transition-colors font-bold text-lg shadow-lg flex items-center justify-center">
                    <i class='bx bx-check-circle text-2xl mr-2'></i>
                    Abrir Caja
                </button>
                <a
                    href="{{ route('dashboard') }}"
                    class="flex-1 bg-gray-600 text-white py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold text-lg shadow-lg text-center flex items-center justify-center">
                    <i class='bx bx-x text-2xl mr-2'></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Modal para Agregar Nuevo Cajero -->
    <div id="newCashierModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border border-gray-700">
            <!-- Header del Modal -->
            <div class="border-b border-purple-600/50 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class='bx bx-user-plus text-3xl text-purple-500 mr-2'></i>
                        Agregar Cajero
                    </h2>
                    <button onclick="closeNewCashierModal()" class="text-gray-400 hover:text-white transition-colors">
                        <i class='bx bx-x text-3xl'></i>
                    </button>
                </div>
            </div>

            <!-- Body del Modal -->
            <div class="p-6">
                <form id="newCashierForm" class="space-y-4">
                    <div>
                        <label for="new_cashier_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Nombre del Cajero *
                        </label>
                        <input
                            type="text"
                            id="new_cashier_name"
                            name="name"
                            required
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: Juan Pérez">
                    </div>

                    <div>
                        <label for="new_cashier_phone" class="block text-sm font-medium text-gray-300 mb-2">
                            Teléfono
                        </label>
                        <input
                            type="text"
                            id="new_cashier_phone"
                            name="phone"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: 555-1234">
                    </div>

                    <div>
                        <label for="new_cashier_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            id="new_cashier_email"
                            name="email"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ej: juan@ejemplo.com">
                    </div>

                    <div>
                        <label for="new_cashier_notes" class="block text-sm font-medium text-gray-300 mb-2">
                            Notas
                        </label>
                        <textarea
                            id="new_cashier_notes"
                            name="notes"
                            rows="2"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Información adicional"></textarea>
                    </div>

                    <div id="modal-error" class="hidden bg-red-900 border border-red-700 text-red-100 p-3 rounded-lg text-sm"></div>
                    <div id="modal-success" class="hidden bg-green-900 border border-green-700 text-green-100 p-3 rounded-lg text-sm"></div>

                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            class="flex-1 bg-purple-600 text-white py-2.5 rounded-lg hover:bg-purple-700 transition-colors font-semibold flex items-center justify-center">
                            <i class='bx bx-save text-xl mr-2'></i>
                            Guardar Cajero
                        </button>
                        <button
                            type="button"
                            onclick="closeNewCashierModal()"
                            class="flex-1 bg-gray-600 text-white py-2.5 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Hacer las funciones globales para que funcionen dentro del dashboard
        window.openNewCashierModal = function() {
            console.log('Abriendo modal de cajero...');
            const modal = document.getElementById('newCashierModal');
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    const nameInput = document.getElementById('new_cashier_name');
                    if (nameInput) nameInput.focus();
                }, 100);
            } else {
                console.error('Modal no encontrado!');
                alert('Error: No se pudo abrir el modal. Por favor recarga la página.');
            }
        };

        window.closeNewCashierModal = function() {
            const modal = document.getElementById('newCashierModal');
            if (modal) {
                modal.classList.add('hidden');
                const form = document.getElementById('newCashierForm');
                if (form) form.reset();
                const errorDiv = document.getElementById('modal-error');
                if (errorDiv) errorDiv.classList.add('hidden');
                const successDiv = document.getElementById('modal-success');
                if (successDiv) successDiv.classList.add('hidden');
            }
        };

        // Cerrar modal al hacer clic fuera
        document.getElementById('newCashierModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewCashierModal();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNewCashierModal();
            }
        });

        // Manejar el envío del formulario
        document.getElementById('newCashierForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Guardando cajero...');

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            console.log('Datos a enviar:', data);

            const errorDiv = document.getElementById('modal-error');
            const successDiv = document.getElementById('modal-success');
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');

            try {
                console.log('Enviando petición a:', '{{ route("cashiers.store") }}');
                const response = await fetch('{{ route("cashiers.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                console.log('Respuesta recibida:', response.status);
                const result = await response.json();
                console.log('Resultado:', result);

                if (response.ok && result.success) {
                    // Mostrar mensaje de éxito
                    successDiv.textContent = result.message || 'Cajero creado exitosamente!';
                    successDiv.classList.remove('hidden');

                    // Recargar la lista de cajeros
                    await reloadCashiers();

                    // Cerrar el modal después de 1 segundo
                    setTimeout(() => {
                        closeNewCashierModal();
                    }, 1000);
                } else {
                    // Mostrar errores de validación
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

        // Recargar la lista de cajeros en el select
        async function reloadCashiers() {
            try {
                const response = await fetch('{{ route("cashiers.active") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success && result.cashiers) {
                    const select = document.getElementById('cashier_id');
                    const currentValue = select.value;

                    // Limpiar opciones excepto la primera
                    select.innerHTML = '<option value="">Sin cajero asignado</option>';

                    // Agregar nuevas opciones
                    result.cashiers.forEach(cashier => {
                        const option = document.createElement('option');
                        option.value = cashier.id;
                        option.textContent = cashier.name;
                        select.appendChild(option);
                    });

                    // Si había una selección previa, intentar mantenerla
                    if (currentValue) {
                        select.value = currentValue;
                    } else if (result.cashiers.length > 0) {
                        // Seleccionar el último cajero agregado (el más reciente)
                        select.value = result.cashiers[result.cashiers.length - 1].id;
                    }
                }
            } catch (error) {
                console.error('Error al recargar cajeros:', error);
            }
        }
    </script>

</body>
</html>
