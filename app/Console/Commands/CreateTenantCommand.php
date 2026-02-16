<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Restaurant;
use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class CreateTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create
                            {name : Nombre del restaurante}
                            {slug? : Slug para el subdominio}
                            {--plan=free : Plan del restaurante}
                            {--email= : Email del administrador}
                            {--password= : Password del administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un nuevo tenant (restaurante) con su base de datos y configuración';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $slug = $this->argument('slug') ?? Str::slug($name);
        $plan = $this->option('plan');
        $email = $this->option('email') ?? $this->ask('Email del administrador');
        $password = $this->option('password') ?? $this->secret('Password del administrador');

        $this->info("🚀 Creando tenant: {$name}");
        $this->info("📍 Slug: {$slug}");
        $this->info("💼 Plan: {$plan}");

        try {
            // Verificar si el slug ya existe
            if (Tenant::where('id', $slug)->exists()) {
                $this->error("❌ El slug '{$slug}' ya está en uso.");
                return 1;
            }

            // Crear tenant
            $this->info("📦 Creando tenant...");
            $tenant = Tenant::create([
                'id' => $slug,
                'restaurant_name' => $name,
                'plan' => $plan,
            ]);

            // Crear dominio
            $tenant->domains()->create([
                'domain' => $slug . '.' . config('tenancy.central_domains')[2],
            ]);

            $this->info("✅ Tenant creado con ID: {$tenant->id}");

            // Crear registro en tabla restaurants
            $restaurant = Restaurant::create([
                'tenant_id' => $tenant->id,
                'name' => $name,
                'slug' => $slug,
                'domain' => $slug . '.' . config('tenancy.central_domains')[2],
                'db_name' => config('tenancy.database.prefix') . $tenant->id,
                'active' => true,
                'plan' => $plan,
                'trial_ends_at' => now()->addDays(14),
            ]);

            // Ejecutar migraciones tenant usando el comando directo
            $this->info("🗄️  Ejecutando migraciones tenant...");
            $this->call('tenant:migrate-direct', [
                'tenant' => $tenant->id,
            ]);
            $this->info("✅ Migraciones ejecutadas");

            // Crear usuario administrador
            $this->info("👤 Creando usuario administrador...");
            $tenant->run(function () use ($name, $email, $password) {
                \App\Models\User::create([
                    'name' => $name . ' Admin',
                    'email' => $email,
                    'password' => bcrypt($password),
                    'role' => 'owner',
                    'email_verified_at' => now(),
                ]);
            });
            $this->info("✅ Usuario administrador creado");

            // Mostrar información
            $restaurant = $tenant->restaurant();
            $this->newLine();
            $this->info("🎉 ¡Tenant creado exitosamente!");
            $this->newLine();
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Tenant ID', $tenant->id],
                    ['Nombre', $name],
                    ['Slug', $slug],
                    ['Dominio', $restaurant->domain],
                    ['URL', $restaurant->url],
                    ['URL Menú', $restaurant->menu_url],
                    ['Plan', $plan],
                    ['Base de datos', $restaurant->db_name],
                    ['Email Admin', $email],
                    ['Trial hasta', $restaurant->trial_ends_at->format('Y-m-d')],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error al crear tenant: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
