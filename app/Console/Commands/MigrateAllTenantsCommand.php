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

        $successCount = 0;
        $errorCount = 0;

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant: {$tenant->id}");

            try {
                // Inicializar tenancy
                tenancy()->initialize($tenant);

                // Verificar si la tabla migrations existe, si no, crearla
                $this->ensureMigrationsTable();

                // Ejecutar migraciones
                if ($this->option('fresh')) {
                    $this->call('migrate:fresh', [
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);
                } else {
                    $exitCode = $this->call('migrate', [
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);

                    if ($exitCode === 0) {
                        $successCount++;
                    }
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
                $errorCount++;
                $this->error("✗ Error migrating tenant {$tenant->id}: {$e->getMessage()}");
                $this->newLine();

                // Continuar con el siguiente tenant
                continue;
            } finally {
                // Limpiar tenancy
                tenancy()->end();
            }
        }

        $this->newLine();
        $this->info("All tenants processed.");
        $this->info("Success: {$successCount} | Errors: {$errorCount}");

        return $errorCount > 0 ? 1 : 0;
    }

    /**
     * Asegurar que la tabla migrations existe en la base de datos del tenant
     */
    private function ensureMigrationsTable()
    {
        if (!\Schema::hasTable('migrations')) {
            \Schema::create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }
    }
}
