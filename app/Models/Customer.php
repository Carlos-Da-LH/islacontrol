<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

     /**
     * Define los campos que pueden ser asignados masivamente.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone_number', 'email'];

    /**
     * Define la relación con la tabla `sales`.
     * Un cliente puede tener muchas ventas.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
