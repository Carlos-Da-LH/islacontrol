# 🤖 Opción B: Ollama - IA Local Gratuita

## ¿Qué es Ollama?
Ollama te permite correr modelos de IA **100% gratis** en tu servidor local, sin necesidad de APIs externas ni límites de uso.

---

## 📥 Instalación de Ollama

### **Paso 1: Descargar Ollama**
Descarga desde: https://ollama.com/download

**Para Windows:**
1. Descarga el instalador `.exe`
2. Ejecuta y sigue el asistente
3. Ollama quedará corriendo en segundo plano

**Para Linux/Mac:**
```bash
curl -fsSL https://ollama.com/install.sh | sh
```

---

## 🚀 Paso 2: Descargar un Modelo

Una vez instalado, abre una terminal y ejecuta:

```bash
# Modelo recomendado para tu caso (rápido y preciso)
ollama pull llama3.2:3b

# O si tienes más RAM (mejor calidad)
ollama pull llama3.2:7b

# O para español específico
ollama pull mistral:7b
```

**Modelos disponibles:**
- `llama3.2:3b` - 2GB RAM, muy rápido ✅ **Recomendado**
- `llama3.2:7b` - 4GB RAM, más preciso
- `mistral:7b` - 4GB RAM, excelente para español
- `gemma2:2b` - 1.5GB RAM, ultra rápido

---

## 🔧 Paso 3: Verificar que funciona

```bash
ollama run llama3.2:3b
```

Deberías ver un chat interactivo. Escribe `exit` para salir.

---

## 💻 Paso 4: Integrar con IslaControl

### **Crear API endpoint en Laravel**

1. **Crea un controlador:**

```bash
cd C:\xampp\htdocs\Islacontrol
php artisan make:controller OllamaController
```

2. **Edita el controlador** (`app/Http/Controllers/OllamaController.php`):

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OllamaController extends Controller
{
    public function chat(Request $request)
    {
        $question = $request->input('question');
        $context = $request->input('context', []);

        try {
            $response = Http::timeout(30)->post('http://localhost:11434/api/generate', [
                'model' => 'llama3.2:3b',
                'prompt' => "Eres IslaFinance IA, asistente de negocios. Analiza estos datos: " . json_encode($context) . "\n\nPregunta del usuario: " . $question,
                'stream' => false
            ]);

            return response()->json([
                'success' => true,
                'response' => $response->json()['response']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

3. **Agrega la ruta** (`routes/web.php`):

```php
Route::post('/api/ollama/chat', [OllamaController::class, 'chat']);
```

4. **Modifica el JavaScript** en `dashboard.blade.php`:

En la función `generateResponse`, agrega al final (antes del return):

```javascript
// Intentar usar Ollama si está disponible
try {
    const ollamaResponse = await fetch('/api/ollama/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            question: question,
            context: {
                sales: metrics.totalSales,
                revenue: metrics.revenueThisMonth,
                products: metrics.totalProducts,
                lowStock: metrics.lowStock,
                trend: metrics.salesTrend
            }
        })
    });

    if (ollamaResponse.ok) {
        const data = await ollamaResponse.json();
        if (data.success) {
            return {
                response: data.response,
                confidence: 0.95
            };
        }
    }
} catch (error) {
    console.log('Ollama no disponible, usando IA local');
}

// Si Ollama no está disponible, usar respuestas predefinidas
return {
    response,
    confidence: 0.90
};
```

---

## ✅ Verificar que funciona

1. **Asegúrate de que Ollama está corriendo:**
```bash
ollama list
```

Deberías ver el modelo descargado.

2. **Prueba el endpoint desde navegador:**
```
http://localhost:11434/
```

Deberías ver "Ollama is running"

3. **Recarga IslaControl y prueba la IA**

---

## 📊 Comparación de Opciones

| Característica | IA Mejorada (A) | Ollama (B) |
|----------------|-----------------|------------|
| **Costo** | $0 | $0 |
| **Instalación** | ✅ Ya funciona | Requiere setup |
| **Velocidad** | ⚡ Instantánea | 🐢 2-5 segundos |
| **Calidad** | ⭐⭐⭐ Buena | ⭐⭐⭐⭐⭐ Excelente |
| **Contexto** | Limitado | Conversacional |
| **Internet** | No requiere | No requiere |
| **RAM necesaria** | Mínima | 2-8 GB |

---

## 🎯 Recomendación

- **Usa Opción A (IA Mejorada)** → Ya está funcionando, rápida, sin setup
- **Agrega Opción B (Ollama)** → Si quieres conversaciones más naturales

Puedes tener **AMBAS**:
1. IA Mejorada para respuestas rápidas
2. Ollama para análisis complejos (se activa automáticamente si está disponible)

---

## 🆘 Problemas comunes

**Ollama no inicia:**
```bash
# Windows
net start ollama

# Linux/Mac
sudo systemctl start ollama
```

**Modelo muy lento:**
- Usa `llama3.2:3b` en lugar de modelos más grandes
- Asegúrate de tener suficiente RAM

**Error de conexión:**
- Verifica que Ollama esté en `http://localhost:11434`
- Revisa firewall/antivirus

---

## 🚀 Siguiente nivel

Una vez que funcione, puedes:
- Entrenar con tus propios datos
- Usar modelos especializados
- Implementar memoria de conversaciones
- Agregar análisis de imágenes
