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
    protected $fillable = ['name', 'phone_number', 'email', 'user_id'];

    /**
     * Define la relación con la tabla `users`.
     * Un cliente pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación con la tabla `sales`.
     * Un cliente puede tener muchas ventas.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
