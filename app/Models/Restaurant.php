<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'landlord';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'domain',
        'db_name',
        'db_username',
        'db_password',
        'active',
        'plan',
        'trial_ends_at',
        'subscribed_at',
        'settings',
        'rut',
        'logo_horizontal',
        'logo_square',
        'phone',
        'address',
        'description',
        'facebook',
        'instagram',
        'tiktok',
        'twitter',
        'menu_background_image',
        'menu_color_scheme',
        'accepts_online_orders',
        'delivery_fee',
        'min_order_amount',
        'country',
        'currency',
        'currency_symbol',
    ];

    protected $casts = [
        'active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscribed_at' => 'datetime',
        'settings' => 'array',
        'accepts_online_orders' => 'boolean',
        'delivery_fee' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    /**
     * Relación con suscripciones
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    /**
     * Relación con tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }


    /**
     * Suscripción activa
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Verificar si está en período de prueba
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Verificar si tiene suscripción activa
     */
    public function subscribed(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Obtener URL del subdominio
     */
    public function getUrlAttribute(): string
    {
        return 'https://' . $this->domain;
    }

    /**
     * Obtener URL del menú
     */
    public function getMenuUrlAttribute(): string
    {
        return $this->url . '/menu';
    }
}
