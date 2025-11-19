<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     /**
     * Define los campos que pueden ser asignados masivamente.
     *
     * @var array
     */
    protected $fillable = ['name', 'stock', 'price', 'category_id'];

    /**
     * Define la relación con la tabla `categories`.
     * Un producto pertenece a una categoría.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define la relación con la tabla `sale_items`.
     * Un producto puede estar en muchos items de venta.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
