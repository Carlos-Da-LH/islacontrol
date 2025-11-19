<nav class="sticky top-0 z-50 bg-white shadow-sm transition-transform duration-300 ease-in-out">
    <div class="container mx-auto flex items-center justify-between px-4 py-4 md:px-6">
        <a href="{{ route('home') }}" class="flex-shrink-0">
            <h1 class="text-2xl font-bold text-indigo-600 transition-colors hover:text-indigo-800">
                TuPlataforma
            </h1>
            </a>

        <div class="hidden space-x-6 md:flex">
            <a href="#inicio" class="rounded-md px-3 py-2 font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-indigo-600">Inicio</a>
            <a href="{{ route('anuncios.page') }}" class="rounded-md bg-indigo-500 px-3 py-2 font-semibold text-white transition-colors hover:bg-indigo-600">
                Anuncios
            </a>
            <a href="#contacto" class="rounded-md px-3 py-2 font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-indigo-600">Contacto</a>
        </div>

        <div class="flex items-center space-x-4">
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-md px-4 py-2 font-medium text-indigo-600 ring-1 ring-indigo-600 transition-colors hover:bg-indigo-600 hover:text-white">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden font-medium text-gray-600 transition-colors hover:text-indigo-600 md:block">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-4 py-2 font-medium text-white transition-colors hover:bg-indigo-700">
                    Regístrate gratis
                </a>
            @endauth
        </div>

        <button id="mobile-menu-button" class="md:hidden">
            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden md:hidden">
        <div class="flex flex-col space-y-2 px-4 pb-4">
            <a href="#inicio" class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-indigo-600">Inicio</a>
            <a href="{{ route('anuncios.page') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-indigo-600">Anuncios</a>
            <a href="#contacto" class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-indigo-600">Contacto</a>
            @guest
                <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-indigo-600">
                    Iniciar sesión
                </a>
            @endguest
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-button').onclick = function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    };
</script>