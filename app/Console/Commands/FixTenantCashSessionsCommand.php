<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;

class FixTenantCashSessionsCommand extends Command
{
    protected $signature = 'tenants:fix-cash-sessions {tenant?} {--all}';
    protected $description = 'Fix cash sessions table by adding missing payment method columns';

    public function handle()
    {
        $tenantId = $this->argument('tenant');
        $all = $this->option('all');

        if (!$tenantId && !$all) {
            $this->error('Please specify a tenant ID or use --all flag');
            return 1;
        }

        if ($all) {
            $tenants = Tenant::all();
            $this->info("Fixing cash sessions for all {$tenants->count()} tenants...");
        } else {
            $tenant = Tenant::find($tenantId);
            if (!$tenant) {
                $this->error("Tenant '{$tenantId}' not found");
                return 1;
            }
            $tenants = collect([$tenant]);
            $this->info("Fixing cash sessions for tenant: {$tenantId}");
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($tenants as $tenant) {
            try {
                $this->info("Processing tenant: {$tenant->id}");

                // Configurar conexión tenant
                $dbName = 'tenant_' . $tenant->getTenantKey();
                Config::set('database.connections.tenant_fix.database', $dbName);
                Config::set('database.connections.tenant_fix.host', config('database.connections.mysql.host'));
                Config::set('database.connections.tenant_fix.port', config('database.connections.mysql.port'));
                Config::set('database.connections.tenant_fix.username', config('database.connections.mysql.username'));
                Config::set('database.connections.tenant_fix.password', config('database.connections.mysql.password'));
                Config::set('database.connections.tenant_fix.driver', 'mysql');
                Config::set('database.connections.tenant_fix.charset', 'utf8mb4');
                Config::set('database.connections.tenant_fix.collation', 'utf8mb4_unicode_ci');

                DB::purge('tenant_fix');

                // Verificar si la tabla cash_sessions existe
                if (!Schema::connection('tenant_fix')->hasTable('cash_sessions')) {
                    $this->warn("  - cash_sessions table not found, skipping");
                    continue;
                }

                // Verificar si ya tiene los campos
                $hasFields = Schema::connection('tenant_fix')->hasColumns('cash_sessions', [
                    'expected_cash', 'expected_card', 'expected_transfer'
                ]);

                if ($hasFields) {
                    $this->info("  ✓ Already has payment method fields");
                    $successCount++;
                    continue;
                }

                // Agregar las columnas faltantes
                Schema::connection('tenant_fix')->table('cash_sessions', function ($table) {
                    // Montos esperados por método de pago
                    $table->decimal('expected_cash', 10, 2)->default(0)->after('expected_balance');
                    $table->decimal('expected_card', 10, 2)->default(0)->after('expected_cash');
                    $table->decimal('expected_transfer', 10, 2)->default(0)->after('expected_card');

                    // Montos contados por método de pago
                    $table->decimal('counted_cash', 10, 2)->nullable()->after('expected_transfer');
                    $table->decimal('counted_card', 10, 2)->nullable()->after('counted_cash');
                    $table->decimal('counted_transfer', 10, 2)->nullable()->after('counted_card');

                    // Diferencias por método de pago
                    $table->decimal('difference_cash', 10, 2)->nullable()->after('counted_transfer');
                    $table->decimal('difference_card', 10, 2)->nullable()->after('difference_cash');
                    $table->decimal('difference_transfer', 10, 2)->nullable()->after('difference_card');

                    // Propinas por método de pago
                    $table->decimal('tips_cash', 10, 2)->default(0)->after('difference_transfer');
                    $table->decimal('tips_card', 10, 2)->default(0)->after('tips_cash');
                    $table->decimal('tips_transfer', 10, 2)->default(0)->after('tips_card');
                });

                $this->info("  ✓ Payment method fields added successfully");
                $successCount++;

            } catch (\Exception $e) {
                $this->error("  ✗ Error processing tenant {$tenant->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->newLine();
        $this->info("Process completed:");
        $this->info("  Success: {$successCount}");
        $this->info("  Errors: {$errorCount}");

        return $errorCount > 0 ? 1 : 0;
    }
}
