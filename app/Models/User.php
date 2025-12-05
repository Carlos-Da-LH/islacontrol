<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firebase_uid',
        'business_type',
        'business_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación uno a muchos con productos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relación uno a muchos con clientes
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Relación uno a muchos con ventas
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Relación uno a muchos con categorías
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
