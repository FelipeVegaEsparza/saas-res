<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class TenantStatusCommand extends Command
{
    protected $signature = 'tenant:status {tenant}';
    protected $description = 'Verificar el estado de un tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant');

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant '{$tenantId}' no encontrado");
            return 1;
        }

        $dbName = 'tenant_' . $tenantId;

        $this->info("🔍 Estado del Tenant: {$tenantId}");
        $this->newLine();

        // Verificar si la base de datos existe
        try {
            $databases = DB::select("SHOW DATABASES LIKE '{$dbName}'");

            if (empty($databases)) {
                $this->error("❌ Base de datos '{$dbName}' NO existe");
                return 1;
            }

            $this->info("✅ Base de datos '{$dbName}' existe");
            $this->newLine();

            // Configurar conexión temporal
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

            DB::purge('tenant_temp');

            // Obtener estadísticas
            $users = DB::connection('tenant_temp')->table('users')->count();
            $categories = DB::connection('tenant_temp')->table('categories')->count();
            $products = DB::connection('tenant_temp')->table('products')->count();
            $tables = DB::connection('tenant_temp')->table('tables')->count();

            $this->table(
                ['Tabla', 'Registros'],
                [
                    ['users', $users],
                    ['categories', $categories],
                    ['products', $products],
                    ['tables', $tables],
                ]
            );

            $this->newLine();

            // Obtener información del dominio
            $domain = $tenant->domains()->first();

            if ($domain) {
                $this->info("🌐 Dominio: {$domain->domain}");
                $this->info("🔗 URL: http://{$domain->domain}:8000");
                $this->info("📋 Menú: http://{$domain->domain}:8000/menu");
                $this->info("🔐 Login: http://{$domain->domain}:8000/login");
            }

            $this->newLine();

            // Verificar usuario admin
            $admin = DB::connection('tenant_temp')->table('users')
                ->where('role', 'owner')
                ->orWhere('role', 'admin')
                ->first();

            if ($admin) {
                $this->info("👤 Usuario Admin:");
                $this->info("   Email: {$admin->email}");
                $this->info("   Nombre: {$admin->name}");
                $this->info("   Role: {$admin->role}");
            } else {
                $this->warn("⚠️  No se encontró usuario administrador");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
