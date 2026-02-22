<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Database\Seeders\TenantDemoSeeder;

class SeedTenantDirectCommand extends Command
{
    protected $signature = 'tenant:seed-direct {tenant}';
    protected $description = 'Seed directamente la base de datos del tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant');

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant '{$tenantId}' no encontrado");
            return 1;
        }

        $dbName = 'tenant_' . $tenantId;

        $this->info("Seeding tenant: {$tenantId}");
        $this->info("Base de datos: {$dbName}");

        // Guardar configuración original de la conexión tenant
        $originalConfig = config('database.connections.tenant');

        // Actualizar la conexión 'tenant' para apuntar a la base de datos correcta
        config([
            'database.connections.tenant.database' => $dbName,
        ]);

        // Purgar la conexión para forzar reconexión
        DB::purge('tenant');

        // También configurar la conexión por defecto para el modelo User
        config([
            'database.connections.mysql.database' => $dbName,
        ]);
        DB::purge('mysql');

        try {
            $this->info("Ejecutando seeder...");

            $seeder = new TenantDemoSeeder();
            $seeder->run();

            $this->info("✓ Seeder completado exitosamente");

            // Restaurar configuración original
            config(['database.connections.tenant' => $originalConfig]);
            DB::purge('tenant');

            // Restaurar conexión mysql
            config([
                'database.connections.mysql.database' => env('DB_DATABASE', 'laravel'),
            ]);
            DB::purge('mysql');

            return 0;
        } catch (\Exception $e) {
            $this->error("Error ejecutando seeder: " . $e->getMessage());

            // Restaurar configuración original en caso de error
            config(['database.connections.tenant' => $originalConfig]);
            DB::purge('tenant');
            config([
                'database.connections.mysql.database' => env('DB_DATABASE', 'laravel'),
            ]);
            DB::purge('mysql');

            return 1;
        }
    }
}
