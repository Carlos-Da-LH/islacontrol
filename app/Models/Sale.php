<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sale_date',
        'amount',
        'is_credit',
        'paid_amount',
        'is_paid',
        'notes',
        'user_id',
        'cash_register_id',
        'payment_method',
        'amount_received',
        'change_returned',
        'payment_reference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Relación con la caja registradora
     */
    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}