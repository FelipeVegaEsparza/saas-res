<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class BackupAllTenantsCommand extends Command
{
    protected $signature = 'tenants:backup {--path=storage/backups}';
    protected $description = 'Create backups of all tenant databases';

    public function handle()
    {
        $tenants = Tenant::all();
        $backupPath = $this->option('path');

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $this->info("Creating backups for {$tenants->count()} tenant(s)...");

        foreach ($tenants as $tenant) {
            $dbName = 'tenant_' . $tenant->getTenantKey();
            $backupFile = $backupPath . '/backup_' . $tenant->id . '_' . date('Y-m-d_H-i-s') . '.sql';

            $this->info("Backing up tenant: {$tenant->id}");

            $command = sprintf(
                'mysqldump -h%s -P%s -u%s -p%s %s > %s',
                env('DB_HOST', '127.0.0.1'),
                env('DB_PORT', '3306'),
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                $dbName,
                $backupFile
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                $this->info("✓ Backup created: {$backupFile}");
            } else {
                $this->error("✗ Failed to backup tenant: {$tenant->id}");
            }
        }

        $this->info("Backup process completed.");
        return 0;
    }
}
