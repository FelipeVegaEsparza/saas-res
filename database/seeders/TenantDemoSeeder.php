<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\Table;
use App\Models\Tenant\User;

class TenantDemoSeeder extends Seeder
{
    /**
     * Seed de datos de demostración para un tenant
     */
    public function run(): void
    {
        // Crear usuario administrador si no existe
        if (User::count() == 0) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@demo.com',
                'password' => bcrypt('demo123'),
                'role' => 'owner',
                'active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Categorías - usar firstOrCreate para evitar duplicados
        $entradas = Category::firstOrCreate(
            ['slug' => 'entradas'],
            [
                'name' => 'Entradas',
                'description' => 'Deliciosas entradas para comenzar',
                'order' => 1,
                'active' => true,
            ]
        );

        $principales = Category::firstOrCreate(
            ['slug' => 'platos-principales'],
            [
                'name' => 'Platos Principales',
                'description' => 'Nuestros mejores platos',
                'order' => 2,
                'active' => true,
            ]
        );

        $postres = Category::firstOrCreate(
            ['slug' => 'postres'],
            [
                'name' => 'Postres',
                'description' => 'Dulces tentaciones',
                'order' => 3,
                'active' => true,
            ]
        );

        $bebidas = Category::firstOrCreate(
            ['slug' => 'bebidas'],
            [
                'name' => 'Bebidas',
                'description' => 'Bebidas frías y calientes',
                'order' => 4,
                'active' => true,
            ]
        );

        // Productos - Entradas
        Product::firstOrCreate(
            ['slug' => 'ensalada-cesar'],
            [
                'category_id' => $entradas->id,
                'name' => 'Ensalada César',
                'description' => 'Lechuga romana, crutones, parmesano y aderezo césar',
                'price' => 8.99,
                'available' => true,
                'featured' => true,
                'preparation_time' => 10,
                'tags' => ['vegetariano'],
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'nachos-con-queso'],
            [
                'category_id' => $entradas->id,
                'name' => 'Nachos con Queso',
                'description' => 'Nachos crujientes con queso cheddar fundido, jalapeños y guacamole',
                'price' => 9.99,
                'available' => true,
                'preparation_time' => 8,
                'tags' => ['vegetariano', 'picante'],
            ]
        );

        // Productos - Principales
        Product::firstOrCreate(
            ['slug' => 'hamburguesa-clasica'],
            [
                'category_id' => $principales->id,
                'name' => 'Hamburguesa Clásica',
                'description' => 'Carne de res 200g, lechuga, tomate, cebolla, queso y papas fritas',
                'price' => 14.99,
                'available' => true,
                'featured' => true,
                'preparation_time' => 20,
                'allergens' => ['gluten', 'lactosa'],
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'pizza-margherita'],
            [
                'category_id' => $principales->id,
                'name' => 'Pizza Margherita',
                'description' => 'Salsa de tomate, mozzarella fresca y albahaca',
                'price' => 12.99,
                'available' => true,
                'featured' => true,
                'preparation_time' => 15,
                'tags' => ['vegetariano'],
                'allergens' => ['gluten', 'lactosa'],
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'pasta-carbonara'],
            [
                'category_id' => $principales->id,
                'name' => 'Pasta Carbonara',
                'description' => 'Pasta con salsa carbonara, panceta y parmesano',
                'price' => 13.99,
                'available' => true,
                'preparation_time' => 18,
                'allergens' => ['gluten', 'lactosa', 'huevo'],
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'pollo-a-la-parrilla'],
            [
                'category_id' => $principales->id,
                'name' => 'Pollo a la Parrilla',
                'description' => 'Pechuga de pollo marinada con vegetales asados',
                'price' => 15.99,
                'available' => true,
                'preparation_time' => 25,
            ]
        );

        // Productos - Postres
        Product::firstOrCreate(
            ['slug' => 'tiramisu'],
            [
                'category_id' => $postres->id,
                'name' => 'Tiramisú',
                'description' => 'Clásico postre italiano con café y mascarpone',
                'price' => 6.99,
                'available' => true,
                'featured' => true,
                'preparation_time' => 5,
                'allergens' => ['gluten', 'lactosa', 'huevo'],
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'brownie-con-helado'],
            [
                'category_id' => $postres->id,
                'name' => 'Brownie con Helado',
                'description' => 'Brownie de chocolate caliente con helado de vainilla',
                'price' => 7.99,
                'available' => true,
                'preparation_time' => 8,
                'allergens' => ['gluten', 'lactosa', 'huevo', 'nueces'],
            ]
        );

        // Productos - Bebidas
        Product::firstOrCreate(
            ['slug' => 'coca-cola'],
            [
                'category_id' => $bebidas->id,
                'name' => 'Coca Cola',
                'description' => 'Refresco 500ml',
                'price' => 2.50,
                'available' => true,
                'preparation_time' => 1,
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'agua-mineral'],
            [
                'category_id' => $bebidas->id,
                'name' => 'Agua Mineral',
                'description' => 'Agua mineral 500ml',
                'price' => 2.00,
                'available' => true,
                'preparation_time' => 1,
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'cafe-americano'],
            [
                'category_id' => $bebidas->id,
                'name' => 'Café Americano',
                'description' => 'Café americano recién hecho',
                'price' => 3.50,
                'available' => true,
                'preparation_time' => 3,
            ]
        );

        Product::firstOrCreate(
            ['slug' => 'jugo-natural'],
            [
                'category_id' => $bebidas->id,
                'name' => 'Jugo Natural',
                'description' => 'Jugo natural de naranja o manzana',
                'price' => 4.50,
                'available' => true,
                'preparation_time' => 5,
            ]
        );

        // Mesas - solo crear si no existen
        if (Table::count() == 0) {
            for ($i = 1; $i <= 15; $i++) {
                Table::create([
                    'number' => "Mesa $i",
                    'capacity' => $i <= 5 ? 2 : ($i <= 10 ? 4 : 6),
                    'status' => 'available',
                    'location' => $i <= 8 ? 'Interior' : 'Terraza',
                    'active' => true,
                ]);
            }
        }

        if ($this->command) {
            $this->command->info('✅ Datos de demostración creados exitosamente');
            $this->command->info('📊 Categorías: 4');
            $this->command->info('🍽️  Productos: 12');
            $this->command->info('🪑 Mesas: 15');
        }
    }
}
