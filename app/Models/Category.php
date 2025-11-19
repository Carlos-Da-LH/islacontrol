<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Define los campos que pueden ser asignados masivamente.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Define la relación con la tabla `products`.
     * Una categoría puede tener muchos productos.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}