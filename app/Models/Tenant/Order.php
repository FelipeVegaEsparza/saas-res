<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\User;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // No especificamos conexión - usará la conexión por defecto que se cambia dinámicamente

    protected $fillable = [
        'order_number',
        'table_id',
        'customer_id',
        'waiter_id',
        'user_id',
        'customer_name',
        'status',
        'type',
        'subtotal',
        'tax',
        'discount',
        'total',
        'notes',
        'kitchen_notes',
        'preparing_at',
        'ready_at',
        'served_at',
        'closed_at',
        'paid_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'preparing_at' => 'datetime',
        'ready_at' => 'datetime',
        'served_at' => 'datetime',
        'closed_at' => 'datetime',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Relación con mesa
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relación con cliente
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación con usuario (mesero)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con mesero
     */
    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    /**
     * Relación con items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación con pagos
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calcular totales
     */
    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal + $this->tax - $this->discount;
        $this->save();
    }

    /**
     * Marcar como completada
     */
    public function complete()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'completed_at' => now(),
        ]);

        if ($this->table) {
            $this->table->free();
        }
    }

    /**
     * Obtener label del estado
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pendiente',
            'preparing' => 'En Preparación',
            'ready' => 'Listo',
            'served' => 'Servido',
            'closed' => 'Cuenta Cerrada',
            'paid' => 'Pagado',
            'cancelled' => 'Cancelado',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Obtener color del estado
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'preparing' => 'info',
            'ready' => 'primary',
            'served' => 'success',
            'closed' => 'secondary',
            'paid' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Verificar si puede ser editada
     */
    public function canBeEdited()
    {
        return in_array($this->status, ['pending', 'preparing']);
    }

    /**
     * Verificar si puede ser pagada
     */
    public function canBePaid()
    {
        return in_array($this->status, ['served', 'closed']);
    }
}
