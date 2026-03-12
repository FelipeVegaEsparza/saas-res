<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'description',
        'order_id',
        'delivery_order_id',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array'
    ];

    // Relaciones
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'credit_use' => 'Uso de Crédito',
            'payment' => 'Pago',
            'adjustment' => 'Ajuste',
            default => $this->type
        };
    }

    public function getFormattedAmountAttribute()
    {
        $prefix = $this->type === 'payment' || ($this->type === 'adjustment' && $this->amount > 0) ? '+' : '-';
        return $prefix . formatPrice(abs($this->amount));
    }

    // Scopes
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
