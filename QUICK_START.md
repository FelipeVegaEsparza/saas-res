# 🚀 GUÍA DE INICIO RÁPIDO

## Configuración Inicial (Solo una vez)

### 1. Asegúrate de que MySQL esté corriendo
```cmd
docker-compose up -d
```

### 2. Verifica que las migraciones landlord estén ejecutadas
```cmd
php artisan migrate --path=database/migrations/landlord
```

### 3. Crea los planes
```cmd
php artisan db:seed --class=PlansSeeder
```

---

## Crear tu Primer Restaurante

### Opción A: Comando Automático (Recomendado)
```cmd
php artisan tenant:create "Mi Restaurante Demo" demo --email=admin@demo.com --password=demo123
```

### Opción B: Paso a Paso

**1. Crear el tenant manualmente:**
```cmd
php artisan tinker
```

```php
$tenant = App\Models\Tenant::create([
    'id' => 'demo',
    'restaurant_name' => 'Mi Restaurante Demo',
    'plan' => 'free',
]);

$tenant->domains()->create([
    'domain' => 'demo.localhost',
]);

App\Models\Restaurant::create([
    'tenant_id' => 'demo',
    'name' => 'Mi Restaurante Demo',
    'slug' => 'demo',
    'domain' => 'demo.localhost',
    'db_name' => 'tenant_demo',
    'active' => true,
    'plan' => 'free',
    'trial_ends_at' => now()->addDays(14),
]);

exit
```

**2. Ejecutar migraciones del tenant:**
```cmd
php artisan tenant:migrate-direct demo
```

**3. Poblar con datos de prueba:**
```cmd
php artisan tenant:seed-direct demo
```

---

## Configurar Hosts (Desarrollo Local)

### Windows
Editar como Administrador: `C:\Windows\System32\drivers\etc\hosts`

### Linux/Mac
```bash
sudo nano /etc/hosts
```

### Agregar estas líneas:
```
127.0.0.1  demo.localhost
127.0.0.1  pizzeria.localhost
127.0.0.1  sushi.localhost
```

Guardar y cerrar.

---

## Iniciar el Servidor

```cmd
composer dev
```

O manualmente en dos terminales:

**Terminal 1:**
```cmd
php artisan serve
```

**Terminal 2:**
```cmd
npm run dev
```

---

## Acceder al Sistema

### 🍽️ Menú Público (Carta Digital)
```
http://demo.localhost:8000/menu
```

### 📊 Dashboard (Requiere login)
```
http://demo.localhost:8000/dashboard
```

**Credenciales:**
- Email: `admin@demo.com`
- Password: `demo123`

### 🔲 Ver QR de Mesa
```
http://demo.localhost:8000/qr/table/1
```

### 🖨️ Imprimir Todos los QR
```
http://demo.localhost:8000/qr/print-all
```

---

## Crear Más Restaurantes

```cmd
# Pizzería
php artisan tenant:create "Pizzería Italiana" pizzeria --email=admin@pizzeria.com --password=pizza123
php artisan tenant:migrate-direct pizzeria
php artisan tenant:seed-direct pizzeria

# Sushi Bar
php artisan tenant:create "Sushi Bar Tokyo" sushi --email=admin@sushi.com --password=sushi123
php artisan tenant:migrate-direct sushi
php artisan tenant:seed-direct sushi

# Burger House
php artisan tenant:create "Burger House" burger --email=admin@burger.com --password=burger123
php artisan tenant:migrate-direct burger
php artisan tenant:seed-direct burger
```

Acceder a:
- `http://pizzeria.localhost:8000/menu`
- `http://sushi.localhost:8000/menu`
- `http://burger.localhost:8000/menu`

---

## Verificar que Todo Funciona

### 1. Listar Tenants
```cmd
php artisan tenants:list
```

### 2. Ver Bases de Datos
```cmd
php artisan tinker
```
```php
DB::connection('landlord')->select('SHOW DATABASES LIKE "tenant_%"');
exit
```

### 3. Ver Restaurantes
```cmd
php artisan tinker
```
```php
App\Models\Restaurant::all(['name', 'slug', 'domain', 'plan']);
exit
```

### 4. Probar Menú
Abrir navegador: `http://demo.localhost:8000/menu`

Deberías ver:
- ✅ Nombre del restaurante
- ✅ 4 categorías (Entradas, Principales, Postres, Bebidas)
- ✅ 12 productos con precios
- ✅ Diseño responsive

### 5. Probar QR
Abrir: `http://demo.localhost:8000/qr/print-all`

Deberías ver:
- ✅ 15 códigos QR (uno por mesa)
- ✅ Información de cada mesa
- ✅ Botón de imprimir

---

## ⚠️ Nota Importante sobre Comandos de Tenant

Debido a un problema con el `DatabaseTenancyBootstrapper` de stancl/tenancy, los comandos estándar (`tenants:migrate` y `tenants:seed`) no cambian correctamente a la base de datos del tenant.

**Solución:** Hemos creado comandos personalizados que funcionan correctamente:

- ✅ `php artisan tenant:migrate-direct {tenant_id}` - Migra directamente a la BD del tenant
- ✅ `php artisan tenant:seed-direct {tenant_id}` - Seed directamente en la BD del tenant

Estos comandos:
1. Crean la base de datos del tenant si no existe
2. Configuran la conexión correctamente
3. Ejecutan las migraciones/seeders en la base de datos correcta

---

## Comandos Útiles

### Limpiar Cachés
```cmd
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Ver Rutas Tenant
```cmd
php artisan route:list --path=tenant
```

### Ejecutar Comando en un Tenant
```cmd
php artisan tenants:run demo --command="cache:clear"
```

### Eliminar un Tenant
```cmd
php artisan tinker
```
```php
$tenant = App\Models\Tenant::find('demo');
$tenant->delete(); // Esto también elimina la base de datos
exit
```

---

## Troubleshooting

### ❌ Error: "Tenant could not be identified"
**Solución:** Verifica que el dominio esté en la tabla `domains`
```cmd
php artisan tinker
```
```php
DB::connection('landlord')->table('domains')->get();
exit
```

### ❌ Error: "Base table or view not found"
**Solución:** Ejecuta las migraciones del tenant con el comando directo
```cmd
php artisan tenant:migrate-direct demo
```

### ❌ No se ven los productos
**Solución:** Ejecuta el seeder con el comando directo
```cmd
php artisan tenant:seed-direct demo
```

### ❌ El subdominio no funciona
**Solución:** Verifica el archivo hosts
```cmd
# Windows
notepad C:\Windows\System32\drivers\etc\hosts

# Debe contener:
127.0.0.1  demo.localhost
```

### ❌ Error de conexión a MySQL
**Solución:** Verifica que Docker esté corriendo
```cmd
docker ps
docker-compose up -d
```

---

## 🎉 ¡Listo!

Tu sistema multi-tenant está funcionando. Ahora puedes:

1. ✅ Crear múltiples restaurantes
2. ✅ Cada uno con su propia base de datos
3. ✅ Cada uno con su propio subdominio
4. ✅ Carta digital pública
5. ✅ Códigos QR por mesa
6. ✅ Dashboard básico

**Siguiente paso:** Implementar autenticación y CRUD completo (Fase 3)
