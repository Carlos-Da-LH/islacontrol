<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <style>
        /* Define la fuente Inter */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Estilo para el SELECT */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%23d1d5db' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }
    </style>
</head>
<body class="text-gray-900 dark:text-white min-h-screen">

    <!-- Main Content Container (Darker background, soft corners, shadow) -->
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 lg:p-6 w-full border-b border-gray-200 dark:border-gray-700">
        
        <!-- Header -->
        <div class="mb-8 pb-4 border-b border-gray-200 dark:border-emerald-600/50 flex items-center justify-between">
            <a href="{{ route('products.index') }}"
                class="text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors transform hover:scale-110 p-2 rounded-full -ml-3">
                <!-- Icono de flecha hacia atrás (SVG) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight flex-grow text-center pr-10">
                Editar Producto: <span class="text-emerald-600 dark:text-emerald-400">{{ $product->name }}</span>
            </h1>
        </div>

        <!-- Bloque de Errores de Validación (Si aplica) -->
        @if ($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-100 p-4 rounded-xl mb-6 shadow-inner shadow-red-500/20" role="alert">
                <strong class="font-extrabold block mb-1 text-sm">¡Error de Validación!</strong>
                <ul class="list-disc list-inside space-y-0.5 ml-2 text-sm font-light">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario de Edición de Producto -->
        <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Contenedor general de campos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Campo Nombre -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre del Producto</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                        class="mt-1 block w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner"
                        placeholder="Ej. Laptop Gaming X200" required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Código de Barras -->
                <div>
                    <label for="codigo_barras" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Código de Barras</label>
                    <input type="text" id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras', $product->codigo_barras) }}"
                        class="mt-1 block w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner"
                        placeholder="Ej. 7501086801046">
                    @error('codigo_barras')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Stock -->
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Stock</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                        class="mt-1 block w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner" 
                        placeholder="Mínimo 0" required min="0">
                    @error('stock')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Precio -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Precio ($)</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}"
                        class="mt-1 block w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner" 
                        placeholder="Ej. 99.99" required min="0">
                    @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Categoría -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
                    <select id="category_id" name="category_id" 
                        class="mt-1 block w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner" required>
                        
                        <option value="" disabled class="text-gray-500">Selecciona una categoría</option>
                        
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="pt-6 flex justify-between space-x-4">
                <a href="{{ route('products.index') }}" 
                    class="w-1/2 bg-gray-600 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:bg-gray-700 transition-colors duration-300 transform hover:scale-[1.01] active:scale-95 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Regresar al Listado
                </a>
                
                <button type="submit" 
                    class="w-1/2 bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-colors duration-300 transform hover:scale-[1.01] active:scale-95 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>

</body>
</html>
