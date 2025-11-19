<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página de Inicio - Plataforma de Gestión</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>

    <script>
        // ==========================================
        // INICIALIZACIÓN GLOBAL DE FIREBASE (FIX DEFINITIVO)
        // Definida aquí y ASUMIMOS que no está siendo incluida
        // en otro <script> ya inicializado.
        // ==========================================
        const firebaseConfig = {
            apiKey: "AIzaSyA8VguwL3jh2lIVpBSRrOvjy-c0PfmGD-4",
            authDomain: "isla-control.firebaseapp.com",
            projectId: "isla-control",
            storageBucket: "isla-control.firebasestorage.app",
            messagingSenderId: "145410754650",
            appId: "1:145410754650:web:8d590e161d280094a6f063",
            measurementId: "G-Z5RWFK99Q8"
        };
        
        // Comprobación para evitar inicialización duplicada en entornos SPA/Blade
        if (firebase.apps.length === 0) {
            window.app = firebase.initializeApp(firebaseConfig);
            window.auth = firebase.auth();
            window.provider = new firebase.auth.GoogleAuthProvider();
        } else {
            // Si ya está inicializado (ej. por otro script en el head), solo reasigna window.auth
            window.auth = firebase.auth();
        }
        
        // Tailwind config (se mantiene en el head)
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'animal-accent': '#5F9E74',
                        'animal-accent-2': '#8FBF8F',
                        'animal-warm': '#F8E9D6'
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --leaf-1: #EAF9EE;
            --leaf-2: #DFF4E6;
            --accent: #5F9E74;
            --accent-2: #8FBF8F;
            --warm: #F8E9D6;
            --text-1: #15382B;
            --muted: #6B7A6B;
        }

        html {
            /* Nueva línea para asegurar altura mínima de la ventana */
            height: 100%;
            scroll-behavior: smooth;
            /* Mantenemos el scroll suave */
        }

        body {
            /* Nueva línea para usar Flexbox y gestionar el diseño */
            display: flex;
            flex-direction: column;
            /* Apila el contenido y el footer verticalmente */
            min-height: 100%;
            /* Asegura que el body ocupe toda la altura */

            /* [Tus estilos actuales de body van aquí...] */
            font-family: 'Inter', sans-serif;
            background: radial-gradient(1200px 600px at 10% 10%, var(--leaf-1), transparent 12%),
                radial-gradient(900px 400px at 90% 90%, var(--leaf-2), transparent 10%),
                linear-gradient(180deg, #FBFBFB 0%, #F6FBF6 100%);
            color: var(--text-1);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Header illustration wave */
        .wave {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 110px;
            pointer-events: none;
        }

        /* Soft card */
        .soft-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.80));
            border: 1px solid rgba(95, 158, 116, 0.08);
            box-shadow: 0 10px 30px rgba(15, 23, 19, 0.06);
            border-radius: 18px;
        }

        /* Glassy navbar */
        .glass-nav {
            backdrop-filter: blur(8px) saturate(110%);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0.54));
            border-bottom: 1px solid rgba(95, 158, 116, 0.06);
        }

        /* Rounded big logo container */
        .logo-badge {
            background-color: transparent !important;
            /* Quita el color de fondo */
            border-radius: 0 !important;
            /* Quita el redondeo */
            padding: 0 !important;
            /* Asegúrate de que no haya padding que cree el espacio */
        }

        /* Mascot small animation */
        .mascot-bounce {
            animation: bounce 3s ease-in-out infinite;
            transform-origin: 50% 50%;
            display: inline-block;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px) rotate(-2deg);
            }
        }

        /* Fade-up animation */
        @keyframes fadeInUp {

            from {
                opacity: 0;
                transform: translateY(18px) scale(.995);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .fade-up {
            animation: fadeInUp .9s ease-out both;
        }

        /* Soft hover */
        .soft-hover {
            transition: all .28s cubic-bezier(.2, .9, .3, 1);
        }

        .soft-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(20, 60, 30, 0.06);
        }

        /* Mobile menu simple */
        [data-hidden="true"] {
            display: none;
        }

        /* Footer leaf pattern: MODIFICADO PARA SER VISIBLE Y NÍTIDO */
        .footer-leaf {
            /* Eliminamos la baja opacidad y el desenfoque para que se vea normal */
            opacity: 1;
            /* O puedes simplemente eliminar la línea si no está en otro lugar */
            filter: none;
            /* Asegura que no haya desenfoque */

            /* Nota: Si quieres un efecto nítido pero muy tenue, usa una opacidad mayor, ej: 0.4 (40%) */
        }

        /* Tiny helper for container width */
        .max-w-xl-2 {
            max-width: 68rem;
        }

        /* Nuevo estilo para asegurar que el contenido ocupe el espacio disponible */
        .main-content-wrapper {
            flex-grow: 1;
            /* Esto hace que el div ocupe todo el espacio libre restante */
        }
    </style>
</head>

<body class="antialiased">

    <nav class="glass-nav fixed top-4 left-4 right-4 z-50 rounded-2xl shadow-lg">
        <div class="container mx-auto px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/" class="logo-badge flex items-center gap-2">
                    <img src="/images/nuevo_islacontrol.png" alt="Logo Isla Control" class="logo-image"
                        style="height: 140px; width: auto;">
                </a>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="#inicio"
                    class="text-sm font-medium text-gray-700 hover:text-animal-accent transition-colors">Inicio</a>
                <a href="#anuncios"
                    class="text-sm font-medium text-gray-700 hover:text-animal-accent transition-colors">Anuncios</a>
                <a href="#about"
                    class="text-sm font-medium text-gray-700 hover:text-animal-accent transition-colors">Acerca de</a>
                <a href="#contacto"
                    class="text-sm font-medium text-gray-700 hover:text-animal-accent transition-colors">Contacto</a>

                @auth
                <div id="user-profile"
                    class="flex items-center gap-3 bg-white/60 px-3 py-1 rounded-full border border-animal-accent/8">
                    <span id="user-name" class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="text-sm bg-red-100 text-red-600 px-3 py-1 rounded-full border border-red-200 hover:bg-red-600 hover:text-white transition-colors">
                        Cerrar
                    </a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endauth
            </div>

            <div class="md:hidden">
                <button id="mobile-menu-button" aria-expanded="false" aria-controls="mobile-menu"
                    class="p-2 rounded-lg bg-white/75 shadow-sm">
                    <svg class="w-6 h-6 text-animal-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" data-hidden="true"
            class="md:hidden bg-white/90 border-t border-animal-accent/6 rounded-b-2xl px-5 py-4 shadow-inner">
            <a href="#inicio" class="block py-2 text-animal-accent font-semibold">Inicio</a>
            <a href="#anuncios" class="block py-2 text-gray-700">Anuncios</a>
            <a href="#about" class="block py-2 text-gray-700">Acerca de</a>
            <a href="#contacto" class="block py-2 text-gray-700">Contacto</a>

            @auth
            <div id="user-profile-mobile" class="mt-3 flex items-center justify-between gap-3">
                <span id="user-name-mobile" class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                    class="px-3 py-1 rounded-full bg-red-50 text-red-600 border border-red-100">Cerrar</a>
            </div>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endauth
        </div>
    </nav>

    <main class="pt-28">

        <header id="inicio" class="relative overflow-hidden py-20 md:py-28">
            <div class="container mx-auto px-6 max-w-xl-2 relative">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="fade-up">
                        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight" style="color:var(--text-1)">
                            Simplifica la gestión de tu negocio
                        </h1>
                        <p class="mt-4 text-lg text-muted" style="color:var(--muted)">
                            Plataforma amigable de punto de venta e inventario pensada para pequeños negocios. Intuitiva,
                            cálida y cercana.
                        </p>

                        <div class="mt-6 flex items-center gap-3">
                            @guest
                            <button onclick="showAuthModal(); return false;"
                                class="rounded-full px-6 py-3 bg-animal-accent text-white font-semibold shadow-md soft-hover">
                                Comenzar
                            </button>
                            @else
                            <a href="{{ route('dashboard') }}"
                                class="rounded-full px-6 py-3 bg-animal-accent text-white font-semibold shadow-md soft-hover">
                                Entrar
                            </a>
                            @endguest
                        </div>

                        <div class="mt-6 text-sm text-muted flex items-center gap-2" style="color:var(--muted)">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2l3 6 6 .5-4.5 4 1.5 6L12 16l-6 3 1.5-6L3 8.5 9 8 12 2z"
                                    stroke="#AFC9B0" stroke-width="1.2" stroke-linejoin="round"
                                    stroke-linecap="round" />
                            </svg>
                            Inicia con una prueba gratuita de 30 días • Sin tarjetas
                        </div>
                    </div>

                    <div class="relative">
                        <div class="soft-card p-5 md:p-8 rounded-3xl">
                            <img src="{{ asset('images/dashboard1.gif') }}" alt="Dashboard Mockup"
                                class="w-full h-auto rounded-2xl shadow-lg transform hover:scale-102 transition-transform duration-500" />
                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-semibold">Ventas hoy</div>
                                    <div class="text-xl font-bold text-animal-accent">+$3,340</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-muted">Clientes</div>
                                    <div class="font-semibold">72</div>
                                </div>
                            </div>
                        </div>

                        <svg class="absolute -top-6 -right-6 w-28 h-28" viewBox="0 0 64 64" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 40c10-18 36-22 52-12-10 14-30 20-52 12z" fill="#DFF4E6" />
                        </svg>
                    </div>
                </div>
            </div>

            <svg class="wave" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,40 C200,120 400,0 720,60 C1040,120 1240,20 1440,60 L1440 120 L0 120 Z" fill="#F7FBF7" />
            </svg>
        </header>

        <section id="about" class="py-16">
            <div class="container mx-auto px-6 max-w-6xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="fade-up">
                        <h2 class="text-3xl md:text-4xl font-extrabold">Todo lo que necesitas, en un solo lugar.</h2>
                        <p class="mt-4 text-gray-600">Nuestra plataforma está diseñada para ser intuitiva y poderosa.
                            Desde el control de inventario hasta el seguimiento de ventas, te damos las herramientas para
                            que te concentres en lo que realmente importa: hacer crecer tu negocio.</p>

                        <ul class="mt-6 space-y-3">
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-animal-accent-2/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-animal-accent" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 13l4 4L19 7" stroke="#5F9E74" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Inventario en tiempo real</div>
                                    <div class="text-sm text-muted">Recibe alertas y controla existencias sin
                                        complicaciones.</div>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-animal-accent-2/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-animal-accent" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z"
                                            stroke="#5F9E74" stroke-width="1.6" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Punto de venta ágil</div>
                                    <div class="text-sm text-muted">Cobros rápidos, integrados y sencillos desde
                                        cualquier dispositivo.</div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="fade-up">
                        <div class="soft-card p-6 rounded-2xl">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="p-4 rounded-xl bg-white/70 border border-animal-accent/6">
                                    <div class="text-sm font-semibold">Soporte</div>
                                    <div class="mt-2 text-sm text-muted">Asistencia cercana por chat y correo.</div>
                                </div>
                                <div class="p-4 rounded-xl bg-white/70 border border-animal-accent/6">
                                    <div class="text-sm font-semibold">Seguridad</div>
                                    <div class="mt-2 text-sm text-muted">Tus datos cifrados y copias de seguridad
                                        automáticas.</div>
                                </div>
                                <div class="p-4 rounded-xl bg-white/70 border border-animal-accent/6">
                                    <div class="text-sm font-semibold">Reportes</div>
                                    <div class="mt-2 text-sm text-muted">Informes fáciles de entender para tomar
                                        decisiones.</div>
                                </div>
                                <div class="p-4 rounded-xl bg-white/70 border border-animal-accent/6">
                                    <div class="text-sm font-semibold">Integraciones</div>
                                    <div class="mt-2 text-sm text-muted">Conecta con impresoras, pagos y más.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-gradient-to-b from-white to-animal-accent-2/8">
            <div class="container mx-auto px-6">
                <div class="text-center">
                    <h3 class="text-3xl font-extrabold">Características clave</h3>
                    <p class="mt-3 text-gray-600 max-w-2xl mx-auto">Potencia tu negocio con estas herramientas esenciales.
                    </p>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <article class="soft-card p-6 rounded-2xl soft-hover">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-animal-accent-2/20 flex items-center justify-center">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 7h18M3 12h18M3 17h18" stroke="#5F9E74" stroke-width="1.8"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Control de Inventario</h4>
                                <p class="text-sm text-muted">Registros exactos y alertas automáticas.</p>
                            </div>
                        </div>
                    </article>

                    <article class="soft-card p-6 rounded-2xl soft-hover">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-animal-accent-2/20 flex items-center justify-center">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 6h16M4 12h8M4 18h8" stroke="#5F9E74" stroke-width="1.8"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Punto de Venta</h4>
                                <p class="text-sm text-muted">Transacciones rápidas y seguras.</p>
                            </div>
                        </div>
                    </article>

                    <article class="soft-card p-6 rounded-2xl soft-hover">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-animal-accent-2/20 flex items-center justify-center">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 6h18M7 6v14M17 6v14" stroke="#5F9E74" stroke-width="1.8"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Reportes Detallados</h4>
                                <p class="text-sm text-muted">Análisis que facilitan decisiones inteligentes.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="ejemplo-dashboard" class="py-16 bg-white">
            <div class="container mx-auto px-6 max-w-6xl">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-extrabold">Panel de Control</h3>
                    <p class="mt-3 text-gray-600">Un vistazo rápido a tu panel de control</p>
                </div>

                <div class="soft-card p-8 max-w-4xl mx-auto">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <h4 class="text-xl font-bold text-gray-800">Ejemplos</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                            <div class="text-sm text-gray-600 mb-1">Ventas de Hoy</div>
                            <div class="text-2xl font-bold text-green-700">$4,580</div>
                            <div class="text-xs text-green-600 mt-1">↑ 12% vs ayer</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                            <div class="text-sm text-gray-600 mb-1">Productos Vendidos</div>
                            <div class="text-2xl font-bold text-blue-700">87</div>
                            <div class="text-xs text-blue-600 mt-1">23 transacciones</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                            <div class="text-sm text-gray-600 mb-1">En Inventario</div>
                            <div class="text-2xl font-bold text-purple-700">342</div>
                            <div class="text-xs text-red-600 mt-1">5 productos bajos</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <h5 class="font-semibold text-gray-700 mb-3">Actividad Reciente</h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">Venta completada</div>
                                        <div class="text-xs text-gray-500">Hace 5 minutos</div>
                                    </div>
                                </div>
                                <div class="font-semibold text-green-600">+$125.00</div>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">Stock bajo: Mouse Inalámbrico</div>
                                        <div class="text-xs text-gray-500">Hace 1 hora</div>
                                    </div>
                                </div>
                                <div class="text-sm text-orange-600">3 unidades</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contacto" class="py-16">
            <div class="container mx-auto px-6 text-center">
                <h4 class="text-3xl font-extrabold">¿Tienes preguntas?</h4>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Estamos aquí para ayudarte. Ponte en contacto con
                    nosotros y te responderemos pronto.</p>
                <a href="#"
                    class="mt-8 inline-block rounded-full border-2 border-animal-accent px-8 py-3 font-medium text-animal-accent transition-colors hover:bg-animal-accent hover:text-white">
                    Contáctanos
                </a>
            </div>
        </section>

    </main>

    <footer class="bg-[#15382B] text-white py-10 mt-10 w-full">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex flex-col items-center gap-6">
                <div class="flex flex-col items-center gap-1">
                    <div class="font-semibold text-lg">IslaControl</div>
                    <div class="text-sm text-white/60">Gestión amable • © 2025</div>
                </div>

                <div class="flex items-center gap-6 mt-4">
                    <a href="https://facebook.com" target="_blank"
                        class="hover:text-blue-500 transform hover:scale-110 transition duration-300">
                        <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M22 12a10 10 0 10-11.5 9.95v-7.05h-2v-2.9h2V9.5c0-2 1.2-3.1 3-3.1.9 0 1.8.15 1.8.15v2h-1c-1 0-1.3.6-1.3 1.2v1.45h2.3l-.37 2.9h-1.93V22A10 10 0 0022 12z" />
                        </svg>
                    </a>
                    <a href="https://instagram.com" target="_blank"
                        class="hover:text-pink-500 transform hover:scale-110 transition duration-300">
                        <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5zM12 7a5 5 0 110 10 5 5 0 010-10zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm5.75-.88a1.12 1.12 0 11-2.24 0 1.12 1.12 0 012.24 0z" />
                        </svg>
                    </a>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4 text-sm text-white/70 mt-4">
                    <a href="/terminos" class="hover:text-white underline">Términos y Condiciones</a>
                    <a href="/politica" class="hover:text-white underline">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    @guest
    @include('components.auth-modal')
    @endguest

    <script>
        // NAV MOBILE TOGGLE SAFE
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                const isHidden = mobileMenu.getAttribute('data-hidden') === 'true' || mobileMenu.getAttribute(
                    'data-hidden') === null;
                mobileMenu.setAttribute('data-hidden', String(!isHidden));
                mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
            });
        }

        // FUNCIONES DEL MODAL (usa el id auth-modal dentro de tu componente blade)
        function showAuthModal() {
            const modal = document.getElementById('auth-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideAuthModal() {
            const modal = document.getElementById('auth-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
            }
        }

        // --- LOGIN TRADICIONAL (MODIFICADO para forzar recarga) ---
        function handleLogin(event) {
            event && event.preventDefault && event.preventDefault();

            const email = document.getElementById('email') ? document.getElementById('email').value : '';
            const password = document.getElementById('password') ? document.getElementById('password').value : '';
            const rememberMe = document.getElementById('remember-me') ? document.getElementById('remember-me').checked : false;

            const persistence = rememberMe ? firebase.auth.Auth.Persistence.LOCAL : firebase.auth.Auth.Persistence.SESSION;
            if (email && password && window.auth) {
                window.auth.setPersistence(persistence)
                    .then(() => {
                        return window.auth.signInWithEmailAndPassword(email, password);
                    })
                    .then((userCredential) => {
                        const user = userCredential.user;
                        console.log("Inicio de sesión exitoso con email y contraseña:", user);
                        // ¡IMPORTANTE! Este es el login que usa Firebase en el front.
                        // Usamos window.location.replace para forzar la recarga.
                        window.location.replace("{{ route('dashboard') }}");
                    })
                    .catch((error) => {
                        const errorMessage = error.message;
                        console.error("Error de inicio de sesión:", errorMessage);
                        Toastify({
                            text: "Error de inicio de sesión: " + errorMessage,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                        }).showToast();
                    });
            } else {
                Toastify({
                    text: "Por favor, ingresa tu email y contraseña.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "linear-gradient(to right, #FBBF24, #FCD34D)",
                }).showToast();
            }
        }

        function handleForgotPassword(event) {
            event && event.preventDefault && event.preventDefault();

            const email = document.getElementById('email') ? document.getElementById('email').value : '';
            if (email && window.auth) {
                window.auth.sendPasswordResetEmail(email)
                    .then(() => {
                        Toastify({
                            text: "Se ha enviado un correo para restablecer tu contraseña. Revisa tu bandeja de entrada.",
                            duration: 5000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #22C55E, #4ADE80)",
                        }).showToast();
                    })
                    .catch((error) => {
                        console.error("Error al restablecer la contraseña:", error.message);
                        Toastify({
                            text: "Error al restablecer la contraseña: " + error.message,
                            duration: 5000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "linear-gradient(to right, #EF4444, #F87171)",
                        }).showToast();
                    });
            } else {
                Toastify({
                    text: "Por favor, ingresa tu email en el campo de arriba para restablecer la contraseña.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "linear-gradient(to right, #FBBF24, #FCD34D)",
                }).showToast();
            }
        }

        function logout() {
            if (window.auth) {
                window.auth.signOut().then(() => {
                    // **CORRECCIÓN CLAVE: Usamos replace para forzar una carga completa al cerrar sesión.**
                    window.location.replace('/');
                }).catch(error => {
                    console.error('Error al cerrar sesión:', error);
                });
            }
        }

        // Actualiza nombres en nav, evita parpadeos
        if (window.auth) {
            window.auth.onAuthStateChanged(user => {
                if (user) {
                    const userNameDesktop = document.querySelector('#user-profile #user-name');
                    if (userNameDesktop) userNameDesktop.textContent = user.displayName || user.email;

                    const userNameMobile = document.querySelector('#user-profile-mobile #user-name-mobile') || document.querySelector('#user-name-mobile');
                    if (userNameMobile) userNameMobile.textContent = user.displayName || user.email;
                }
            });
        }


        // Abrir modal por query param ?modal=login (no repetir)
        document.addEventListener('DOMContentLoaded', () => {
            try {
                const url = new URL(window.location.href);
                const modalParam = url.searchParams.get('modal');
                if (modalParam === 'login') {
                    showAuthModal();
                    url.searchParams.delete('modal');
                    window.history.replaceState({}, document.title, url.pathname);
                }
            } catch (e) {
                // ignore in case of URL issues (older browsers)
            }
        });
    </script>

</body>

</html>