# 🏗️ IMPLEMENTACIÓN MULTI-TENANCY SAAS - RESTAURANTES

## ✅ FASE 1 COMPLETADA: Configuración Base Tenancy

### 📦 Paquetes Instalados
- **stancl/tenancy v3.9.1** - Sistema multi-tenancy completo

### 🗄️ Arquitectura de Base de Datos

#### Base de Datos LANDLORD (Central)
**Conexión:** `landlord`
**Base de datos:** `laravel`

**Tablas:**
- `tenants` - Información de tenants (generada por stancl/tenancy)
- `domains` - Dominios/subdominios de cada tenant
- `restaurants` - Información extendida de restaurantes
- `plans` - Planes de suscripción (Free, Basic, Premium)
- `subscriptions` - Suscripciones activas
- `users` - Usuarios del sistema central
- `cache`, `jobs`, `sessions` - Tablas del sistema

#### Base de Datos TENANT (Una por restaurante)
**Conexión:** `tenant` (dinámica)
**Base de datos:** `tenant_{uuid}`

**Tablas:**
- `users` - Usuarios del restaurante (owner, manager, staff, waiter)
- `categories` - Categorías de productos
- `products` - Productos del menú
- `tables` - Mesas del restaurante
- `orders` - Órdenes/pedidos
- `order_items` - Items de cada orden
- `payments` - Pagos realizados
- `cash_sessions` - Sesiones de caja

### 📁 Estructura de Archivos Creados

```
app/
├── Models/
│   ├── Restaurant.php          # Modelo landlord
│   ├── Plan.php                # Modelo landlord
│   ├── Subscription.php        # Modelo landlord
│   ├── Tenant.php              # Modelo tenant personalizado
│   └── Tenant/                 # Modelos tenant
│       ├── Category.php
│       ├── Product.php
│       ├── Table.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Payment.php
│       └── CashSession.php
└── Console/Commands/
    └── CreateTenantCommand.php # Comando para crear tenants

config/
├── database.php                # Configuración de conexiones
└── tenancy.php                 # Configuración de tenancy

database/migrations/
├── landlord/                   # Migraciones centrales
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2024_*_create_tenants_table.php
│   ├── 2024_*_create_domains_table.php
│   ├── 2026_02_14_000001_create_restaurants_table.php
│   ├── 2026_02_14_000002_create_plans_table.php
│   └── 2026_02_14_000003_create_subscriptions_table.php
└── tenant/                     # Migraciones por restaurante
    ├── 2026_02_14_000001_create_tenant_users_table.php
    ├── 2026_02_14_000002_create_categories_table.php
    ├── 2026_02_14_000003_create_products_table.php
    ├── 2026_02_14_000004_create_tables_table.php
    ├── 2026_02_14_000005_create_orders_table.php
    ├── 2026_02_14_000006_create_order_items_table.php
    ├── 2026_02_14_000007_create_payments_table.php
    └── 2026_02_14_000008_create_cash_sessions_table.php

database/seeders/
└── PlansSeeder.php             # Seeder de planes
```

### 🎯 Planes Configurados

| Plan | Precio | Productos | Mesas | Usuarios | Dominio | Analytics |
|------|--------|-----------|-------|----------|---------|-----------|
| Free | $0 | 20 | 5 | 2 | ❌ | ❌ |
| Basic | $29.99 | 100 | 20 | 5 | ❌ | ✅ |
| Premium | $79.99 | ∞ | ∞ | ∞ | ✅ | ✅ |

### 🚀 Comandos Disponibles

#### Crear un nuevo tenant (restaurante)
```bash
php artisan tenant:create "Mi Restaurante" --email=admin@restaurant.com --password=secret123
```

Con slug personalizado:
```bash
php artisan tenant:create "Pizza House" pizzahouse --plan=basic --email=admin@pizza.com
```

#### Listar todos los tenants
```bash
php artisan tenants:list
```

#### Ejecutar migraciones en todos los tenants
```bash
php artisan tenants:migrate
```

#### Ejecutar comando en un tenant específico
```bash
php artisan tenants:run {tenant_id} --command="db:seed"
```

### 🔧 Configuración .env

```env
# Base de datos central (landlord)
DB_CONNECTION=landlord
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# Dominio central (en producción será tusistema.com)
CENTRAL_DOMAIN=localhost
```

### 📝 Ejemplo de Uso: Crear Tenant

```php
use App\Models\Tenant;

// Crear tenant con restaurante
$tenant = Tenant::createWithRestaurant([
    'name' => 'La Pizzería',
    'slug' => 'pizzeria',
    'plan' => 'basic',
]);

// El sistema automáticamente:
// 1. Crea el tenant en la tabla tenants
// 2. Crea el dominio: pizzeria.localhost
// 3. Crea el registro en restaurants
// 4. Crea la base de datos: tenant_{uuid}
// 5. Ejecuta las migraciones tenant
// 6. Asigna 14 días de prueba

// Acceder al restaurante
$restaurant = $tenant->restaurant();
echo $restaurant->url; // https://pizzeria.localhost
echo $restaurant->menu_url; // https://pizzeria.localhost/menu
```

### 🔐 Seguridad y Aislamiento

1. **Aislamiento de Base de Datos**: Cada tenant tiene su propia base de datos
2. **Identificación por Subdominio**: Automática mediante middleware
3. **Conexión Dinámica**: Se cambia automáticamente según el subdominio
4. **Validación de Acceso**: No es posible acceder a datos de otro tenant

### 🌐 Flujo de Identificación

```
Usuario accede a: pizzeria.localhost
         ↓
Middleware InitializeTenancyByDomain
         ↓
Busca dominio en tabla domains
         ↓
Obtiene tenant_id
         ↓
Cambia conexión DB a tenant_{uuid}
         ↓
Todas las queries usan la DB del tenant
```

### 📊 Modelo de Datos

#### Restaurant (Landlord)
```php
$restaurant->tenant_id;        // UUID del tenant
$restaurant->name;             // Nombre del restaurante
$restaurant->slug;             // Slug para subdominio
$restaurant->domain;           // pizzeria.localhost
$restaurant->db_name;          // tenant_uuid
$restaurant->active;           // Estado
$restaurant->plan;             // free, basic, premium
$restaurant->trial_ends_at;    // Fin del período de prueba
$restaurant->subscribed_at;    // Fecha de suscripción
$restaurant->settings;         // JSON con configuraciones
```

#### Tenant (Stancl)
```php
$tenant->id;                   // UUID
$tenant->restaurant_name;      // Nombre
$tenant->plan;                 // Plan actual
$tenant->domains;              // Relación con dominios
$tenant->restaurant();         // Obtener Restaurant
```

### 🎨 Modelos Tenant

Todos los modelos tenant están en `App\Models\Tenant\`:

- **Category**: Categorías del menú
- **Product**: Productos con precio, imagen, alergenos, tags
- **Table**: Mesas con QR único, capacidad, estado
- **Order**: Órdenes con items, totales, estado
- **OrderItem**: Items individuales de cada orden
- **Payment**: Pagos con método, estado, transacción
- **CashSession**: Sesiones de caja con apertura/cierre

### ✨ Características Implementadas

✅ Multi-tenancy con bases de datos separadas
✅ Identificación por subdominio
✅ Modelo Restaurant extendido
✅ Sistema de planes y suscripciones
✅ Migraciones separadas (landlord/tenant)
✅ Comando para crear tenants automáticamente
✅ Modelos tenant completos para restaurante
✅ Período de prueba de 14 días
✅ Aislamiento total de datos

### 🚧 PRÓXIMAS FASES

#### FASE 2: Rutas y Middleware
- Configurar rutas tenant
- Middleware de autenticación tenant
- Rutas públicas para menú digital

#### FASE 3: Carta Digital y QR
- Controlador de menú público
- Generación de QR por mesa
- Vista de menú responsive

#### FASE 4: Panel de Administración
- Dashboard landlord
- Gestión de restaurantes
- Gestión de suscripciones

#### FASE 5: Panel Tenant
- Dashboard restaurante
- CRUD de productos
- Gestión de mesas y órdenes

#### FASE 6: Producción
- Configuración wildcard DNS
- SSL automático
- Optimizaciones de rendimiento

### 📚 Documentación Adicional

- [Tenancy for Laravel](https://tenancyforlaravel.com/docs/v3/)
- [Stancl/Tenancy GitHub](https://github.com/stancl/tenancy)

### 🆘 Troubleshooting

**Error: "Tenant could not be identified"**
- Verificar que el dominio esté en la tabla `domains`
- Verificar configuración de `central_domains` en `config/tenancy.php`

**Error: "Database does not exist"**
- Ejecutar: `php artisan tenants:migrate`
- Verificar que la base de datos del tenant exista

**Error: "Connection refused"**
- Verificar que MySQL esté corriendo
- Verificar credenciales en `.env`

---

## 🎉 FASE 1 COMPLETADA CON ÉXITO

El sistema multi-tenancy está configurado y funcionando. Cada restaurante tendrá:
- Su propia base de datos aislada
- Su propio subdominio (restaurante.tusistema.com)
- Su propio conjunto de usuarios, productos, mesas y órdenes
- Gestión independiente sin interferencia con otros restaurantes

**Siguiente paso:** Implementar FASE 2 - Rutas y Middleware
