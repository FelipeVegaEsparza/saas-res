<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Table extends Model
{
    use HasFactory, SoftDeletes;

    // No especificamos conexión - usará la conexión por defecto que se cambia dinámicamente

    protected $fillable = [
        'number',
        'capacity',
        'qr_code',
        'status',
        'location',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($table) {
            if (!$table->qr_code) {
                $table->qr_code = Str::uuid();
            }
        });
    }

    /**
     * Relación con órdenes
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Orden activa
     */
    public function activeOrder()
    {
        return $this->hasOne(Order::class)
            ->whereIn('status', ['pending', 'preparing', 'ready']);
    }

    /**
     * Verificar si está disponible
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->active;
    }

    /**
     * Marcar como ocupada
     */
    public function occupy()
    {
        $this->update(['status' => 'occupied']);
    }

    /**
     * Marcar como disponible
     */
    public function free()
    {
        $this->update(['status' => 'available']);
    }
}
