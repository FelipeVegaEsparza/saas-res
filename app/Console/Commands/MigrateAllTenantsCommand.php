<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
                // Configurar manualmente la conexión tenant con la base de datos correcta
                $dbName = 'tenant_' . $tenant->getTenantKey();

                // Actualizar la configuración de la conexión tenant
                Config::set('database.connections.tenant.database', $dbName);
                Config::set('database.connections.tenant.host', config('database.connections.mysql.host'));
                Config::set('database.connections.tenant.port', config('database.connections.mysql.port'));
                Config::set('database.connections.tenant.username', config('database.connections.mysql.username'));
                Config::set('database.connections.tenant.password', config('database.connections.mysql.password'));

                // Purgar y reconectar
                DB::purge('tenant');

                // Inicializar tenancy
                tenancy()->initialize($tenant);

                // Verificar si la tabla migrations existe, si no, crearla
                $this->ensureMigrationsTable();

                // Ejecutar migraciones usando la conexión tenant
                if ($this->option('fresh')) {
                    $this->call('migrate:fresh', [
                        '--database' => 'tenant',
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);
                } else {
                    $exitCode = $this->call('migrate', [
                        '--database' => 'tenant',
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
        if (!Schema::connection('tenant')->hasTable('migrations')) {
            Schema::connection('tenant')->create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }
    }
}
