<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Negocio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Reutilizamos el estilo oscuro que hemos usado en el CRUD de ventas */
        body {
            background-color: #111827; /* Fondo oscuro consistente */
        }
        .crud-container {
            background-color: #1f2937; 
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.5);
        }
        .input-dark-style {
            background-color: #374151;
            color: #D1D5DB;
            border-color: #4B5563;
        }
        .input-dark-style:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.5);
        }
        .logo-preview {
            background-color: #f3f4f6; /* Fondo blanco para contrastar el logo */
            display: inline-block;
            padding: 5px;
            border-radius: 4px;
        }
        
        /* Estilo para el botón de retroceso (coherente con las vistas de ventas) */
        .back-button {
            color: #9CA3AF;
            transition: color 0.2s;
        }
        .back-button:hover {
            color: #fff;
        }
    </style>
</head>

<body class="bg-gray-900 min-h-screen p-8 font-sans text-gray-100">

    <div class="flex items-start justify-center w-full">
        <div class="crud-container p-8 rounded-xl w-full max-w-2xl">
            
            {{-- ENCABEZADO CON BOTÓN DE REGRESO (Usando url()->previous() para volver al ticket) --}}
            <div class="flex items-center mb-8">
                {{-- 🟢 CAMBIO CLAVE: Volver a la URL anterior 🟢 --}}
                <a href="{{ url()->previous() }}" class="back-button mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                
                <h1 class="text-3xl font-bold text-white flex-grow">Configuración del Negocio</h1>
            </div>

            {{-- Manejo de Mensajes (Éxito o Error) --}}
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('settings.update') ?? '#' }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT') 

                {{-- Campo Nombre del Negocio --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre del Negocio</label>
                    <input type="text" id="name" name="name" 
                            class="block w-full px-4 py-2 rounded-lg shadow-sm sm:text-sm input-dark-style" 
                            value="{{ old('name', $nombre_negocio_actual ?? 'Mi Negocio') }}" 
                            required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Logo --}}
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-300 mb-1">Subir Logo (Archivo)</label>
                    <input type="file" id="logo" name="logo" 
                            class="block w-full text-gray-300 border border-gray-700 rounded-lg shadow-sm sm:text-sm input-dark-style file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700"
                            accept="image/*">
                    <p class="text-xs text-gray-400 mt-2">El archivo debe ser JPG, PNG o SVG para un mejor rendimiento en el ticket.</p>
                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Preview del Logo Actual --}}
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-300 mb-2">Logo Actual:</p>
                    <div class="logo-preview">
                        {{-- NOTA: Se añade una variable de tiempo para evitar caché del navegador --}}
                        <img src="{{ $logo_url_actual ?? asset('images/default_logo.png') }}{{ '?v=' . time() }}" 
                             alt="Logo Actual" 
                             class="h-16 w-auto object-contain">
                    </div>
                </div>

                {{-- Botón Guardar --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-600 text-white font-bold text-lg py-3 rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>