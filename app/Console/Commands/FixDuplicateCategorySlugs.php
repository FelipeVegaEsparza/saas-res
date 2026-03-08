<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDuplicateCategorySlugs extends Command
{
    protected $signature = 'categories:fix-slugs';
    protected $description = 'Fix duplicate category slugs by adding numbers to duplicates';

    public function handle()
    {
        $this->info('Fixing duplicate category slugs...');

        // Obtener todos los tenants
        $tenants = DB::table('tenants')->get();

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->id}");

            // Cambiar a la conexión del tenant
            config(['database.connections.tenant.database' => 'tenant_' . $tenant->id]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            // Obtener categorías con slugs duplicados (incluyendo soft deleted)
            $duplicateSlugs = DB::connection('tenant')
                ->table('categories')
                ->select('slug', DB::raw('COUNT(*) as count'))
                ->groupBy('slug')
                ->having('count', '>', 1)
                ->get();

            foreach ($duplicateSlugs as $duplicate) {
                $this->warn("Found duplicate slug: {$duplicate->slug} ({$duplicate->count} times)");

                // Obtener todas las categorías con este slug
                $categories = DB::connection('tenant')
                    ->table('categories')
                    ->where('slug', $duplicate->slug)
                    ->orderBy('id')
                    ->get();

                // Mantener la primera, renombrar las demás
                $counter = 1;
                foreach ($categories as $index => $category) {
                    if ($index > 0) {
                        $newSlug = $duplicate->slug . '-' . $counter;

                        // Asegurarse de que el nuevo slug no existe
                        while (DB::connection('tenant')->table('categories')->where('slug', $newSlug)->exists()) {
                            $counter++;
                            $newSlug = $duplicate->slug . '-' . $counter;
                        }

                        DB::connection('tenant')
                            ->table('categories')
                            ->where('id', $category->id)
                            ->update(['slug' => $newSlug]);

                        $this->info("  Renamed category ID {$category->id} to slug: {$newSlug}");
                        $counter++;
                    }
                }
            }
        }

        $this->info('Done!');
        return 0;
    }
}
