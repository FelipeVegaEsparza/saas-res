<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class SafeMigrateProductionCommand extends Command
{
    protected $signature = 'tenants:safe-migrate-production {--backup-path=storage/backups} {--dry-run}';
    protected $description = 'Safely migrate all tenants in production with backups';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $backupPath = $this->option('backup-path');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $this->info('=== SAFE PRODUCTION MIGRATION PROCESS ===');
        $this->newLine();

        // Paso 1: Verificar estado actual
        $this->info('Step 1: Checking current migration status...');
        $this->call('tenants:check-migrations');
        $this->newLine();

        if (!$this->confirm('Do you want to continue with the migration process?')) {
            $this->info('Migration cancelled by user.');
            return 0;
        }

        // Paso 2: Crear backups
        if (!$isDryRun) {
            $this->info('Step 2: Creating backups...');
            $this->call('tenants:backup', ['--path' => $backupPath]);
            $this->newLine();
        } else {
            $this->info('Step 2: [DRY RUN] Would create backups...');
        }

        // Paso 3: Migrar tenants uno por uno
        $tenants = Tenant::all();
        $this->info("Step 3: Migrating {$tenants->count()} tenant(s)...");

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant: {$tenant->id}");

            if (!$isDryRun) {
                try {
                    $exitCode = $this->call('tenants:migrate', ['tenant' => $tenant->id]);

                    if ($exitCode === 0) {
                        $successCount++;
                        $this->info("✓ {$tenant->id} migrated successfully");
                    } else {
                        $errorCount++;
                        $errors[] = $tenant->id;
                        $this->error("✗ {$tenant->id} migration failed");
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = $tenant->id . ': ' . $e->getMessage();
                    $this->error("✗ {$tenant->id} migration error: " . $e->getMessage());
                }
            } else {
                $this->info("[DRY RUN] Would migrate tenant: {$tenant->id}");
            }

            $this->newLine();
        }

        // Resumen final
        $this->info('=== MIGRATION SUMMARY ===');
        if (!$isDryRun) {
            $this->info("Successful: {$successCount}");
            $this->info("Errors: {$errorCount}");

            if ($errorCount > 0) {
                $this->error('Failed tenants:');
                foreach ($errors as $error) {
                    $this->error("  - {$error}");
                }
            }
        } else {
            $this->info("Would process {$tenants->count()} tenants");
        }

        // Verificar estado final
        if (!$isDryRun && $errorCount === 0) {
            $this->info('Step 4: Verifying final state...');
            $this->call('tenants:check-migrations');
        }

        return $errorCount > 0 ? 1 : 0;
    }
}
