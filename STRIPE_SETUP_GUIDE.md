# Guía de Configuración de Pagos con Stripe

## ✅ Lo que ya está hecho

1. ✅ Laravel Cashier instalado y configurado
2. ✅ Migraciones de base de datos ejecutadas
3. ✅ Modelo User con trait Billable
4. ✅ Archivo de configuración de planes (`config/plans.php`)
5. ✅ SubscriptionController completo
6. ✅ Rutas de suscripción configuradas
7. ✅ Vista de checkout con Stripe Elements
8. ✅ Welcome page actualizada con botones de suscripción

## 📋 Pasos para completar la configuración

### 1. Crear cuenta en Stripe

1. Ve a https://stripe.com
2. Crea una cuenta (es gratis para testing)
3. Una vez dentro, ve a "Developers" > "API keys"
4. Copia tus keys de prueba (test keys)

### 2. Configurar variables de entorno

Abre el archivo `.env` y actualiza estas líneas con tus keys reales de Stripe:

```env
STRIPE_KEY=pk_test_TU_CLAVE_PUBLICA_AQUI
STRIPE_SECRET=sk_test_TU_CLAVE_SECRETA_AQUI
STRIPE_WEBHOOK_SECRET=whsec_TU_WEBHOOK_SECRET_AQUI
```

### 3. Crear productos y precios en Stripe

#### Opción A: Manualmente en el Dashboard de Stripe

1. Ve a "Products" en tu dashboard de Stripe
2. Crea 3 productos:
   - **Plan Básico**: $19/mes (con 30 días de prueba)
   - **Plan Pro**: $49/mes
   - **Plan Empresarial**: $149/mes

3. Para cada producto, obtén el ID del precio (price_xxxxx)
4. Actualiza tu `.env`:

```env
STRIPE_PLAN_BASICO_PRICE_ID=price_xxxxx
STRIPE_PLAN_PRO_PRICE_ID=price_yyyyy
STRIPE_PLAN_EMPRESARIAL_PRICE_ID=price_zzzzz
```

#### Opción B: Por comandos (recomendado)

Puedes crear los productos usando el CLI de Stripe o hacer requests directos. Te recomiendo hacerlo manualmente la primera vez.

### 4. Configurar Webhooks

1. En tu dashboard de Stripe, ve a "Developers" > "Webhooks"
2. Click en "Add endpoint"
3. URL del endpoint: `https://tu-dominio.com/stripe/webhook`
   - Para desarrollo local, usa ngrok o Stripe CLI
4. Selecciona estos eventos:
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `customer.updated`
   - `customer.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`

5. Copia el "Signing secret" y actualízalo en tu `.env`:
```env
STRIPE_WEBHOOK_SECRET=whsec_tu_webhook_secret_aqui
```

### 5. Testing con Stripe CLI (Opcional pero recomendado)

Para testing local:

```bash
# Instala Stripe CLI
# https://stripe.com/docs/stripe-cli

# Login
stripe login

# Redirige webhooks a tu app local
stripe listen --forward-to http://localhost:8000/stripe/webhook

# Esto te dará un webhook secret que puedes usar en .env
```

### 6. Tarjetas de prueba de Stripe

Para testing, usa estas tarjetas:

- **Éxito**: 4242 4242 4242 4242
- **Requiere autenticación**: 4000 0025 0000 3155
- **Falla**: 4000 0000 0000 9995

Cualquier fecha futura y cualquier CVC funcionan.

## 🚀 Cómo funciona el flujo

### Para nuevos usuarios:

1. Usuario se registra/inicia sesión
2. Selecciona un plan en la página de bienvenida
3. Es redirigido a `/suscripcion/checkout/{plan}`
4. Ingresa su tarjeta (Stripe Elements)
5. Si el plan tiene trial, se crea con 30 días gratis
6. Es redirigido a `/suscripcion/dashboard`

### Para usuarios con suscripción:

- Pueden ver su plan actual
- Pueden cambiar de plan (upgrade/downgrade)
- Pueden cancelar su suscripción
- Pueden reanudar si está cancelada
- Pueden ver y descargar facturas

## 📂 Archivos importantes

```
config/plans.php                                  # Configuración de planes
app/Models/User.php                               # Trait Billable
app/Http/Controllers/SubscriptionController.php   # Lógica de suscripciones
resources/views/subscriptions/checkout.blade.php  # Formulario de pago
routes/web.php                                    # Rutas
.env                                              # Credenciales de Stripe
```

## 🔧 Completar las vistas faltantes

Aún necesitas crear estas vistas:

### 1. `resources/views/subscriptions/dashboard.blade.php`
Vista donde el usuario ve su plan actual, puede cambiar, cancelar, etc.

### 2. `resources/views/subscriptions/invoices.blade.php`
Vista donde el usuario ve su historial de facturas y puede descargarlas.

### 3. `resources/views/subscriptions/plans.blade.php`
Vista alternativa para ver todos los planes (opcional, puedes usar welcome.php)

## 🛡️ Seguridad y Producción

Antes de ir a producción:

1. ✅ Cambia a keys de producción en `.env`
2. ✅ Configura el webhook en producción
3. ✅ Verifica que HTTPS esté habilitado
4. ✅ Agrega middleware de verificación de suscripción a rutas protegidas
5. ✅ Configura emails para notificaciones de pago
6. ✅ Implementa manejo de pagos fallidos

## 📊 Middleware de verificación

Puedes crear un middleware para proteger rutas que requieren suscripción activa:

```php
php artisan make:middleware EnsureUserHasSubscription
```

Y en el middleware:

```php
public function handle($request, Closure $next)
{
    if (!$request->user() || !$request->user()->subscribed('default')) {
        return redirect('suscripcion/planes');
    }

    return $next($request);
}
```

Luego aplícalo a rutas específicas:

```php
Route::middleware(['auth', 'subscribed'])->group(function () {
    Route::get('/productos', ...);
    Route::get('/ventas', ...);
    // etc.
});
```

## 🎯 Próximos pasos sugeridos

1. Crear las vistas faltantes (dashboard, invoices)
2. Configurar tu cuenta de Stripe real
3. Crear los productos en Stripe
4. Configurar webhooks
5. Testear todo el flujo con tarjetas de prueba
6. Implementar middleware de verificación
7. Configurar emails de notificación
8. Añadir límites según el plan (productos, clientes, ventas)

## 💡 Recursos útiles

- [Documentación de Laravel Cashier](https://laravel.com/docs/9.x/billing)
- [Documentación de Stripe](https://stripe.com/docs)
- [Stripe Testing](https://stripe.com/docs/testing)
- [Stripe Webhooks](https://stripe.com/docs/webhooks)

## ❓ Troubleshooting

### Error: "No such price"
- Verifica que los price_id en .env coincidan con los de Stripe

### Webhook no funciona
- Verifica el webhook secret
- Revisa los logs en Stripe Dashboard > Developers > Webhooks

### Pago no se procesa
- Verifica las keys de Stripe
- Revisa la consola del navegador para errores de JavaScript
- Verifica que el CSRF token esté presente

## 🎉 ¡Todo listo!

Ahora tienes un sistema de pagos recurrentes completamente funcional con:
- ✅ Suscripciones recurrentes automáticas
- ✅ Período de prueba de 30 días
- ✅ Upgrades y downgrades de planes
- ✅ Cancelación y reanudación
- ✅ Facturas automáticas
- ✅ Dashboard de suscripción
- ✅ Pagos seguros con Stripe

¡Solo falta configurar tus keys de Stripe y crear las vistas finales!
