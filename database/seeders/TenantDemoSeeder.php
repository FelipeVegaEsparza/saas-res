<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\Table;
use App\Models\User;

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
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Categorías
        $entradas = Category::create([
            'name' => 'Entradas',
            'slug' => 'entradas',
            'description' => 'Deliciosas entradas para comenzar',
            'order' => 1,
            'active' => true,
        ]);

        $principales = Category::create([
            'name' => 'Platos Principales',
            'slug' => 'platos-principales',
            'description' => 'Nuestros mejores platos',
            'order' => 2,
            'active' => true,
        ]);

        $postres = Category::create([
            'name' => 'Postres',
            'slug' => 'postres',
            'description' => 'Dulces tentaciones',
            'order' => 3,
            'active' => true,
        ]);

        $bebidas = Category::create([
            'name' => 'Bebidas',
            'slug' => 'bebidas',
            'description' => 'Bebidas frías y calientes',
            'order' => 4,
            'active' => true,
        ]);

        // Productos - Entradas
        Product::create([
            'category_id' => $entradas->id,
            'name' => 'Ensalada César',
            'slug' => 'ensalada-cesar',
            'description' => 'Lechuga romana, crutones, parmesano y aderezo césar',
            'price' => 8.99,
            'available' => true,
            'featured' => true,
            'preparation_time' => 10,
            'tags' => ['vegetariano'],
        ]);

        Product::create([
            'category_id' => $entradas->id,
            'name' => 'Nachos con Queso',
            'slug' => 'nachos-con-queso',
            'description' => 'Nachos crujientes con queso cheddar fundido, jalapeños y guacamole',
            'price' => 9.99,
            'available' => true,
            'preparation_time' => 8,
            'tags' => ['vegetariano', 'picante'],
        ]);

        // Productos - Principales
        Product::create([
            'category_id' => $principales->id,
            'name' => 'Hamburguesa Clásica',
            'slug' => 'hamburguesa-clasica',
            'description' => 'Carne de res 200g, lechuga, tomate, cebolla, queso y papas fritas',
            'price' => 14.99,
            'available' => true,
            'featured' => true,
            'preparation_time' => 20,
            'allergens' => ['gluten', 'lactosa'],
        ]);

        Product::create([
            'category_id' => $principales->id,
            'name' => 'Pizza Margherita',
            'slug' => 'pizza-margherita',
            'description' => 'Salsa de tomate, mozzarella fresca y albahaca',
            'price' => 12.99,
            'available' => true,
            'featured' => true,
            'preparation_time' => 15,
            'tags' => ['vegetariano'],
            'allergens' => ['gluten', 'lactosa'],
        ]);

        Product::create([
            'category_id' => $principales->id,
            'name' => 'Pasta Carbonara',
            'slug' => 'pasta-carbonara',
            'description' => 'Pasta con salsa carbonara, panceta y parmesano',
            'price' => 13.99,
            'available' => true,
            'preparation_time' => 18,
            'allergens' => ['gluten', 'lactosa', 'huevo'],
        ]);

        Product::create([
            'category_id' => $principales->id,
            'name' => 'Pollo a la Parrilla',
            'slug' => 'pollo-a-la-parrilla',
            'description' => 'Pechuga de pollo marinada con vegetales asados',
            'price' => 15.99,
            'available' => true,
            'preparation_time' => 25,
        ]);

        // Productos - Postres
        Product::create([
            'category_id' => $postres->id,
            'name' => 'Tiramisú',
            'slug' => 'tiramisu',
            'description' => 'Clásico postre italiano con café y mascarpone',
            'price' => 6.99,
            'available' => true,
            'featured' => true,
            'preparation_time' => 5,
            'allergens' => ['gluten', 'lactosa', 'huevo'],
        ]);

        Product::create([
            'category_id' => $postres->id,
            'name' => 'Brownie con Helado',
            'slug' => 'brownie-con-helado',
            'description' => 'Brownie de chocolate caliente con helado de vainilla',
            'price' => 7.99,
            'available' => true,
            'preparation_time' => 8,
            'allergens' => ['gluten', 'lactosa', 'huevo', 'nueces'],
        ]);

        // Productos - Bebidas
        Product::create([
            'category_id' => $bebidas->id,
            'name' => 'Coca Cola',
            'slug' => 'coca-cola',
            'description' => 'Refresco 500ml',
            'price' => 2.50,
            'available' => true,
            'preparation_time' => 1,
        ]);

        Product::create([
            'category_id' => $bebidas->id,
            'name' => 'Agua Mineral',
            'slug' => 'agua-mineral',
            'description' => 'Agua mineral 500ml',
            'price' => 2.00,
            'available' => true,
            'preparation_time' => 1,
        ]);

        Product::create([
            'category_id' => $bebidas->id,
            'name' => 'Café Americano',
            'slug' => 'cafe-americano',
            'description' => 'Café americano recién hecho',
            'price' => 3.50,
            'available' => true,
            'preparation_time' => 3,
        ]);

        Product::create([
            'category_id' => $bebidas->id,
            'name' => 'Jugo Natural',
            'slug' => 'jugo-natural',
            'description' => 'Jugo natural de naranja o manzana',
            'price' => 4.50,
            'available' => true,
            'preparation_time' => 5,
        ]);

        // Mesas
        for ($i = 1; $i <= 15; $i++) {
            Table::create([
                'number' => "Mesa $i",
                'capacity' => $i <= 5 ? 2 : ($i <= 10 ? 4 : 6),
                'status' => 'available',
                'location' => $i <= 8 ? 'Interior' : 'Terraza',
                'active' => true,
            ]);
        }

        if ($this->command) {
            $this->command->info('✅ Datos de demostración creados exitosamente');
            $this->command->info('📊 Categorías: 4');
            $this->command->info('🍽️  Productos: 12');
            $this->command->info('🪑 Mesas: 15');
        }
    }
}
