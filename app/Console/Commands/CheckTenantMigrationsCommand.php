<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;

class CheckTenantMigrationsCommand extends Command
{
    protected $signature = 'tenants:check-migrations';
    protected $description = 'Check migration status for all tenants';

    public function handle()
    {
        $tenants = Tenant::all();

        $this->info("Checking migration status for {$tenants->count()} tenant(s)...");
        $this->newLine();

        $results = [];

        foreach ($tenants as $tenant) {
            try {
                // Configurar conexión tenant
                $dbName = 'tenant_' . $tenant->getTenantKey();
                Config::set('database.connections.tenant_check.database', $dbName);
                Config::set('database.connections.tenant_check.host', config('database.connections.mysql.host'));
                Config::set('database.connections.tenant_check.port', config('database.connections.mysql.port'));
                Config::set('database.connections.tenant_check.username', config('database.connections.mysql.username'));
                Config::set('database.connections.tenant_check.password', config('database.connections.mysql.password'));
                Config::set('database.connections.tenant_check.driver', 'mysql');
                Config::set('database.connections.tenant_check.charset', 'utf8mb4');
                Config::set('database.connections.tenant_check.collation', 'utf8mb4_unicode_ci');

                DB::purge('tenant_check');

                // Verificar si existe la tabla migrations
                if (!Schema::connection('tenant_check')->hasTable('migrations')) {
                    $results[] = [
                        'tenant' => $tenant->id,
                        'status' => 'NO_MIGRATIONS_TABLE',
                        'last_migration' => 'N/A',
                        'has_cash_fields' => 'N/A'
                    ];
                    continue;
                }

                // Obtener última migración
                $lastMigration = DB::connection('tenant_check')
                    ->table('migrations')
                    ->orderBy('batch', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                // Verificar si tiene los campos de cash session
                $hasCashFields = Schema::connection('tenant_check')->hasColumns('cash_sessions', [
                    'expected_cash', 'expected_card', 'expected_transfer'
                ]);

                $results[] = [
                    'tenant' => $tenant->id,
                    'status' => 'OK',
                    'last_migration' => $lastMigration ? $lastMigration->migration : 'NONE',
                    'has_cash_fields' => $hasCashFields ? 'YES' : 'NO'
                ];

            } catch (\Exception $e) {
                $results[] = [
                    'tenant' => $tenant->id,
                    'status' => 'ERROR',
                    'last_migration' => $e->getMessage(),
                    'has_cash_fields' => 'ERROR'
                ];
            }
        }

        // Mostrar resultados en tabla
        $this->table(
            ['Tenant', 'Status', 'Last Migration', 'Has Cash Fields'],
            array_map(function($result) {
                return [
                    $result['tenant'],
                    $result['status'],
                    substr($result['last_migration'], 0, 50) . (strlen($result['last_migration']) > 50 ? '...' : ''),
                    $result['has_cash_fields']
                ];
            }, $results)
        );

        return 0;
    }
}
