<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class EmergencyFixCashSessionsCommand extends Command
{
    protected $signature = 'tenants:emergency-fix-cash {tenant}';
    protected $description = 'Emergency fix for specific tenant cash sessions';

    public function handle()
    {
        $tenantId = $this->argument('tenant');
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant '{$tenantId}' not found");
            return 1;
        }

        $this->info("Emergency fix for tenant: {$tenantId}");

        try {
            // Configurar conexión tenant
            $dbName = 'tenant_' . $tenant->getTenantKey();
            Config::set('database.connections.emergency.database', $dbName);
            Config::set('database.connections.emergency.host', config('database.connections.mysql.host'));
            Config::set('database.connections.emergency.port', config('database.connections.mysql.port'));
            Config::set('database.connections.emergency.username', config('database.connections.mysql.username'));
            Config::set('database.connections.emergency.password', config('database.connections.mysql.password'));
            Config::set('database.connections.emergency.driver', 'mysql');
            Config::set('database.connections.emergency.charset', 'utf8mb4');
            Config::set('database.connections.emergency.collation', 'utf8mb4_unicode_ci');

            DB::purge('emergency');

            // Ejecutar SQL directo para agregar columnas
            $sql = "
                ALTER TABLE cash_sessions
                ADD COLUMN IF NOT EXISTS expected_cash DECIMAL(10,2) DEFAULT 0 AFTER expected_balance,
                ADD COLUMN IF NOT EXISTS expected_card DECIMAL(10,2) DEFAULT 0 AFTER expected_cash,
                ADD COLUMN IF NOT EXISTS expected_transfer DECIMAL(10,2) DEFAULT 0 AFTER expected_card,
                ADD COLUMN IF NOT EXISTS counted_cash DECIMAL(10,2) NULL AFTER expected_transfer,
                ADD COLUMN IF NOT EXISTS counted_card DECIMAL(10,2) NULL AFTER counted_cash,
                ADD COLUMN IF NOT EXISTS counted_transfer DECIMAL(10,2) NULL AFTER counted_card,
                ADD COLUMN IF NOT EXISTS difference_cash DECIMAL(10,2) NULL AFTER counted_transfer,
                ADD COLUMN IF NOT EXISTS difference_card DECIMAL(10,2) NULL AFTER difference_cash,
                ADD COLUMN IF NOT EXISTS difference_transfer DECIMAL(10,2) NULL AFTER difference_card,
                ADD COLUMN IF NOT EXISTS tips_cash DECIMAL(10,2) DEFAULT 0 AFTER difference_transfer,
                ADD COLUMN IF NOT EXISTS tips_card DECIMAL(10,2) DEFAULT 0 AFTER tips_cash,
                ADD COLUMN IF NOT EXISTS tips_transfer DECIMAL(10,2) DEFAULT 0 AFTER tips_card
            ";

            DB::connection('emergency')->statement($sql);

            $this->info("✓ Emergency fix completed successfully");
            return 0;

        } catch (\Exception $e) {
            $this->error("✗ Emergency fix failed: " . $e->getMessage());
            return 1;
        }
    }
}
