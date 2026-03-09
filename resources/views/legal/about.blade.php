<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00D084" />
    <title>Acerca de IslaControl</title>
    <link rel="icon" type="image/png" href="/storage/logos/logo_islacontrol22.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #00D084 0%, #00a066 100%);">
                        <i class='bx bx-store text-white text-2xl'></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">IslaControl</span>
                </div>
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                    <i class='bx bx-arrow-back'></i>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class='bx bx-store text-white text-5xl'></i>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">Acerca de IslaControl</h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto">
                La solución completa para gestionar tu negocio de manera eficiente y profesional
            </p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Mission Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Misión</h2>
            <p class="text-lg text-gray-700 leading-relaxed mb-4">
                IslaControl nace con la misión de proporcionar a pequeños y medianos negocios una herramienta
                profesional, accesible y fácil de usar para gestionar todas las áreas críticas de su operación diaria.
            </p>
            <p class="text-lg text-gray-700 leading-relaxed">
                Creemos que todo negocio, sin importar su tamaño, merece tener acceso a tecnología de calidad
                que les permita competir en igualdad de condiciones con empresas más grandes.
            </p>
        </div>

        <!-- What We Offer -->
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">¿Qué Ofrecemos?</h2>

            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                            <i class='bx bx-cart text-green-600 text-2xl'></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Sistema de Punto de Venta (POS)</h3>
                        <p class="text-gray-600">
                            Procesa ventas de manera rápida y eficiente con soporte para múltiples métodos de pago,
                            impresión de tickets y registro detallado de transacciones.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-package text-blue-600 text-2xl'></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Gestión de Inventario</h3>
                        <p class="text-gray-600">
                            Control completo de productos con soporte para códigos de barras, alertas de stock bajo,
                            categorización y seguimiento de movimientos de inventario.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                            <i class='bx bx-line-chart text-purple-600 text-2xl'></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Reportes y Analíticas</h3>
                        <p class="text-gray-600">
                            Genera reportes detallados de ventas, productos más vendidos, tendencias y métricas clave
                            para tomar decisiones informadas sobre tu negocio.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                            <i class='bx bx-wallet text-yellow-600 text-2xl'></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Control de Caja</h3>
                        <p class="text-gray-600">
                            Administra la apertura y cierre de caja, asigna cajeros, lleva un registro preciso de todos
                            los movimientos de efectivo y genera cortes de caja automáticos.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center">
                            <i class='bx bx-brain text-pink-600 text-2xl'></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Asistente de IA (Isla IA)</h3>
                        <p class="text-gray-600">
                            Asistente inteligente local que te ayuda a analizar tu negocio, obtener insights y
                            responder preguntas sobre tus datos de manera conversacional.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technology -->
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Tecnología</h2>
            <p class="text-lg text-gray-700 leading-relaxed mb-4">
                IslaControl está construido con tecnologías modernas y probadas:
            </p>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    <span class="text-gray-700"><strong>Laravel PHP:</strong> Framework backend robusto y seguro</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    <span class="text-gray-700"><strong>Firebase Auth:</strong> Autenticación segura y confiable</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    <span class="text-gray-700"><strong>MySQL:</strong> Base de datos confiable y escalable</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    <span class="text-gray-700"><strong>Tailwind CSS:</strong> Interfaz moderna y responsive</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    <span class="text-gray-700"><strong>Ollama AI:</strong> Inteligencia artificial local</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    <span class="text-gray-700"><strong>PWA Ready:</strong> Instálala como app nativa</span>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">¿Por qué elegir IslaControl?</h2>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <i class='bx bx-star text-yellow-500 text-2xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">100% Gratis</h3>
                        <p class="text-gray-600">Sin costos ocultos, sin límites de uso. Todas las funciones disponibles sin pagar.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <i class='bx bx-star text-yellow-500 text-2xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Fácil de Usar</h3>
                        <p class="text-gray-600">Interfaz intuitiva diseñada para que cualquier persona pueda usarla sin capacitación.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <i class='bx bx-star text-yellow-500 text-2xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Multiplataforma</h3>
                        <p class="text-gray-600">Funciona en computadoras, tablets y teléfonos móviles sin necesidad de instalación.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <i class='bx bx-star text-yellow-500 text-2xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Seguro y Privado</h3>
                        <p class="text-gray-600">Tus datos están protegidos con encriptación y no los compartimos con terceros.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <i class='bx bx-star text-yellow-500 text-2xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">En Español</h3>
                        <p class="text-gray-600">Completamente en español, diseñado para negocios latinoamericanos.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <i class='bx bx-star text-yellow-500 text-2xl flex-shrink-0'></i>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Actualizaciones Constantes</h3>
                        <p class="text-gray-600">Mejoramos constantemente la aplicación con nuevas funciones y correcciones.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Version Info -->
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-8 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Versión Actual</h2>
                    <p class="text-emerald-50">IslaControl v1.0.0</p>
                    <p class="text-emerald-50 text-sm mt-2">Última actualización: {{ date('F Y') }}</p>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-emerald-50 mb-3">¿Listo para empezar?</p>
                    <a href="{{ route('welcome') }}" class="bg-white text-emerald-600 px-6 py-3 rounded-lg font-semibold hover:bg-emerald-50 transition-colors inline-flex items-center gap-2">
                        <i class='bx bx-rocket'></i>
                        Comenzar Ahora
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} IslaControl. Todos los derechos reservados.</p>
                <div class="mt-4 flex justify-center gap-6">
                    <a href="{{ route('legal.privacy') }}" class="text-gray-600 hover:text-emerald-600">Privacidad</a>
                    <a href="{{ route('legal.terms') }}" class="text-gray-600 hover:text-emerald-600">Términos</a>
                    <a href="{{ route('legal.contact') }}" class="text-gray-600 hover:text-emerald-600">Contacto</a>
                    <a href="{{ route('legal.about') }}" class="text-gray-600 hover:text-emerald-600">Acerca de</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
