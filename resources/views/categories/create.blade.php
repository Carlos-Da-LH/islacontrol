<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Categoría</title>
    <!-- Incluye Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Define la fuente Inter */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex items-start justify-center min-h-screen pt-16 p-4">

    <!-- Main Content Container (Wide and Dark) -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-4xl border border-gray-700">
        
        <!-- Header -->
        <div class="mb-8 pb-4 border-b border-gray-700">
            <h1 class="text-4xl font-extrabold text-white tracking-tight">
                <svg class="inline-block w-8 h-8 mr-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Nueva Categoría
            </h1>
        </div>

        <!-- Formulario -->
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
            
            @csrf <!-- Token de seguridad requerido en Laravel -->

            <!-- Bloque para mostrar errores de validación -->
            @if ($errors->any())
                <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg relative shadow-md" role="alert">
                    <strong class="font-bold">¡Ocurrió un error!</strong>
                    <span class="block sm:inline">Por favor, revisa los campos.</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Campo Nombre -->
            <div class="bg-gray-700 p-6 rounded-xl shadow-inner border border-gray-600">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nombre de la Categoría</label>
                
                <input type="text" 
                    name="name" 
                    id="name" 
                    placeholder="Ej. Productos Electrónicos"
                    value="{{ old('name') }}" 
                    required 
                    class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-lg">

                <!-- Mostrar errores de validación si existen -->
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grupo de Botones -->
            <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 pt-4">
                
                <!-- Botón de Regreso (Gris) -->
                <a href="{{ route('categories.index') }}" 
                    class="flex items-center justify-center bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-gray-700 transition-colors shadow-md transform hover:scale-[1.02] active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Regresar al Listado
                </a>

                <!-- Botón de Guardar (Verde) -->
                <button type="submit" 
                    class="flex items-center justify-center bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-emerald-700 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 focus:outline-none focus:ring-4 focus:ring-emerald-500/50">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Guardar Categoría
                </button>
            </div>
        </form>
    </div>

</body>
</html>
