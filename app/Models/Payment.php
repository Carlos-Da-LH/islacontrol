<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_id',
        'stripe_payment_id',
        'status',
        'amount',
        'payment_method_type',
    ];

    /**
     * Define la relación con la tabla `sales`.
     * Un pago pertenece a una venta.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
