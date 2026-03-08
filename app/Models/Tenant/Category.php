<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // No especificamos conexión - usará la conexión por defecto que se cambia dinámicamente

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Relación con productos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Productos activos
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('available', true);
    }
}
