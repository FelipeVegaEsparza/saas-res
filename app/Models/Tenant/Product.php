<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'images',
        'available',
        'featured',
        'preparation_time',
        'allergens',
        'tags',
        'order',
        'track_stock',
        'stock_quantity',
        'min_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available' => 'boolean',
        'featured' => 'boolean',
        'track_stock' => 'boolean',
        'stock_quantity' => 'integer',
        'min_stock' => 'integer',
        'images' => 'array',
        'allergens' => 'array',
        'tags' => 'array',
    ];

    /**
     * Relación con categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con items de orden
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope para productos disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    /**
     * Scope para productos destacados
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Verificar si el producto tiene stock bajo
     */
    public function hasLowStock()
    {
        return $this->track_stock && $this->stock_quantity <= $this->min_stock && $this->stock_quantity > 0;
    }

    /**
     * Verificar si el producto está sin stock
     */
    public function isOutOfStock()
    {
        return $this->track_stock && $this->stock_quantity <= 0;
    }

    /**
     * Reducir stock del producto
     */
    public function reduceStock($quantity)
    {
        if ($this->track_stock) {
            $this->decrement('stock_quantity', $quantity);
        }
    }

    /**
     * Aumentar stock del producto
     */
    public function increaseStock($quantity)
    {
        if ($this->track_stock) {
            $this->increment('stock_quantity', $quantity);
        }
    }
}
