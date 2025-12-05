<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente Profesional</title>
    <!-- Carga de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Estilos base para el tema oscuro */
        .dark-input {
            background-color: #2d3748; /* bg-gray-700 */
            border-color: #4a5568; /* border-gray-600 */
            color: #e2e8f0; /* text-gray-200 */
            transition: all 0.15s ease-in-out;
        }
        .dark-input:focus {
            border-color: #48bb78; /* Verde de enfoque */
            box-shadow: 0 0 0 1px #48bb78;
            outline: none;
        }
    </style>
</head>

<!-- Fondo general muy oscuro -->
<body class="bg-gray-900 min-h-screen pt-28 lg:pt-0">
    <!-- Contenedor principal ancho (max-w-7xl) -->
    <div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">

        <!-- Tarjeta de Edición (fondo gris oscuro) -->
        <div class="bg-gray-800 shadow-2xl rounded-2xl overflow-hidden p-4 sm:p-6 lg:p-10 border border-gray-700">

            <!-- Título con Icono SVG -->
            <div class="flex flex-col sm:flex-row items-center justify-center mb-6 sm:mb-8 lg:mb-10 border-b border-gray-700 pb-4 gap-2">
                <!-- Icono de Edición de Cliente (SVG - Lápiz sobre Usuario) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white text-center">
                    Editar Cliente
                </h1>
            </div>
            
            <!-- Mensaje de error/éxito (Simulación de Laravel Session) -->
            <!-- @if (session('success'))
            <div class="bg-green-600 p-4 rounded-lg mb-6 text-white font-medium text-center shadow-lg">
                {{ session('success') }}
            </div>
            @endif -->
            
            <!-- Formulario de Edición (Fondo aún más oscuro) -->
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="p-8 border border-gray-700 rounded-xl bg-gray-900 shadow-xl">
                @csrf
                @method('PUT') 
                
                <h2 class="text-2xl font-bold text-green-400 mb-6 border-b border-gray-700 pb-3">
                    Actualizar Datos del Cliente #{{ $customer->id }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Campo Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ $customer->name }}" required
                            class="mt-1 block w-full rounded-lg shadow-sm dark-input text-base h-11">
                    </div>
                    <!-- Campo Teléfono -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-300 mb-1">Teléfono</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ $customer->phone_number }}"
                            class="mt-1 block w-full rounded-lg shadow-sm dark-input text-base h-11">
                    </div>
                    <!-- Campo Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ $customer->email }}"
                            class="mt-1 block w-full rounded-lg shadow-sm dark-input text-base h-11">
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end gap-4 items-center">
                    <!-- Botón Actualizar (Color Verde Brillante) -->
                    <button type="submit"
                        class="py-3 px-6 border border-transparent shadow-lg text-base font-semibold rounded-lg text-white 
                               bg-green-600 hover:bg-green-700 active:bg-green-800 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 focus:ring-offset-gray-900 
                               transition duration-300 ease-in-out transform hover:scale-[1.005]"
                    >
                        Actualizar Cliente
                    </button>
                    
                    <!-- Botón Cancelar/Volver a la lista -->
                    <a href="{{ route('customers.index') }}" 
                        class="py-3 px-6 text-base font-medium rounded-lg text-gray-300 bg-gray-700 hover:bg-gray-600 
                               transition duration-150 ease-in-out shadow-md"
                    >
                        Cancelar y Volver
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</body>

</html>
