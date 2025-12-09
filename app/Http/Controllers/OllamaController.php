<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Conversation;

class OllamaController extends Controller
{
    // Detectar si estamos en local o producción
    private function isLocal()
    {
        return app()->environment('local') || request()->getHost() === 'localhost' || request()->getHost() === '127.0.0.1';
    }

    // Detectar si Ollama está disponible
    private function isOllamaAvailable()
    {
        try {
            $response = Http::timeout(2)->get('http://localhost:11434/api/tags');
            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('Ollama no disponible: ' . $e->getMessage());
            return false;
        }
    }

    public function chat(Request $request)
    {
        $question = $request->input('question');
        $context = $request->input('context', []);
        $userId = auth()->id();
        $sessionId = $request->input('session_id');

        if (!$sessionId) {
            $sessionId = Conversation::startNewSession($userId);
        }

        try {
            // Guardar mensaje del usuario
            Conversation::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'role' => 'user',
                'message' => $question,
                'context' => $context
            ]);

            // Obtener historial reciente
            $history = Conversation::getRecentHistory($userId, $sessionId, 10);

            // Decidir qué API usar automáticamente
            // Local: Ollama (si está disponible) | Producción: Groq
            // TEMPORAL: Forzar Groq en local para mejor calidad
            $useOllama = false; // Cambiar a true cuando tengas llama3.1:8b
            // $useOllama = $this->isLocal() && $this->isOllamaAvailable();

            if ($useOllama) {
                // USAR OLLAMA (Local) - Requiere llama3.1:8b o superior
                $aiResponse = $this->chatWithOllama($question, $context, $history);
                $model = 'Ollama (llama3.1:8b)';
            } else {
                // USAR GROQ API (Cloud - GRATIS)
                $aiResponse = $this->chatWithGroq($question, $context, $history);
                $model = 'Groq (llama-3.3-70b)';
            }

            // Guardar respuesta de Isla
            Conversation::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'role' => 'assistant',
                'message' => $aiResponse,
                'context' => $context
            ]);

            return response()->json([
                'success' => true,
                'response' => $aiResponse,
                'session_id' => $sessionId,
                'model' => $model
            ]);

        } catch (\Exception $e) {
            Log::error('Error en chat: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Error al procesar tu mensaje',
                'details' => $e->getMessage()
            ], 503);
        }
    }

    // Llamar a Ollama (Local) - Requiere llama3.1:8b o superior
    private function chatWithOllama($question, $context, $history)
    {
        $messages = $this->buildChatMessages($question, $context, $history);

        Log::info('Ollama Request:', [
            'messages_count' => count($messages),
            'question' => $question
        ]);

        $response = Http::timeout(30)->post('http://localhost:11434/api/chat', [
            'model' => 'llama3.1:8b',
            'messages' => $messages,
            'stream' => false,
            'options' => [
                'temperature' => 0.85, // Más variado y natural
                'top_p' => 0.9,
                'top_k' => 40,
                'num_predict' => 150, // Respuestas medianas (~35-45 palabras)
                'repeat_penalty' => 1.1
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $aiResponse = $data['message']['content'] ?? 'Sin respuesta';
            
            Log::info('Ollama Response OK:', [
                'response_length' => strlen($aiResponse)
            ]);
            
            return $aiResponse;
        }

        Log::error('Ollama Error:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        throw new \Exception('Ollama no respondió correctamente: ' . $response->body());
    }

    // Llamar a Groq API (Cloud - GRATIS)
    private function chatWithGroq($question, $context, $history)
    {
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            throw new \Exception('GROQ_API_KEY no configurada en .env');
        }

        $messages = $this->buildChatMessages($question, $context, $history);

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => $messages,
                'temperature' => 0.85, // Más variado y natural
                'max_tokens' => 150, // Respuestas medianas (~35-45 palabras)
                'top_p' => 0.9,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? 'Sin respuesta';
        }

        Log::error('Groq API error: ' . $response->body());
        throw new \Exception('Groq API no respondió correctamente: ' . $response->body());
    }

    // Construir mensajes en formato chat
    private function buildChatMessages($question, $context, $history)
    {
        $messages = [];

        // 1. System prompt (personalidad de Isla)
        $systemPrompt = $this->buildSystemPrompt($context);
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt
        ];

        // 2. Historial de conversación
        if ($history->isNotEmpty()) {
            foreach ($history as $msg) {
                $messages[] = [
                    'role' => $msg->role === 'user' ? 'user' : 'assistant',
                    'content' => $msg->message
                ];
            }
        }

        // 3. Pregunta actual del usuario
        $messages[] = [
            'role' => 'user',
            'content' => $question
        ];

        return $messages;
    }

    // System prompt optimizado para respuestas cortas pero naturales
    private function buildSystemPrompt($context)
    {
        $sales = $context['sales'] ?? 0;
        $revenue = $context['revenue'] ?? 0;
        $trend = isset($context['trend']) ? round($context['trend'], 1) : 0;
        $products = $context['products'] ?? 0;
        $lowStock = $context['lowStock'] ?? 0;
        $customers = $context['customers'] ?? 0;

        $prompt = "Eres Isla, asistente inteligente de IslaControl. Eres amigable, conversacional y siempre das respuestas completas.\n\n";

        $prompt .= "ESTILO DE CONVERSACIÓN:\n";
        $prompt .= "- Hablas como una amiga experta, natural y cercana\n";
        $prompt .= "- Das respuestas completas pero sin ser tediosa\n";
        $prompt .= "- Explicas brevemente cuando sea necesario\n";
        $prompt .= "- Varía tus respuestas, NO repitas las mismas frases\n";
        $prompt .= "- Sé entusiasta y muestra interés genuino\n";
        $prompt .= "- Usa expresiones naturales: 'mira', 'fíjate', 'claro', 'genial'\n";
        $prompt .= "- Si preguntan algo técnico, explica de forma simple\n\n";

        $prompt .= "LONGITUD DE RESPUESTAS:\n";
        $prompt .= "- Respuestas medianas: 35-50 palabras (2-3 oraciones)\n";
        $prompt .= "- NO seas cortante ni demasiado breve\n";
        $prompt .= "- Añade contexto o un consejo extra cuando sea útil\n";
        $prompt .= "- Usa 1-2 emojis para dar calidez\n\n";

        $prompt .= "PERSONALIDAD:\n";
        $prompt .= "- Natural, empática y profesional\n";
        $prompt .= "- Experta en negocios pero accesible\n";
        $prompt .= "- Adaptable al tono del usuario\n";
        $prompt .= "- Puedes usar groserías ligeras ocasionalmente: 'chingón', 'qué onda'\n\n";

        $prompt .= "SOBRE ISLACONTROL:\n";
        $prompt .= "IslaControl es el SISTEMA que el usuario usa para gestionar su negocio.\n";
        $prompt .= "Di 'tu negocio', 'tu empresa', nunca 'el negocio de IslaControl'.\n\n";

        $hasBusinessContext = $sales > 0 || $revenue > 0 || $products > 0;

        if ($hasBusinessContext) {
            $prompt .= "Datos disponibles: {$sales} ventas, \${$revenue} ingresos, {$products} productos.\n\n";
        }

        $prompt .= "IMPORTANTE: Da respuestas completas y conversacionales. NO seas cortante. Varía tu estilo.";

        return $prompt;
    }

    public function getHistory(Request $request)
    {
        $userId = auth()->id();
        $sessionId = $request->input('session_id');

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'error' => 'session_id requerido'
            ], 400);
        }

        $history = Conversation::getRecentHistory($userId, $sessionId, 50);

        return response()->json([
            'success' => true,
            'history' => $history,
            'count' => $history->count()
        ]);
    }

    public function clearHistory(Request $request)
    {
        $userId = auth()->id();
        $sessionId = $request->input('session_id');

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'error' => 'session_id requerido'
            ], 400);
        }

        Conversation::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->delete();

        $newSessionId = Conversation::startNewSession($userId);

        return response()->json([
            'success' => true,
            'message' => 'Historial limpiado',
            'new_session_id' => $newSessionId
        ]);
    }

    public function newSession(Request $request)
    {
        $userId = auth()->id();
        $sessionId = Conversation::startNewSession($userId);

        return response()->json([
            'success' => true,
            'session_id' => $sessionId
        ]);
    }

    public function getSessions(Request $request)
    {
        $userId = auth()->id();
        $sessions = Conversation::getUserSessions($userId);

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
            'count' => $sessions->count()
        ]);
    }

    public function deleteSession(Request $request)
    {
        $userId = auth()->id();
        $sessionId = $request->input('session_id');

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'error' => 'session_id requerido'
            ], 400);
        }

        $deleted = Conversation::deleteSession($userId, $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Sesión eliminada',
            'deleted' => $deleted
        ]);
    }

    public function cleanOldConversations()
    {
        $deleted = Conversation::cleanOldConversations(30);

        return response()->json([
            'success' => true,
            'message' => "Se eliminaron {$deleted} conversaciones antiguas"
        ]);
    }

    public function checkStatus()
    {
        $ollamaAvailable = $this->isOllamaAvailable();
        $groqAvailable = !empty(env('GROQ_API_KEY'));

        return response()->json([
            'ollama_available' => $ollamaAvailable,
            'groq_available' => $groqAvailable,
            'active_model' => $ollamaAvailable 
                ? 'Ollama (Local)' 
                : ($groqAvailable ? 'Groq API (Cloud)' : 'Ninguno'),
            'environment' => app()->environment(),
            'message' => $ollamaAvailable 
                ? 'Ollama funcionando en local' 
                : ($groqAvailable ? 'Groq API configurada (GRATIS)' : 'Ninguna IA disponible')
        ]);
    }
}