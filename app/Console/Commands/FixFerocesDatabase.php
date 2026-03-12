<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixFerocesDatabase extends Command
{
    protected $signature = 'fix:feroces-db';
    protected $description = 'Fix feroces database migrations and add tip column';

    public function handle()
    {
        $this->info('Fixing feroces database...');

        // Switch to tenant database
        config(['database.connections.tenant.database' => 'tenant_feroces']);
        DB::purge('tenant');
        DB::reconnect('tenant');

        // Mark problematic migrations as executed
        $migrations = [
            '2026_02_24_205742_create_preparation_areas_table',
            '2026_02_24_205807_add_preparation_area_id_to_products_table',
            '2026_02_24_212415_add_preparation_status_to_order_items_table',
            '2026_02_24_212601_add_preparation_status_to_delivery_order_items_table',
            '2026_03_08_202530_remove_soft_deletes_from_categories_table',
            '2026_03_09_011354_remove_soft_deletes_from_tables_table'
        ];

        foreach ($migrations as $migration) {
            $exists = DB::connection('tenant')->table('migrations')
                ->where('migration', $migration)
                ->exists();

            if (!$exists) {
                DB::connection('tenant')->table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => 1
                ]);
                $this->info("Marked migration as executed: {$migration}");
            }
        }

        // Check and add tip column
        $hasColumn = Schema::connection('tenant')->hasColumn('payments', 'tip');

        if ($hasColumn) {
            $this->info("✓ Column 'tip' already exists in payments table");
        } else {
            $this->info("Adding 'tip' column to payments table...");
            try {
                Schema::connection('tenant')->table('payments', function ($table) {
                    $table->decimal('tip', 10, 2)->default(0)->after('amount_paid');
                });
                $this->info("✓ Column 'tip' added successfully!");

                // Mark the tip migration as executed
                DB::connection('tenant')->table('migrations')->insert([
                    'migration' => '2026_03_09_020000_add_tip_to_payments_table',
                    'batch' => 1
                ]);

            } catch (\Exception $e) {
                $this->error("Failed to add column: " . $e->getMessage());
                return 1;
            }
        }

        $this->info("✓ Feroces database fixed successfully!");
        return 0;
    }
}
