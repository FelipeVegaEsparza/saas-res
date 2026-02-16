<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Plan gratuito para probar el sistema',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'max_products' => 20,
                'max_tables' => 5,
                'max_users' => 2,
                'custom_domain' => false,
                'analytics' => false,
                'active' => true,
                'features' => [
                    'Carta digital con QR',
                    'Gestión de mesas',
                    'Gestión de pedidos',
                    'Hasta 20 productos',
                    'Hasta 5 mesas',
                    'Hasta 2 usuarios',
                ],
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Plan básico para restaurantes pequeños',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'max_products' => 100,
                'max_tables' => 20,
                'max_users' => 5,
                'custom_domain' => false,
                'analytics' => true,
                'active' => true,
                'features' => [
                    'Todo lo del plan Free',
                    'Hasta 100 productos',
                    'Hasta 20 mesas',
                    'Hasta 5 usuarios',
                    'Reportes y analíticas',
                    'Soporte por email',
                ],
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Plan premium para restaurantes profesionales',
                'price' => 79.99,
                'billing_cycle' => 'monthly',
                'max_products' => -1, // Ilimitado
                'max_tables' => -1, // Ilimitado
                'max_users' => -1, // Ilimitado
                'custom_domain' => true,
                'analytics' => true,
                'active' => true,
                'features' => [
                    'Todo lo del plan Basic',
                    'Productos ilimitados',
                    'Mesas ilimitadas',
                    'Usuarios ilimitados',
                    'Dominio personalizado',
                    'Analíticas avanzadas',
                    'Soporte prioritario 24/7',
                    'Integraciones personalizadas',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('✅ Planes creados exitosamente');
    }
}
