# 🧪 GUÍA DE PRUEBAS - MULTI-TENANCY

## 🚀 Prueba Rápida del Sistema

### 1. Crear tu primer restaurante

```bash
php artisan tenant:create "La Pizzería Italiana" pizzeria --email=admin@pizzeria.com --password=secret123
```

**Salida esperada:**
```
🚀 Creando tenant: La Pizzería Italiana
📍 Slug: pizzeria
💼 Plan: free
📦 Creando tenant...
✅ Tenant creado con ID: pizzeria
🗄️  Creando base de datos...
✅ Base de datos creada
📋 Ejecutando migraciones...
✅ Migraciones ejecutadas
👤 Creando usuario administrador...
✅ Usuario administrador creado

🎉 ¡Tenant creado exitosamente!

+----------------+----------------------------------+
| Campo          | Valor                            |
+----------------+----------------------------------+
| Tenant ID      | pizzeria                         |
| Nombre         | La Pizzería Italiana             |
| Slug           | pizzeria                         |
| Dominio        | pizzeria.localhost               |
| URL            | https://pizzeria.localhost       |
| URL Menú       | https://pizzeria.localhost/menu  |
| Plan           | free                             |
| Base de datos  | tenant_pizzeria                  |
| Email Admin    | admin@pizzeria.com               |
| Trial hasta    | 2026-02-28                       |
+----------------+----------------------------------+
```

### 2. Crear más restaurantes de prueba

```bash
# Restaurante 2
php artisan tenant:create "Sushi Bar Tokyo" sushi --plan=basic --email=admin@sushi.com --password=secret123

# Restaurante 3
php artisan tenant:create "Burger King" burger --plan=premium --email=admin@burger.com --password=secret123
```

### 3. Verificar en la base de datos

```bash
# Conectar a MySQL
mysql -u sail -p

# Ver base de datos landlord
USE laravel;

# Ver restaurantes creados
SELECT id, name, slug, domain, plan, active FROM restaurants;

# Ver tenants
SELECT id, restaurant_name, plan FROM tenants;

# Ver dominios
SELECT id, domain, tenant_id FROM domains;

# Ver bases de datos de tenants
SHOW DATABASES LIKE 'tenant_%';
```

### 4. Probar aislamiento de datos

```php
// routes/web.php - Agregar ruta de prueba
Route::get('/test-tenant', function () {
    // Esto mostrará información del tenant actual
    $tenant = tenant();
    
    if (!$tenant) {
        return 'No estás en un tenant (dominio central)';
    }
    
    $restaurant = $tenant->restaurant();
    
    return [
        'tenant_id' => $tenant->id,
        'restaurant_name' => $restaurant->name,
        'domain' => $restaurant->domain,
        'plan' => $restaurant->plan,
        'database' => config('database.connections.tenant.database'),
        'users_count' => \App\Models\User::count(),
    ];
});
```

Acceder a:
- `http://pizzeria.localhost:8000/test-tenant`
- `http://sushi.localhost:8000/test-tenant`
- `http://burger.localhost:8000/test-tenant`

Cada uno mostrará información diferente.

### 5. Agregar datos de prueba a un tenant

```bash
# Crear seeder para tenant
php artisan make:seeder TenantDemoSeeder
```

```php
// database/seeders/TenantDemoSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\Table;

class TenantDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Categorías
        $pizzas = Category::create([
            'name' => 'Pizzas',
            'slug' => 'pizzas',
            'description' => 'Nuestras deliciosas pizzas artesanales',
            'order' => 1,
            'active' => true,
        ]);

        $bebidas = Category::create([
            'name' => 'Bebidas',
            'slug' => 'bebidas',
            'description' => 'Bebidas frías y calientes',
            'order' => 2,
            'active' => true,
        ]);

        // Productos
        Product::create([
            'category_id' => $pizzas->id,
            'name' => 'Pizza Margherita',
            'slug' => 'pizza-margherita',
            'description' => 'Tomate, mozzarella y albahaca fresca',
            'price' => 12.99,
            'available' => true,
            'featured' => true,
            'preparation_time' => 15,
            'tags' => ['vegetariana'],
        ]);

        Product::create([
            'category_id' => $pizzas->id,
            'name' => 'Pizza Pepperoni',
            'slug' => 'pizza-pepperoni',
            'description' => 'Pepperoni, mozzarella y salsa de tomate',
            'price' => 14.99,
            'available' => true,
            'featured' => true,
            'preparation_time' => 15,
        ]);

        Product::create([
            'category_id' => $bebidas->id,
            'name' => 'Coca Cola',
            'slug' => 'coca-cola',
            'description' => 'Refresco 500ml',
            'price' => 2.50,
            'available' => true,
            'preparation_time' => 1,
        ]);

        // Mesas
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'number' => "Mesa $i",
                'capacity' => rand(2, 6),
                'status' => 'available',
                'location' => $i <= 5 ? 'Interior' : 'Terraza',
                'active' => true,
            ]);
        }

        $this->command->info('✅ Datos de prueba creados');
    }
}
```

```bash
# Ejecutar seeder en un tenant específico
php artisan tenants:run pizzeria --command="db:seed --class=TenantDemoSeeder"
```

### 6. Probar consultas en diferentes tenants

```php
// Crear ruta de prueba para menú
Route::get('/menu-test', function () {
    $categories = \App\Models\Tenant\Category::with('products')->get();
    
    return view('menu-test', compact('categories'));
});
```

```blade
{{-- resources/views/menu-test.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Menú - {{ tenant()->restaurant()->name }}</title>
</head>
<body>
    <h1>{{ tenant()->restaurant()->name }}</h1>
    <h2>Menú Digital</h2>
    
    @foreach($categories as $category)
        <h3>{{ $category->name }}</h3>
        <p>{{ $category->description }}</p>
        
        <ul>
            @foreach($category->products as $product)
                <li>
                    <strong>{{ $product->name }}</strong> - ${{ $product->price }}
                    <br>
                    <small>{{ $product->description }}</small>
                </li>
            @endforeach
        </ul>
    @endforeach
</body>
</html>
```

### 7. Probar límites de plan

```php
// Crear middleware de prueba
Route::middleware(['tenant'])->group(function () {
    Route::get('/check-limits', function () {
        $restaurant = tenant()->restaurant();
        $subscription = $restaurant->activeSubscription;
        $plan = $subscription ? $subscription->plan : null;
        
        if (!$plan) {
            return 'Sin plan activo';
        }
        
        $productCount = \App\Models\Tenant\Product::count();
        $tableCount = \App\Models\Tenant\Table::count();
        $userCount = \App\Models\User::count();
        
        return [
            'plan' => $plan->name,
            'products' => [
                'current' => $productCount,
                'max' => $plan->max_products,
                'available' => $plan->max_products - $productCount,
                'unlimited' => $plan->max_products == -1,
            ],
            'tables' => [
                'current' => $tableCount,
                'max' => $plan->max_tables,
                'available' => $plan->max_tables - $tableCount,
                'unlimited' => $plan->max_tables == -1,
            ],
            'users' => [
                'current' => $userCount,
                'max' => $plan->max_users,
                'available' => $plan->max_users - $userCount,
                'unlimited' => $plan->max_users == -1,
            ],
        ];
    });
});
```

## 🧪 Tests Automatizados

### Crear test para tenancy

```bash
php artisan make:test TenancyTest
```

```php
// tests/Feature/TenancyTest.php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Tenant\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_be_created()
    {
        $tenant = Tenant::createWithRestaurant([
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'plan' => 'free',
        ]);

        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
        ]);

        $this->assertDatabaseHas('restaurants', [
            'tenant_id' => $tenant->id,
            'name' => 'Test Restaurant',
        ]);
    }

    public function test_tenant_data_is_isolated()
    {
        // Crear dos tenants
        $tenant1 = Tenant::createWithRestaurant([
            'name' => 'Restaurant 1',
            'slug' => 'restaurant-1',
            'plan' => 'free',
        ]);

        $tenant2 = Tenant::createWithRestaurant([
            'name' => 'Restaurant 2',
            'slug' => 'restaurant-2',
            'plan' => 'free',
        ]);

        // Agregar producto al tenant 1
        $tenant1->run(function () {
            Product::create([
                'category_id' => 1,
                'name' => 'Product 1',
                'slug' => 'product-1',
                'price' => 10.00,
            ]);
        });

        // Verificar que tenant 2 no ve el producto
        $tenant2->run(function () {
            $this->assertEquals(0, Product::count());
        });

        // Verificar que tenant 1 sí ve el producto
        $tenant1->run(function () {
            $this->assertEquals(1, Product::count());
        });
    }
}
```

```bash
# Ejecutar tests
php artisan test
```

## 📊 Comandos de Diagnóstico

```bash
# Listar todos los tenants
php artisan tenants:list

# Ver información de un tenant
php artisan tinker
>>> $tenant = Tenant::find('pizzeria')
>>> $tenant->restaurant()
>>> $tenant->domains

# Verificar conexión de base de datos
php artisan tinker
>>> DB::connection('landlord')->select('SELECT DATABASE()')
>>> tenancy()->initialize('pizzeria')
>>> DB::connection('tenant')->select('SELECT DATABASE()')

# Limpiar caché de un tenant
php artisan tenants:run pizzeria --command="cache:clear"

# Ejecutar migraciones en todos los tenants
php artisan tenants:migrate

# Rollback en un tenant específico
php artisan tenants:run pizzeria --command="migrate:rollback"
```

## 🔍 Debugging

### Verificar tenant actual

```php
// En cualquier parte del código
if (tenancy()->initialized) {
    $tenant = tenant();
    dd([
        'tenant_id' => $tenant->id,
        'restaurant' => $tenant->restaurant(),
        'database' => DB::connection()->getDatabaseName(),
    ]);
}
```

### Log de cambios de tenant

```php
// app/Providers/TenancyServiceProvider.php
use Stancl\Tenancy\Events\TenancyInitialized;
use Stancl\Tenancy\Events\TenancyEnded;

Event::listen(TenancyInitialized::class, function (TenancyInitialized $event) {
    \Log::info('Tenancy initialized', [
        'tenant_id' => $event->tenancy->tenant->id,
        'database' => DB::connection()->getDatabaseName(),
    ]);
});

Event::listen(TenancyEnded::class, function (TenancyEnded $event) {
    \Log::info('Tenancy ended', [
        'tenant_id' => $event->tenancy->tenant->id,
    ]);
});
```

## ✅ Checklist de Pruebas

- [ ] Crear tenant desde comando
- [ ] Verificar base de datos creada
- [ ] Verificar migraciones ejecutadas
- [ ] Agregar datos de prueba
- [ ] Acceder por subdominio
- [ ] Verificar aislamiento de datos
- [ ] Probar límites de plan
- [ ] Verificar período de prueba
- [ ] Probar cambio entre tenants
- [ ] Verificar logs

---

**¡Sistema listo para pruebas!** 🎉
