<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant\Table;
use Stancl\Tenancy\Facades\Tenancy;

class SyncTableStatusCommand extends Command
{
    protected $signature = 'tenant:sync-table-status {tenant}';
    protected $description = 'Sincroniza el estado de las mesas con sus órdenes activas';

    public function handle()
    {
        $tenantId = $this->argument('tenant');

        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant {$tenantId} no encontrado");
            return 1;
        }

        Tenancy::initialize($tenant);

        $this->info("Sincronizando estado de mesas para tenant: {$tenantId}");

        $tables = Table::all();
        $updated = 0;

        foreach ($tables as $table) {
            $hasActiveOrder = $table->orders()
                ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
                ->exists();

            $expectedStatus = $hasActiveOrder ? 'occupied' : 'available';

            if ($table->status !== $expectedStatus) {
                $table->update(['status' => $expectedStatus]);
                $this->info("Mesa {$table->number}: {$table->status} -> {$expectedStatus}");
                $updated++;
            }
        }

        $this->info("Sincronización completada. {$updated} mesas actualizadas.");

        return 0;
    }
}
