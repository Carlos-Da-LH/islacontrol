<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'role',
        'message',
        'context'
    ];

    protected $casts = [
        'context' => 'array'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Obtener historial reciente de una sesión (últimos N mensajes)
    public static function getRecentHistory($userId, $sessionId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    // Limpiar conversaciones antiguas (más de X días)
    public static function cleanOldConversations($days = 30)
    {
        return self::where('created_at', '<', now()->subDays($days))->delete();
    }

    // Iniciar nueva sesión
    public static function startNewSession($userId)
    {
        return uniqid('session_' . $userId . '_');
    }

    // Obtener todas las sesiones del usuario con resumen
    public static function getUserSessions($userId)
    {
        return self::where('user_id', $userId)
            ->select('session_id')
            ->selectRaw('MIN(created_at) as started_at')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('COUNT(*) as message_count')
            ->selectRaw('(SELECT message FROM conversations WHERE session_id = conversations.session_id AND role = "user" ORDER BY created_at ASC LIMIT 1) as first_message')
            ->groupBy('session_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($session) {
                // Crear título a partir del primer mensaje
                $title = $session->first_message
                    ? (strlen($session->first_message) > 40
                        ? substr($session->first_message, 0, 40) . '...'
                        : $session->first_message)
                    : 'Conversación sin título';

                $session->title = $title;
                return $session;
            });
    }

    // Eliminar una sesión completa
    public static function deleteSession($userId, $sessionId)
    {
        return self::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->delete();
    }
}
