<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00D084" />
    <title>Política de Privacidad - IslaControl</title>
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
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Política de Privacidad</h1>
            <p class="text-gray-600 mb-8">Última actualización: {{ date('d/m/Y') }}</p>

            <div class="space-y-6 text-gray-700 leading-relaxed">
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">1. Introducción</h2>
                    <p>
                        IslaControl ("nosotros", "nuestro" o "la aplicación") se compromete a proteger tu privacidad.
                        Esta Política de Privacidad explica cómo recopilamos, usamos, compartimos y protegemos tu información
                        personal cuando utilizas nuestra aplicación de gestión de negocios.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">2. Información que Recopilamos</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">2.1 Información de Cuenta</h3>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Correo electrónico</li>
                        <li>Nombre del usuario</li>
                        <li>Nombre del negocio</li>
                        <li>Tipo de negocio</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">2.2 Datos del Negocio</h3>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Información de productos (nombre, precio, código de barras, categoría)</li>
                        <li>Información de clientes (nombre, teléfono, dirección opcional)</li>
                        <li>Registros de ventas</li>
                        <li>Movimientos de caja</li>
                        <li>Información de cajeros</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">2.3 Datos de Uso</h3>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Registros de acceso y actividad en la aplicación</li>
                        <li>Preferencias de configuración</li>
                        <li>Conversaciones con el asistente AI (si se utiliza esta función)</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">3. Cómo Usamos tu Información</h2>
                    <p class="mb-2">Utilizamos la información recopilada para:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Proporcionar y mantener el servicio de gestión de negocio</li>
                        <li>Generar reportes y estadísticas de tu negocio</li>
                        <li>Gestionar inventarios, ventas y clientes</li>
                        <li>Mejorar la funcionalidad de la aplicación</li>
                        <li>Proporcionar soporte técnico</li>
                        <li>Cumplir con obligaciones legales</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">4. Compartir Información</h2>
                    <p class="mb-2">
                        <strong>No vendemos, alquilamos ni compartimos tu información personal con terceros</strong>
                        excepto en los siguientes casos:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li><strong>Proveedores de servicios:</strong> Firebase para autenticación y almacenamiento</li>
                        <li><strong>Cumplimiento legal:</strong> Cuando sea requerido por ley</li>
                        <li><strong>Con tu consentimiento:</strong> Cuando nos des permiso explícito</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">5. Seguridad de los Datos</h2>
                    <p>
                        Implementamos medidas de seguridad técnicas y organizativas para proteger tus datos:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Autenticación mediante Firebase</li>
                        <li>Cifrado de datos en tránsito (HTTPS/SSL)</li>
                        <li>Acceso restringido a datos personales</li>
                        <li>Respaldos regulares de información</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">6. Tus Derechos</h2>
                    <p class="mb-2">Tienes derecho a:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li><strong>Acceder</strong> a tus datos personales</li>
                        <li><strong>Corregir</strong> información incorrecta</li>
                        <li><strong>Eliminar</strong> tu cuenta y datos asociados</li>
                        <li><strong>Exportar</strong> tus datos en formato legible</li>
                        <li><strong>Oponerte</strong> al procesamiento de tus datos</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">7. Retención de Datos</h2>
                    <p>
                        Mantenemos tus datos mientras tu cuenta esté activa o según sea necesario para proporcionarte
                        los servicios. Si eliminas tu cuenta, borraremos permanentemente tu información personal dentro
                        de 30 días, excepto cuando la ley requiera mantenerla por más tiempo.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">8. Uso del Asistente AI</h2>
                    <p>
                        IslaControl incluye un asistente de inteligencia artificial local (Ollama). Las conversaciones
                        con el asistente se almacenan localmente en tu dispositivo y no se comparten con terceros.
                        Puedes eliminar el historial de conversaciones en cualquier momento desde la configuración.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">9. Cookies y Tecnologías Similares</h2>
                    <p>
                        Utilizamos cookies y tecnologías similares para mantener tu sesión activa y mejorar tu experiencia.
                        Puedes configurar tu navegador para rechazar cookies, aunque esto puede afectar la funcionalidad
                        de la aplicación.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">10. Cambios a esta Política</h2>
                    <p>
                        Podemos actualizar esta Política de Privacidad ocasionalmente. Te notificaremos sobre cambios
                        significativos publicando la nueva política en esta página y actualizando la fecha de "Última actualización".
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">11. Contacto</h2>
                    <p class="mb-2">Si tienes preguntas sobre esta Política de Privacidad, contáctanos:</p>
                    <div class="bg-gray-50 rounded-lg p-4 mt-3">
                        <p><strong>Email:</strong> <a href="mailto:soporte@islacontrol.com" class="text-emerald-600 hover:text-emerald-700">soporte@islacontrol.com</a></p>
                        <p class="mt-2"><strong>Sitio web:</strong> <a href="{{ route('welcome') }}" class="text-emerald-600 hover:text-emerald-700">{{ url('/') }}</a></p>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} IslaControl. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
