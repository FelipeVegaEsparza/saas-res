<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class MigrateAllTenantsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate-all {--fresh : Drop all tables and re-run all migrations} {--seed : Seed the database after migrating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for all tenants';

    /**
     * Execute the console command.
     */
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
            $this->info("Migrating tenant: {$tenant->id}");

            try {
                // Inicializar tenancy
                tenancy()->initialize($tenant);

                // Ejecutar migraciones
                if ($this->option('fresh')) {
                    $this->call('migrate:fresh', [
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);
                } else {
                    $this->call('migrate', [
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);
                }

                // Seed si se especifica
                if ($this->option('seed')) {
                    $this->call('db:seed', [
                        '--class' => 'TenantSeeder',
                        '--force' => true,
                    ]);
                }

                $this->info("✓ Tenant {$tenant->id} migrated successfully.");
                $this->newLine();

            } catch (\Exception $e) {
                $this->error("✗ Error migrating tenant {$tenant->id}: {$e->getMessage()}");
                $this->newLine();
            } finally {
                // Limpiar tenancy
                tenancy()->end();
            }
        }

        $this->info('All tenants processed.');
        return 0;
    }
}
