<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cashier_id',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'difference',
        'total_cash_sales',
        'total_card_sales',
        'total_transfer_sales',
        'total_sales',
        'opened_at',
        'closed_at',
        'status',
        'opening_notes',
        'closing_notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'difference' => 'decimal:2',
        'total_cash_sales' => 'decimal:2',
        'total_card_sales' => 'decimal:2',
        'total_transfer_sales' => 'decimal:2',
        'total_sales' => 'decimal:2',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el cajero
     */
    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    /**
     * Relación con las ventas
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Verificar si la caja está abierta
     */
    public function isOpen()
    {
        return $this->status === 'open';
    }

    /**
     * Verificar si la caja está cerrada
     */
    public function isClosed()
    {
        return $this->status === 'closed';
    }

    /**
     * Calcular el balance esperado
     */
    public function calculateExpectedBalance()
    {
        return $this->opening_balance + $this->total_cash_sales;
    }

    /**
     * Calcular la diferencia (faltante o sobrante)
     */
    public function calculateDifference()
    {
        if ($this->closing_balance !== null) {
            $expected = $this->calculateExpectedBalance();
            return $this->closing_balance - $expected;
        }
        return null;
    }

    /**
     * Obtener caja abierta del usuario actual
     */
    public static function getOpenCashRegister($userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 'open')
            ->orderBy('opened_at', 'desc')
            ->first();
    }

    /**
     * Actualizar totales de ventas
     */
    public function updateSalesTotals()
    {
        $sales = $this->sales()
            ->selectRaw('
                SUM(CASE WHEN payment_method = "efectivo" THEN amount ELSE 0 END) as cash_total,
                SUM(CASE WHEN payment_method IN ("tarjeta_debito", "tarjeta_credito") THEN amount ELSE 0 END) as card_total,
                SUM(CASE WHEN payment_method = "transferencia" THEN amount ELSE 0 END) as transfer_total,
                SUM(amount) as total
            ')
            ->first();

        $this->total_cash_sales = $sales->cash_total ?? 0;
        $this->total_card_sales = $sales->card_total ?? 0;
        $this->total_transfer_sales = $sales->transfer_total ?? 0;
        $this->total_sales = $sales->total ?? 0;
        $this->expected_balance = $this->calculateExpectedBalance();

        $this->save();
    }
}
