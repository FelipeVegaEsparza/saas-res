<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;

class MigrateTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate {tenant} {--fresh : Drop all tables and re-run all migrations} {--seed : Seed the database after migrating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for a specific tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant');

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant '{$tenantId}' not found.");
            return 1;
        }

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
            }

            // Seed si se especifica
            if ($this->option('seed')) {
                $this->call('db:seed', [
                    '--database' => 'tenant',
                    '--class' => 'TenantSeeder',
                    '--force' => true,
                ]);
            }

            $this->info("✓ Tenant {$tenant->id} migrated successfully.");

            return 0;

        } catch (\Exception $e) {
            $this->error("✗ Error migrating tenant {$tenant->id}: {$e->getMessage()}");
            return 1;
        } finally {
            // Limpiar tenancy
            tenancy()->end();
        }
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
