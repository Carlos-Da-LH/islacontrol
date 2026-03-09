<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00D084" />
    <title>Términos de Servicio - IslaControl</title>
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
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Términos de Servicio</h1>
            <p class="text-gray-600 mb-8">Última actualización: {{ date('d/m/Y') }}</p>

            <div class="space-y-6 text-gray-700 leading-relaxed">
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">1. Aceptación de los Términos</h2>
                    <p>
                        Al acceder y utilizar IslaControl ("la aplicación"), aceptas estar sujeto a estos Términos de Servicio.
                        Si no estás de acuerdo con alguna parte de estos términos, no debes utilizar nuestra aplicación.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">2. Descripción del Servicio</h2>
                    <p>
                        IslaControl es una aplicación de gestión empresarial que te permite administrar:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Inventario de productos</li>
                        <li>Registro de ventas</li>
                        <li>Base de datos de clientes</li>
                        <li>Control de caja</li>
                        <li>Gestión de cajeros</li>
                        <li>Generación de reportes</li>
                        <li>Asistente de inteligencia artificial</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">3. Registro de Cuenta</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">3.1 Requisitos</h3>
                    <p>Para utilizar IslaControl, debes:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Tener al menos 18 años de edad</li>
                        <li>Proporcionar información precisa y completa durante el registro</li>
                        <li>Mantener la seguridad de tu cuenta y contraseña</li>
                        <li>Notificarnos inmediatamente sobre cualquier uso no autorizado</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">3.2 Responsabilidad de la Cuenta</h3>
                    <p>
                        Eres responsable de todas las actividades que ocurran bajo tu cuenta. No compartas tus
                        credenciales de acceso con terceros.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">4. Uso Aceptable</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">4.1 Está Permitido:</h3>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Usar la aplicación para gestionar tu negocio legítimo</li>
                        <li>Almacenar información de productos, clientes y ventas</li>
                        <li>Generar reportes y analíticas de tu negocio</li>
                        <li>Utilizar todas las funciones disponibles</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">4.2 Está Prohibido:</h3>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Usar la aplicación para actividades ilegales</li>
                        <li>Intentar acceder a cuentas de otros usuarios</li>
                        <li>Interferir con el funcionamiento de la aplicación</li>
                        <li>Realizar ingeniería inversa del software</li>
                        <li>Copiar, modificar o distribuir el código de la aplicación</li>
                        <li>Utilizar bots o scripts automatizados sin autorización</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">5. Propiedad Intelectual</h2>
                    <p>
                        IslaControl y todo su contenido, características y funcionalidad son propiedad de sus desarrolladores
                        y están protegidos por las leyes de derechos de autor y propiedad intelectual.
                    </p>
                    <p class="mt-2">
                        <strong>Tus datos te pertenecen.</strong> Mantienes todos los derechos sobre la información que ingresas
                        en la aplicación (productos, clientes, ventas, etc.).
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">6. Privacidad y Protección de Datos</h2>
                    <p>
                        Tu privacidad es importante para nosotros. El uso de tu información personal está regido por nuestra
                        <a href="{{ route('legal.privacy') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">Política de Privacidad</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">7. Disponibilidad del Servicio</h2>
                    <p>
                        Nos esforzamos por mantener IslaControl disponible 24/7, pero no garantizamos que el servicio
                        esté libre de interrupciones. Podemos:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Realizar mantenimiento programado</li>
                        <li>Actualizar funcionalidades</li>
                        <li>Suspender temporalmente el servicio por razones técnicas</li>
                    </ul>
                    <p class="mt-2">
                        Te notificaremos con anticipación cuando sea posible.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">8. Respaldos y Pérdida de Datos</h2>
                    <p>
                        Aunque realizamos respaldos regulares, <strong>te recomendamos mantener copias de seguridad
                        de tu información crítica</strong>. No somos responsables por pérdida de datos debido a:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Fallos técnicos</li>
                        <li>Errores del usuario</li>
                        <li>Eliminación accidental de datos</li>
                        <li>Circunstancias fuera de nuestro control</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">9. Tarifas y Pagos</h2>
                    <p>
                        IslaControl actualmente se ofrece de forma gratuita. Si en el futuro implementamos planes de pago:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Te notificaremos con 30 días de anticipación</li>
                        <li>Tendrás la opción de aceptar las nuevas condiciones o cerrar tu cuenta</li>
                        <li>Los usuarios existentes recibirán condiciones preferenciales</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">10. Terminación del Servicio</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">10.1 Por tu parte:</h3>
                    <p>
                        Puedes cancelar tu cuenta en cualquier momento desde la configuración. Tus datos serán eliminados
                        según nuestra Política de Privacidad.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-4">10.2 Por nuestra parte:</h3>
                    <p>
                        Podemos suspender o terminar tu cuenta si:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Violas estos Términos de Servicio</li>
                        <li>Usas la aplicación para actividades ilegales</li>
                        <li>Tu cuenta permanece inactiva por más de 2 años</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">11. Limitación de Responsabilidad</h2>
                    <p>
                        IslaControl se proporciona "tal cual" sin garantías de ningún tipo. No somos responsables por:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4 mt-2">
                        <li>Pérdidas financieras derivadas del uso de la aplicación</li>
                        <li>Interrupciones del servicio</li>
                        <li>Errores en reportes o cálculos (aunque nos esforzamos por minimizarlos)</li>
                        <li>Decisiones comerciales tomadas basándose en la información de la app</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">12. Cambios a los Términos</h2>
                    <p>
                        Podemos modificar estos Términos de Servicio ocasionalmente. Los cambios importantes serán
                        notificados con al menos 15 días de anticipación. El uso continuado de la aplicación después
                        de la modificación constituye tu aceptación de los nuevos términos.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">13. Ley Aplicable</h2>
                    <p>
                        Estos términos se rigen por las leyes aplicables en tu jurisdicción. Cualquier disputa será
                        resuelta mediante arbitraje o en los tribunales competentes.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">14. Contacto</h2>
                    <p class="mb-2">Para preguntas sobre estos Términos de Servicio:</p>
                    <div class="bg-gray-50 rounded-lg p-4 mt-3">
                        <p><strong>Email:</strong> <a href="mailto:soporte@islacontrol.com" class="text-emerald-600 hover:text-emerald-700">soporte@islacontrol.com</a></p>
                        <p class="mt-2"><strong>Sitio web:</strong> <a href="{{ route('welcome') }}" class="text-emerald-600 hover:text-emerald-700">{{ url('/') }}</a></p>
                    </div>
                </section>

                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mt-8">
                    <p class="text-emerald-800 font-semibold">
                        Al utilizar IslaControl, confirmas que has leído, entendido y aceptado estos Términos de Servicio.
                    </p>
                </div>
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
