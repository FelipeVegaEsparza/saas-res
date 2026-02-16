<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Campos adicionales del tenant
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'restaurant_name',
            'plan',
        ];
    }

    /**
     * Crear tenant con restaurante
     */
    public static function createWithRestaurant(array $data): self
    {
        $tenant = static::create([
            'restaurant_name' => $data['name'],
            'plan' => $data['plan'] ?? 'free',
        ]);

        // Crear dominio (subdominio)
        $tenant->domains()->create([
            'domain' => $data['slug'] . '.' . config('tenancy.central_domains')[2],
        ]);

        // Crear registro en tabla restaurants
        Restaurant::create([
            'tenant_id' => $tenant->id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'domain' => $data['slug'] . '.' . config('tenancy.central_domains')[2],
            'db_name' => 'tenant_' . $tenant->id,
            'active' => true,
            'plan' => $data['plan'] ?? 'free',
            'trial_ends_at' => now()->addDays(14), // 14 días de prueba
        ]);

        return $tenant;
    }

    /**
     * Obtener restaurante asociado
     */
    public function restaurant()
    {
        // Usar el modelo Restaurant que ya tiene la conexión landlord configurada
        return Restaurant::where('tenant_id', $this->id)->first();
    }
}
