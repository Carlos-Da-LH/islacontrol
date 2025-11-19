<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Plataforma de Gestión' }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Barra de Navegación -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto flex items-center justify-between px-4 py-4">
            <!-- Logo del Negocio -->
            <a href="#" class="text-2xl font-bold text-gray-800">
                <img src="{{ asset('images/tu-logo.png') }}" alt="Logo del Negocio" class="h-10">
            </a>
            <div class="flex items-center space-x-4">
                <a href="#inicio" class="text-gray-600 hover:text-indigo-600">Inicio</a>
                <a href="#anuncio" class="text-gray-600 hover:text-indigo-600">Anuncio</a>
                <a href="#about" class="text-gray-600 hover:text-indigo-600">Acerca de</a>
                <a href="#contacto" class="text-gray-600 hover:text-indigo-600">Contacto</a>
                <a href="{{ route('login') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-white transition-colors hover:bg-indigo-700">
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="rounded-md border-2 border-indigo-600 px-4 py-2 font-medium text-indigo-600 transition-colors hover:bg-indigo-600 hover:text-white">
                    Regístrate
                </a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>
    
</body>
</html>
