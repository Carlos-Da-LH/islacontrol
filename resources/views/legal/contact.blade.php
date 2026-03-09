<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00D084" />
    <title>Contacto - IslaControl</title>
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

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Contacto y Soporte</h1>
            <p class="text-lg text-gray-600">Estamos aquí para ayudarte con cualquier pregunta o problema</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-12">
            <!-- Email Support -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #00D084 0%, #00a066 100%);">
                    <i class='bx bx-envelope text-white text-3xl'></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Correo Electrónico</h3>
                <p class="text-gray-600 mb-4">Envíanos un correo y te responderemos dentro de 24-48 horas</p>
                <a href="mailto:soporte@islacontrol.com" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold">
                    soporte@islacontrol.com
                    <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>

            <!-- FAQ / Help -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #00D084 0%, #00a066 100%);">
                    <i class='bx bx-help-circle text-white text-3xl'></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Centro de Ayuda</h3>
                <p class="text-gray-600 mb-4">Consulta nuestras preguntas frecuentes y guías de usuario</p>
                <a href="#faq" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold">
                    Ver preguntas frecuentes
                    <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12" id="faq">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Preguntas Frecuentes</h2>

            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Cómo creo una cuenta en IslaControl?</h3>
                    <p class="text-gray-600">
                        En la página de inicio, haz clic en "Comenzar Ahora" o "Registrarse". Necesitarás proporcionar
                        un correo electrónico y crear una contraseña. También puedes registrarte usando tu cuenta de Google.
                    </p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Mis datos están seguros?</h3>
                    <p class="text-gray-600">
                        Sí. Utilizamos Firebase para autenticación y encriptación de datos. Toda la comunicación se realiza
                        mediante HTTPS. Además, tus datos están separados por usuario, nadie más puede ver tu información.
                        Consulta nuestra <a href="{{ route('legal.privacy') }}" class="text-emerald-600 hover:text-emerald-700">Política de Privacidad</a>
                        para más detalles.
                    </p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Puedo usar IslaControl desde varios dispositivos?</h3>
                    <p class="text-gray-600">
                        Sí, IslaControl es una aplicación web que funciona en cualquier navegador. Puedes acceder desde
                        tu computadora, tablet o celular. Tus datos se sincronizan automáticamente.
                    </p>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Cómo agrego productos con código de barras?</h3>
                    <p class="text-gray-600">
                        En la sección de Productos, puedes hacer clic en el icono del escáner de código de barras
                        para utilizar la cámara de tu dispositivo y escanear el código. También puedes escribir el
                        código manualmente.
                    </p>
                </div>

                <!-- FAQ Item 5 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Cómo funciona el control de caja?</h3>
                    <p class="text-gray-600">
                        El sistema de control de caja te permite registrar la apertura y cierre de tu caja diaria.
                        Puedes asignar cajeros, registrar el monto inicial, y al cerrar, el sistema calculará
                        automáticamente las ventas del día y te mostrará si hay diferencias.
                    </p>
                </div>

                <!-- FAQ Item 6 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Puedo exportar mis reportes?</h3>
                    <p class="text-gray-600">
                        Sí, en la sección de Reportes puedes generar reportes de ventas por fecha y descargarlos
                        en formato PDF. Los reportes incluyen resumen de ventas, productos vendidos y detalles de pago.
                    </p>
                </div>

                <!-- FAQ Item 7 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Qué es el asistente AI?</h3>
                    <p class="text-gray-600">
                        IslaControl incluye un asistente de inteligencia artificial que puede ayudarte a analizar tus
                        ventas, obtener insights de tu negocio y responder preguntas sobre tu inventario. El asistente
                        corre localmente en tu dispositivo para mayor privacidad.
                    </p>
                </div>

                <!-- FAQ Item 8 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿IslaControl tiene costo?</h3>
                    <p class="text-gray-600">
                        Actualmente IslaControl es gratuito. Si en el futuro introducimos planes de pago, te
                        notificaremos con anticipación y los usuarios actuales tendrán condiciones preferenciales.
                    </p>
                </div>

                <!-- FAQ Item 9 -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Cómo elimino mi cuenta?</h3>
                    <p class="text-gray-600">
                        Desde la sección de Configuración, puedes solicitar la eliminación de tu cuenta. Tus datos
                        serán borrados permanentemente dentro de 30 días. Este proceso es irreversible.
                    </p>
                </div>

                <!-- FAQ Item 10 -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No encuentro la respuesta a mi pregunta</h3>
                    <p class="text-gray-600">
                        Si no encontraste la respuesta que buscabas, envíanos un correo a
                        <a href="mailto:soporte@islacontrol.com" class="text-emerald-600 hover:text-emerald-700">soporte@islacontrol.com</a>
                        y con gusto te ayudaremos.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Info Box -->
        <div class="mt-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-8 text-white">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">¿Necesitas más ayuda?</h2>
                <p class="text-emerald-50 mb-6">Nuestro equipo de soporte está listo para asistirte</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="mailto:soporte@islacontrol.com" class="bg-white text-emerald-600 px-6 py-3 rounded-lg font-semibold hover:bg-emerald-50 transition-colors inline-flex items-center gap-2">
                        <i class='bx bx-envelope'></i>
                        Enviar correo
                    </a>
                    <a href="{{ route('welcome') }}" class="bg-emerald-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-800 transition-colors inline-flex items-center gap-2">
                        <i class='bx bx-home'></i>
                        Ir al inicio
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
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
