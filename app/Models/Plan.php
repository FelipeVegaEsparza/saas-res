<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'max_products',
        'max_tables',
        'max_users',
        'custom_domain',
        'analytics',
        'active',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_domain' => 'boolean',
        'analytics' => 'boolean',
        'active' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Relación con suscripciones
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Verificar si es plan gratuito
     */
    public function isFree(): bool
    {
        return $this->price == 0;
    }
}
