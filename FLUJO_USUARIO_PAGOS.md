# 📋 Flujo de Usuario: Sistema de Pagos

## 🎯 Resumen del Flujo Completo

Este documento explica cómo funciona el sistema de pagos desde el punto de vista del usuario, ya sea nuevo o existente.

---

## 👤 USUARIO NUEVO (Sin cuenta)

### Paso 1: Usuario ve la página de bienvenida
- URL: `http://tu-dominio.com/`
- Ve 3 planes disponibles: **Básico ($19/mes)**, **Pro ($49/mes)** y **Empresarial ($149/mes)**
- El Plan Básico tiene **30 días de prueba gratis** ✨

### Paso 2: Usuario selecciona un plan
- Click en "Seleccionar Plan" de cualquiera de los 3 planes
- Como NO está registrado, el sistema:
  1. Guarda el plan seleccionado en `sessionStorage`
  2. Lo redirige a `/register?plan=basico` (por ejemplo)

### Paso 3: Página de registro
- URL: `http://tu-dominio.com/register?plan=basico`
- Ve un mensaje: **"📦 Plan Básico seleccionado"** (para recordarle qué va a comprar)
- Rellena el formulario:
  - Nombre completo
  - Email
  - Contraseña
- Click en "REGISTRARME"

### Paso 4: Registro exitoso con Firebase
- Se crea la cuenta en Firebase
- Se autentica automáticamente
- Mensaje: "¡Registro exitoso! Redirigiendo..."
- **Automáticamente** es redirigido a: `/suscripcion/checkout/{plan}`

### Paso 5: Página de Checkout (Pago)
- URL: `http://tu-dominio.com/suscripcion/checkout/basico`
- Ve:
  - Detalles del plan seleccionado
  - Precio: $19/mes
  - Si tiene trial: "30 días de prueba gratis - No se te cobrará hasta después de 30 días"
  - Formulario de pago con Stripe Elements
- Ingresa los datos de su tarjeta:
  - Nombre del titular
  - Número de tarjeta (para pruebas: `4242 4242 4242 4242`)
  - Fecha de expiración (cualquier fecha futura)
  - CVC (cualquier 3 dígitos)

### Paso 6: Procesamiento del pago
- Click en "Comenzar prueba gratis" (si tiene trial) o "Suscribirse ahora"
- Stripe procesa el pago de forma segura
- Laravel Cashier crea la suscripción
- Si tiene 30 días de prueba, NO se cobra inmediatamente

### Paso 7: Suscripción completada
- Redirigido a: `/suscripcion/dashboard`
- Ve su plan activo
- Puede empezar a usar el sistema completo ✅

---

## 👤 USUARIO REGISTRADO (Con cuenta pero sin suscripción)

### Paso 1: Usuario inicia sesión
- Ya tiene cuenta creada
- Inicia sesión con email/contraseña o Google

### Paso 2: Ve la página de bienvenida
- URL: `http://tu-dominio.com/`
- Ve los 3 planes disponibles

### Paso 3: Selecciona un plan
- Click en "Seleccionar Plan"
- Como YA está autenticado:
  - **Directamente** va a `/suscripcion/checkout/{plan}`
  - No necesita registrarse de nuevo

### Paso 4-7: Igual que usuario nuevo
- Proceso de pago igual a partir del Paso 5 anterior

---

## 👤 USUARIO CON SUSCRIPCIÓN ACTIVA

### Caso 1: Quiere cambiar de plan
1. Va a `/suscripcion/dashboard`
2. Ve su plan actual
3. Click en "Cambiar plan"
4. Selecciona el nuevo plan
5. El cambio se aplica inmediatamente con prorrateo

### Caso 2: Quiere cancelar
1. Va a `/suscripcion/dashboard`
2. Click en "Cancelar suscripción"
3. La suscripción se cancela pero sigue activa hasta el final del período pagado
4. Puede reanudar en cualquier momento antes de que expire

### Caso 3: Quiere ver facturas
1. Va a `/suscripcion/facturas`
2. Ve historial completo de pagos
3. Puede descargar cada factura en PDF

---

## 🔄 Flujos Especiales

### Usuario con suscripción intenta comprar otro plan
- Si ya tiene suscripción activa y hace click en "Seleccionar Plan":
  - Es redirigido a `/suscripcion/dashboard`
  - Mensaje: "Ya tienes una suscripción activa"
  - Debe cambiar de plan desde el dashboard

### Usuario nuevo cancela en el checkout
- Puede cerrar la página en cualquier momento
- No se le cobra nada
- Puede volver más tarde y seleccionar el plan de nuevo

### Usuario con trial de 30 días
- Días 1-30: Usa el sistema gratis, no se le cobra
- Día 30: Stripe automáticamente cobra los $19
- Si cancela antes del día 30: No se le cobra nada

---

## 💳 Tarjetas de Prueba (Testing)

Para probar el sistema sin hacer cargos reales:

### Tarjeta que funciona correctamente:
```
Número: 4242 4242 4242 4242
Fecha: Cualquier fecha futura (ej: 12/25)
CVC: Cualquier 3 dígitos (ej: 123)
ZIP: Cualquier código postal
```

### Tarjeta que requiere autenticación 3D Secure:
```
Número: 4000 0025 0000 3155
```

### Tarjeta que falla:
```
Número: 4000 0000 0000 9995
```

---

## 🛡️ Seguridad

- **Todos los pagos** se procesan a través de Stripe (PCI DSS compliant)
- **Nunca** almacenamos números de tarjeta en nuestra base de datos
- Los tokens de pago son manejados por Stripe Elements
- Conexión segura HTTPS requerida en producción

---

## 📊 Dashboard de Suscripción

Cuando un usuario tiene suscripción activa, en `/suscripcion/dashboard` puede ver:

- ✅ Plan actual
- ✅ Precio mensual
- ✅ Fecha del próximo cobro
- ✅ Estado (activo, cancelado, en período de prueba)
- ✅ Método de pago guardado
- ✅ Opciones para:
  - Cambiar de plan (upgrade/downgrade)
  - Cancelar suscripción
  - Reanudar suscripción (si está cancelada)
  - Ver historial de facturas

---

## 🔔 Notificaciones Automáticas

El sistema envía emails automáticos para:

- ✅ Bienvenida al registrarse
- ✅ Confirmación de suscripción
- ✅ Recordatorio antes de que termine el trial
- ✅ Confirmación de pago exitoso (factura)
- ✅ Alerta de pago fallido
- ✅ Confirmación de cancelación

---

## ❓ Preguntas Frecuentes

### ¿Qué pasa si un usuario nuevo no completa el registro?
- No se crea ninguna cuenta
- No se procesa ningún pago
- Puede intentar de nuevo cuando quiera

### ¿Qué pasa si un usuario se registra pero no completa el pago?
- La cuenta se crea exitosamente
- NO tiene suscripción activa
- Puede ver la app pero sin acceso completo
- Puede ir a `/suscripcion/planes` y seleccionar un plan cuando quiera

### ¿Puede un usuario tener múltiples suscripciones?
- No, el sistema actual solo permite 1 suscripción activa por usuario
- Si quiere cambiar, debe hacerlo desde el dashboard

### ¿Qué pasa después del trial de 30 días?
- Día 30: Stripe intenta cobrar automáticamente
- Si el pago es exitoso: La suscripción continúa normalmente
- Si el pago falla: Se envía notificación y se reintenta según configuración

---

## 🎨 Mejoras Futuras Sugeridas

1. **Middleware de verificación**: Proteger rutas del sistema que requieren suscripción activa
2. **Límites por plan**: Implementar restricciones de productos/clientes/ventas según el plan
3. **Panel de administración**: Ver todas las suscripciones activas
4. **Cupones de descuento**: Integración con Stripe Coupons
5. **Pagos anuales**: Opción de pagar anualmente con descuento
6. **Facturación personalizada**: Agregar RFC/datos fiscales

---

## 📞 Soporte

Si un usuario tiene problemas con el pago:
1. Verificar que las keys de Stripe están configuradas correctamente
2. Revisar los logs de Stripe Dashboard
3. Verificar el webhook está funcionando
4. Revisar los logs de Laravel (`storage/logs/laravel.log`)

---

¡El sistema está listo para recibir pagos! 🎉
