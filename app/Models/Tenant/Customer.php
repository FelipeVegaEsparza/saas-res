<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'document_type',
        'document_number',
        'credit_limit',
        'credit_used',
        'active',
        'notes'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'credit_used' => 'decimal:2',
        'active' => 'boolean'
    ];

    // Relaciones
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class)->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getCreditAvailableAttribute()
    {
        return $this->credit_limit - $this->credit_used;
    }

    public function getFullNameAttribute()
    {
        return $this->name . ($this->phone ? ' (' . $this->phone . ')' : '');
    }

    // Métodos de negocio
    public function hasAvailableCredit($amount)
    {
        return $this->credit_available >= $amount;
    }

    public function useCredit($amount, $description, $orderId = null, $deliveryOrderId = null)
    {
        if (!$this->hasAvailableCredit($amount)) {
            throw new \Exception('Crédito insuficiente');
        }

        $this->increment('credit_used', $amount);

        return $this->transactions()->create([
            'type' => 'credit_use',
            'amount' => $amount,
            'description' => $description,
            'order_id' => $orderId,
            'delivery_order_id' => $deliveryOrderId
        ]);
    }

    public function addPayment($amount, $description)
    {
        $this->decrement('credit_used', min($amount, $this->credit_used));

        return $this->transactions()->create([
            'type' => 'payment',
            'amount' => $amount,
            'description' => $description
        ]);
    }

    public function adjustCredit($amount, $description)
    {
        if ($amount > 0) {
            $this->decrement('credit_used', min($amount, $this->credit_used));
        } else {
            $this->increment('credit_used', abs($amount));
        }

        return $this->transactions()->create([
            'type' => 'adjustment',
            'amount' => $amount,
            'description' => $description
        ]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeWithCredit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('document_number', 'like', "%{$search}%");
        });
    }
}
