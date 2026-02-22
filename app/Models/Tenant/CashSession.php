<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\User;

class CashSession extends Model
{
    use HasFactory;

    // No especificamos conexión - usará la conexión por defecto que se cambia dinámicamente

    protected $fillable = [
        'user_id',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'difference',
        'status',
        'opening_notes',
        'closing_notes',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'difference' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Relación con usuario (cajero)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con pagos
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Cerrar sesión
     */
    public function close(float $closingBalance, ?string $notes = null)
    {
        $this->update([
            'closing_balance' => $closingBalance,
            'difference' => $closingBalance - $this->expected_balance,
            'status' => 'closed',
            'closing_notes' => $notes,
            'closed_at' => now(),
        ]);
    }

    /**
     * Verificar si está abierta
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
