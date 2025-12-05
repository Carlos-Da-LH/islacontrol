# 🧠 Memoria de Conversación - Isla AI

Isla ahora tiene memoria! Recuerda todo lo que hablan y puede hacer referencias a conversaciones anteriores.

## ✨ ¿Qué hace?

- **Guarda cada mensaje** del usuario y cada respuesta de Isla
- **Recuerda contexto** de conversaciones anteriores
- **Hace referencias naturales** como "como te decía antes...", "siguiendo con lo que me preguntaste..."
- **Conversaciones fluidas** como si fuera una persona real que recuerda

## 🗄️ Base de datos

Se creó la tabla `conversations` con:
- `user_id` - Usuario que habla con Isla
- `session_id` - Identificador de sesión (para agrupar conversaciones)
- `role` - `user` o `assistant` (quien habla)
- `message` - El mensaje
- `context` - Datos del negocio en ese momento (JSON)
- `created_at` - Cuándo se dijo

## 🔌 API Endpoints

### 1. Chat con memoria
```javascript
POST /api/ollama/chat

{
  "question": "Hola Isla!",
  "user_id": 1,              // Opcional, toma auth()->id()
  "session_id": "session_1_123",  // Opcional, se crea automáticamente
  "context": {
    "sales": 45,
    "revenue": 5600
    // ... datos del negocio
  }
}

// Respuesta
{
  "success": true,
  "response": "Hola! Qué tal? 😊",
  "session_id": "session_1_123",  // Para siguientes mensajes
  "model": "Ollama (llama3.2:3b)"
}
```

### 2. Ver historial
```javascript
GET /api/ollama/history?user_id=1&session_id=session_1_123

// Respuesta
{
  "success": true,
  "history": [
    {
      "role": "user",
      "message": "Hola Isla!",
      "created_at": "2025-11-28 20:15:00"
    },
    {
      "role": "assistant",
      "message": "Hola! Qué tal? 😊",
      "created_at": "2025-11-28 20:15:01"
    }
  ],
  "count": 2
}
```

### 3. Limpiar historial (nueva conversación)
```javascript
POST /api/ollama/clear-history

{
  "user_id": 1,
  "session_id": "session_1_123"
}

// Respuesta
{
  "success": true,
  "message": "Historial limpiado",
  "new_session_id": "session_1_456"  // Nueva sesión
}
```

### 4. Crear nueva sesión
```javascript
POST /api/ollama/new-session

{
  "user_id": 1
}

// Respuesta
{
  "success": true,
  "session_id": "session_1_789"
}
```

### 5. Limpiar conversaciones antiguas (+30 días)
```javascript
POST /api/ollama/clean-old

// Respuesta
{
  "success": true,
  "message": "Se eliminaron 150 conversaciones antiguas"
}
```

## 💻 Ejemplo de uso en JavaScript

```javascript
// 1. Iniciar nueva sesión
let sessionId = null;

async function startNewChat() {
  const response = await fetch('/api/ollama/new-session', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: 1 })
  });

  const data = await response.json();
  sessionId = data.session_id;
}

// 2. Enviar mensaje (mantiene contexto)
async function sendMessage(message) {
  const response = await fetch('/api/ollama/chat', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      question: message,
      session_id: sessionId,  // Importante: mantener session_id
      user_id: 1,
      context: {
        sales: 45,
        revenue: 5600
      }
    })
  });

  const data = await response.json();

  // Guardar session_id si es la primera vez
  if (!sessionId) {
    sessionId = data.session_id;
  }

  return data.response;
}

// 3. Ver historial completo
async function getHistory() {
  const response = await fetch(
    `/api/ollama/history?user_id=1&session_id=${sessionId}`
  );

  const data = await response.json();
  console.log('Historial:', data.history);
}

// 4. Resetear conversación
async function resetChat() {
  const response = await fetch('/api/ollama/clear-history', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      user_id: 1,
      session_id: sessionId
    })
  });

  const data = await response.json();
  sessionId = data.new_session_id;  // Nueva sesión
}

// Uso
await startNewChat();
await sendMessage("Hola Isla!");
await sendMessage("Cómo van las ventas?");  // Isla recuerda que ya saludaste
await sendMessage("Y qué me recomiendas?");  // Isla recuerda todo el contexto
```

## 🎯 Cómo funciona

1. **Primera vez**: Si no envías `session_id`, se crea uno automático
2. **Siguientes mensajes**: Usa el mismo `session_id` para mantener contexto
3. **Historial**: Isla ve los últimos 10 mensajes para dar contexto
4. **Referencias**: Isla puede decir cosas como:
   - "Como te decía antes..."
   - "Siguiendo con lo que me preguntaste..."
   - "Ah sí, recuerdo que mencionaste..."

## 🧹 Limpieza automática

Las conversaciones de más de 30 días se eliminan automáticamente con:
```bash
POST /api/ollama/clean-old
```

Puedes programar esto en un cron job o tarea programada de Laravel.

## 📝 Notas importantes

- Cada usuario puede tener múltiples sesiones
- Cada sesión es una conversación independiente
- El `session_id` es único: `session_{user_id}_{timestamp}`
- Guarda el `session_id` en localStorage o sessionStorage del frontend
- Si pierdes el `session_id`, se crea una nueva conversación

## 🚀 Próximas mejoras

- [ ] Streaming de respuestas (escribir palabra por palabra)
- [ ] Contexto rico del negocio (acceso directo a DB)
- [ ] Sugerencias proactivas basadas en datos
- [ ] Detección de sentimiento del usuario
- [ ] Personalidad adaptativa según hora del día

---

**Isla ahora recuerda todo! 🧠✨**
