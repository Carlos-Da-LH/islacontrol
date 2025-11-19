<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Plataforma para negocios - Tu solución integral</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    @include('components.nav-bar')

    <main>
        {{ $slot }}
    </main>

    @vite('resources/js/app.js')
</body>
</html>