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

        // Configurar la conexión temporal
        config([
            'database.connections.tenant_temp' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $dbName,
                'username' => env('DB_USERNAME', 'sail'),
                'password' => env('DB_PASSWORD', 'password'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]
        ]);

        // Cambiar la conexión por defecto temporalmente
        DB::purge('tenant_temp');
        DB::setDefaultConnection('tenant_temp');

        try {
            $this->info("Ejecutando seeder...");

            $seeder = new TenantDemoSeeder();
            $seeder->run();

            $this->info("✓ Seeder completado exitosamente");

            // Restaurar conexión por defecto
            DB::setDefaultConnection('mysql');

            return 0;
        } catch (\Exception $e) {
            $this->error("Error ejecutando seeder: " . $e->getMessage());
            DB::setDefaultConnection('mysql');
            return 1;
        }
    }
}
