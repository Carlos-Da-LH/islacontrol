<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título Dinámico -->
    <title>Editar Categoría: {{ $category->name ?? 'Cargando...' }}</title>
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

    <!-- Main Content Container (Dark, Rounded, Wide) -->
    <div class="bg-gray-800 p-8 md:p-12 rounded-3xl shadow-2xl w-full max-w-4xl border border-gray-700">
        
        <!-- Header -->
        <div class="mb-10 pb-4 border-b border-gray-700 flex items-center">
            <svg class="w-10 h-10 mr-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                Editar Categoría: <span class="text-emerald-300">{{ $category->name ?? '' }}</span>
            </h1>
        </div>

        <!-- Formulario -->
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-8">
            
            @csrf
            @method('PUT')

            <!-- Bloque de Errores de Validación -->
            @if ($errors->any())
                <div class="bg-red-900 border border-red-700 text-red-100 p-5 rounded-xl shadow-md" role="alert">
                    <strong class="font-bold block mb-1">¡Revisa los campos!</strong>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Campo Nombre: Contenedor con más enfoque -->
            <div class="bg-gray-700/50 p-6 rounded-xl shadow-lg border border-gray-600 hover:border-emerald-500 transition-all duration-300">
                <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nombre de la Categoría</label>
                
                <input type="text" 
                    name="name" 
                    id="name" 
                    placeholder="Ej. Ropa de Invierno"
                    value="{{ old('name', $category->name) }}" 
                    required 
                    class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 focus:border-emerald-500 transition-colors shadow-inner">

                <!-- Mostrar errores de validación si existen -->
                @error('name')
                    <p class="text-red-400 text-sm mt-2 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Grupo de Botones -->
            <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-4 pt-4">
                
                <!-- Botón de Regreso (Gris) -->
                <a href="{{ route('categories.index') }}" 
                    class="flex items-center justify-center bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-gray-700 transition-colors shadow-md transform hover:scale-[1.02] active:scale-95 border border-gray-500">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Regresar al Listado
                </a>

                <!-- Botón de Actualizar (Verde Esmeralda) -->
                <button type="submit" 
                    class="flex items-center justify-center bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-xl hover:bg-emerald-700 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 focus:outline-none focus:ring-4 focus:ring-emerald-500/70">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Actualizar Categoría
                </button>
            </div>
        </form>
    </div>

</body>
</html>
