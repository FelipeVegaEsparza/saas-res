<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FixTenantMigrationsCommand extends Command
{
    protected $signature = 'tenants:fix-migrations';
    protected $description = 'Registra las migraciones existentes en la tabla migrations de cada tenant';

    public function handle()
    {
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found.');
            return 0;
        }

        $this->info("Found {$tenants->count()} tenant(s).");
        $this->newLine();

        foreach ($tenants as $tenant) {
            $this->info("Fixing migrations for tenant: {$tenant->id}");

            try {
                tenancy()->initialize($tenant);

                // Obtener todas las migraciones del directorio
                $migrationFiles = File::files(database_path('migrations/tenant'));
                $migrationNames = collect($migrationFiles)->map(function ($file) {
                    return pathinfo($file->getFilename(), PATHINFO_FILENAME);
                })->sort()->values();

                // Obtener migraciones ya registradas
                $existingMigrations = DB::table('migrations')->pluck('migration')->toArray();

                $registered = 0;
                foreach ($migrationNames as $migration) {
                    if (!in_array($migration, $existingMigrations)) {
                        // Verificar si la tabla/columna existe antes de registrar
                        if ($this->migrationAlreadyApplied($migration)) {
                            DB::table('migrations')->insert([
                                'migration' => $migration,
                                'batch' => 1
                            ]);
                            $registered++;
                            $this->line("  ✓ Registered: {$migration}");
                        }
                    }
                }

                if ($registered > 0) {
                    $this->info("✓ Registered {$registered} existing migrations for tenant {$tenant->id}");
                } else {
                    $this->info("✓ No migrations to register for tenant {$tenant->id}");
                }
                $this->newLine();

            } catch (\Exception $e) {
                $this->error("✗ Error fixing tenant {$tenant->id}: {$e->getMessage()}");
                $this->newLine();
            } finally {
                tenancy()->end();
            }
        }

        $this->info('All tenants processed. Now you can run: php artisan tenants:migrate-all');
        return 0;
    }

    /**
     * Verificar si una migración ya fue aplicada verificando si la tabla/columna existe
     */
    private function migrationAlreadyApplied($migration)
    {
        // Extraer el nombre de la tabla de la migración
        if (str_contains($migration, 'create_tenant_users_table')) {
            return \Schema::hasTable('users');
        }
        if (str_contains($migration, 'create_tenant_categories_table')) {
            return \Schema::hasTable('categories');
        }
        if (str_contains($migration, 'create_tenant_products_table')) {
            return \Schema::hasTable('products');
        }
        if (str_contains($migration, 'create_tenant_tables_table')) {
            return \Schema::hasTable('tables');
        }
        if (str_contains($migration, 'create_tenant_orders_table')) {
            return \Schema::hasTable('orders');
        }
        if (str_contains($migration, 'create_tenant_order_items_table')) {
            return \Schema::hasTable('order_items');
        }
        if (str_contains($migration, 'create_tenant_payments_table')) {
            return \Schema::hasTable('payments');
        }
        if (str_contains($migration, 'create_tenant_cash_sessions_table')) {
            return \Schema::hasTable('cash_sessions');
        }
        if (str_contains($migration, 'create_delivery_orders_table')) {
            return \Schema::hasTable('delivery_orders');
        }
        if (str_contains($migration, 'add_preparation_area_id_to_products')) {
            return \Schema::hasColumn('products', 'preparation_area_id');
        }
        if (str_contains($migration, 'add_preparation_status_to_order_items')) {
            return \Schema::hasColumn('order_items', 'preparation_status');
        }
        if (str_contains($migration, 'add_preparation_status_to_delivery_order_items')) {
            return \Schema::hasColumn('delivery_order_items', 'preparation_status');
        }

        // Por defecto, no registrar si no sabemos
        return false;
    }
}
