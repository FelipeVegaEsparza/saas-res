<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class MigrateTenantDirectCommand extends Command
{
    protected $signature = 'tenant:migrate-direct {tenant}';
    protected $description = 'Migrar directamente a la base de datos del tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant');

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant '{$tenantId}' no encontrado");
            return 1;
        }

        $dbName = 'tenant_' . $tenantId;

        $this->info("Migrando tenant: {$tenantId}");
        $this->info("Base de datos: {$dbName}");

        // Crear la base de datos si no existe
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
            $this->info("Base de datos verificada/creada");
        } catch (\Exception $e) {
            $this->error("Error creando base de datos: " . $e->getMessage());
            return 1;
        }

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

        // Purgar la conexión para asegurar que use la nueva configuración
        \DB::purge('tenant_temp');

        // Ejecutar migraciones
        try {
            $this->info("Ejecutando migraciones...");
            Artisan::call('migrate', [
                '--database' => 'tenant_temp',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            $this->info(Artisan::output());
            $this->info("✓ Migraciones completadas exitosamente");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error ejecutando migraciones: " . $e->getMessage());
            return 1;
        }
    }
}
