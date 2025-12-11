<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cerrar Caja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at center, #1f2937, #111827);
        }
        /* Dashboard integration styles */
        .dark .close-form-container {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
    </style>
</head>
<body class="text-white flex items-start justify-center min-h-screen p-4 pt-52 lg:pt-8">

    <div class="bg-gray-800 dark:bg-gray-800 p-8 md:p-10 rounded-3xl shadow-[0_0_30px_rgba(239,68,68,0.15)] w-full max-w-3xl border border-gray-700 close-form-container">

        <!-- Header -->
        <div class="mb-8 pb-4 border-b border-red-600/50 flex items-center justify-between">
            <a href="{{ route('cash-register.show', $cashRegister->id) }}"
                class="text-gray-400 hover:text-red-400 transition-colors transform hover:scale-110 p-2 rounded-full -ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight flex-grow text-center pr-10">
                <i class='bx bx-lock-alt text-4xl text-red-500 mr-2'></i>
                Cerrar Caja #{{ $cashRegister->id }}
            </h1>
        </div>

        <!-- Resumen de la Caja -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Fondo Inicial -->
            <div class="bg-blue-900/30 border border-blue-600/50 rounded-xl p-4">
                <p class="text-sm text-blue-300 mb-1">Fondo Inicial</p>
                <p class="text-2xl font-bold text-blue-200">${{ number_format($cashRegister->opening_balance, 2) }}</p>
            </div>

            <!-- Ventas en Efectivo -->
            <div class="bg-emerald-900/30 border border-emerald-600/50 rounded-xl p-4">
                <p class="text-sm text-emerald-300 mb-1">Ventas en Efectivo</p>
                <p class="text-2xl font-bold text-emerald-200">${{ number_format($cashRegister->total_cash_sales, 2) }}</p>
            </div>

            <!-- Efectivo Esperado -->
            <div class="bg-yellow-900/30 border border-yellow-600/50 rounded-xl p-4">
                <p class="text-sm text-yellow-300 mb-1">Efectivo Esperado</p>
                <p class="text-2xl font-bold text-yellow-200">${{ number_format($cashRegister->calculateExpectedBalance(), 2) }}</p>
            </div>
        </div>

        <!-- Detalle de Ventas -->
        <div class="bg-gray-700/50 border border-gray-600 rounded-xl p-4 mb-6">
            <h3 class="font-bold text-lg mb-3 flex items-center">
                <i class='bx bx-bar-chart-alt-2 text-emerald-500 mr-2'></i>
                Resumen de Ventas del Turno
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div>
                    <span class="text-gray-400">💵 Efectivo:</span>
                    <p class="font-semibold text-white">${{ number_format($cashRegister->total_cash_sales, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-400">💳 Tarjetas:</span>
                    <p class="font-semibold text-white">${{ number_format($cashRegister->total_card_sales, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-400">🏦 Transferencias:</span>
                    <p class="font-semibold text-white">${{ number_format($cashRegister->total_transfer_sales, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-400">📊 Total General:</span>
                    <p class="font-semibold text-emerald-400">${{ number_format($cashRegister->total_sales, 2) }}</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-100 p-4 rounded-xl mb-6" role="alert">
                <strong class="font-extrabold block mb-1 text-sm">¡Error!</strong>
                <ul class="list-disc list-inside space-y-0.5 ml-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif>

        <!-- Formulario de Cierre -->
        <form method="POST" action="{{ route('cash-register.close', $cashRegister->id) }}" class="space-y-6">
            @csrf

            <!-- Cajero -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <i class='bx bx-user text-blue-400 mr-1'></i>
                    Cajero
                </label>
                <div class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    @if($cashRegister->cashier)
                        <div class="flex items-center">
                            <i class='bx bx-user-circle text-2xl text-blue-400 mr-2'></i>
                            <span class="font-semibold">{{ $cashRegister->cashier->name }}</span>
                        </div>
                    @else
                        <span class="text-gray-400">Sin cajero asignado</span>
                    @endif
                </div>
                <input type="hidden" name="cashier_id" value="{{ $cashRegister->cashier_id }}">
            </div>

            <!-- Efectivo Contado -->
            <div class="bg-red-900/20 border-2 border-red-600 rounded-xl p-6">
                <label for="closing_balance" class="block text-lg font-bold text-white mb-3">
                    <i class='bx bx-money text-red-400 mr-2'></i>
                    Efectivo Contado en Caja *
                </label>
                <p class="text-sm text-gray-300 mb-4">Cuenta todo el efectivo físico que tienes en la caja ahora y escribe el monto exacto.</p>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-300 text-2xl font-bold">$</span>
                    <input
                        type="number"
                        id="closing_balance"
                        name="closing_balance"
                        step="0.01"
                        min="0"
                        required
                        autofocus
                        class="w-full pl-12 pr-4 py-4 bg-gray-700 border-2 border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-2xl font-bold"
                        placeholder="0.00"
                        oninput="calculateDifference()">
                </div>
            </div>

            <!-- Diferencia (se calcula automáticamente) -->
            <div id="difference-section" class="hidden">
                <div id="difference-positive" class="bg-green-900/30 border-2 border-green-500 rounded-xl p-4 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-300 mb-1">✅ Sobrante</p>
                            <p class="text-xs text-gray-300">Hay más efectivo del esperado</p>
                        </div>
                        <p id="difference-positive-amount" class="text-3xl font-black text-green-400">+$0.00</p>
                    </div>
                </div>

                <div id="difference-negative" class="bg-red-900/30 border-2 border-red-500 rounded-xl p-4 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-300 mb-1">❌ Faltante</p>
                            <p class="text-xs text-gray-300">Falta efectivo</p>
                        </div>
                        <p id="difference-negative-amount" class="text-3xl font-black text-red-400">-$0.00</p>
                    </div>
                </div>

                <div id="difference-zero" class="bg-emerald-900/30 border-2 border-emerald-500 rounded-xl p-4 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-emerald-300 mb-1">✅ Perfecto</p>
                            <p class="text-xs text-gray-300">El efectivo cuadra exactamente</p>
                        </div>
                        <p class="text-3xl font-black text-emerald-400">$0.00</p>
                    </div>
                </div>
            </div>

            <!-- Notas de Cierre -->
            <div>
                <label for="closing_notes" class="block text-sm font-medium text-gray-300 mb-2">
                    <i class='bx bx-note text-red-400 mr-1'></i>
                    Notas de Cierre (Opcional)
                </label>
                <textarea
                    id="closing_notes"
                    name="closing_notes"
                    rows="3"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Ej: Turno sin incidencias, todo correcto...">{{ old('closing_notes') }}</textarea>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <button
                    type="submit"
                    class="flex-1 bg-red-600 text-white py-4 rounded-lg hover:bg-red-700 transition-colors font-bold text-lg shadow-lg flex items-center justify-center">
                    <i class='bx bx-lock-alt text-2xl mr-2'></i>
                    Cerrar Caja
                </button>
                <a
                    href="{{ route('cash-register.show', $cashRegister->id) }}"
                    class="flex-1 bg-gray-600 text-white py-4 rounded-lg hover:bg-gray-700 transition-colors font-semibold text-lg shadow-lg text-center flex items-center justify-center">
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
        const expectedBalance = {{ $cashRegister->calculateExpectedBalance() }};

        function calculateDifference() {
            const closingBalance = parseFloat(document.getElementById('closing_balance').value) || 0;
            const difference = closingBalance - expectedBalance;

            const diffSection = document.getElementById('difference-section');
            const posDiv = document.getElementById('difference-positive');
            const negDiv = document.getElementById('difference-negative');
            const zeroDiv = document.getElementById('difference-zero');

            if (closingBalance > 0) {
                diffSection.classList.remove('hidden');

                posDiv.classList.add('hidden');
                negDiv.classList.add('hidden');
                zeroDiv.classList.add('hidden');

                if (difference > 0) {
                    posDiv.classList.remove('hidden');
                    document.getElementById('difference-positive-amount').textContent = '+$' + difference.toFixed(2);
                } else if (difference < 0) {
                    negDiv.classList.remove('hidden');
                    document.getElementById('difference-negative-amount').textContent = '-$' + Math.abs(difference).toFixed(2);
                } else {
                    zeroDiv.classList.remove('hidden');
                }
            } else {
                diffSection.classList.add('hidden');
            }
        }

        // ===== MODAL DE CAJEROS =====
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

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            const errorDiv = document.getElementById('modal-error');
            const successDiv = document.getElementById('modal-success');
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("cashiers.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

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
