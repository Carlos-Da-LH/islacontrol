<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'status',
        'notes',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las cajas registradoras
     */
    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    /**
     * Verificar si el cajero está activo
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Scope para cajeros activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
