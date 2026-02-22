# Panel de Administración - Configuración

## Estructura Implementada

### 1. Sitio Público (Landing)

- **URL**: `/`
- **Rutas**:
  - `/` - Página principal
  - `/features` - Características
  - `/pricing` - Planes y precios
  - `/contact` - Contacto

### 2. Panel de Administración

- **URL**: `/admin`
- **Credenciales de acceso**:
  - Email: `admin@admin.com`
  - Contraseña: `admin123`

#### Funcionalidades Implementadas:

- ✅ Dashboard con métricas generales
- ✅ Gestión de restaurantes (CRUD completo)
- ✅ Gestión de suscripciones (visualización y edición)
- ✅ Gestión de planes (CRUD completo)
- ✅ Autenticación separada con guard `admin`
- ✅ Sitio público (landing) con diseño personalizado

#### Rutas del Admin:

- `/admin/login` - Login
- `/admin/dashboard` - Dashboard principal
- `/admin/restaurants` - Lista de restaurantes
- `/admin/restaurants/{id}` - Detalle de restaurante
- `/admin/restaurants/{id}/edit` - Editar restaurante
- `/admin/subscriptions` - Lista de suscripciones
- `/admin/plans` - Gestión de planes

### 3. Sistema de Tenants

- **URL**: `/{tenant}/*`
- Ya estaba implementado, se mantiene sin cambios

## Archivos Creados

### Controladores

- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/RestaurantController.php`
- `app/Http/Controllers/Admin/SubscriptionController.php`
- `app/Http/Controllers/Admin/PlanController.php`
- `app/Http/Controllers/Admin/Auth/LoginController.php`
- `app/Http/Controllers/LandingController.php`

### Modelos

- `app/Models/Admin.php`

### Middleware

- `app/Http/Middleware/AdminAuthenticate.php`

### Vistas

- `resources/views/admin/layouts/admin.blade.php` - Layout del admin
- `resources/views/admin/dashboard/index.blade.php` - Dashboard
- `resources/views/admin/restaurants/index.blade.php` - Lista de restaurantes
- `resources/views/admin/restaurants/show.blade.php` - Detalle de restaurante
- `resources/views/admin/auth/login.blade.php` - Login del admin
- `resources/views/landing/layouts/app.blade.php` - Layout del landing
- `resources/views/landing/index.blade.php` - Página principal

### Migraciones

- `database/migrations/2026_02_21_231028_create_admins_table.php`

### Seeders

- `database/seeders/AdminSeeder.php`

## Configuración

### Guards de Autenticación

Se agregó el guard `admin` en `config/auth.php`:

```php
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],
```

### Middleware

Se registró el middleware `admin.auth` en `bootstrap/app.php`

## Próximos Pasos

### Fase 1 - Completada ✅

- ✅ Panel de administración completo
- ✅ Gestión de restaurantes (CRUD)
- ✅ Gestión de planes (CRUD)
- ✅ Gestión de suscripciones (visualización y edición)
- ✅ Dashboard con métricas
- ✅ Sitio público (landing)
- ✅ Autenticación separada

### Fase 2 - Pagos (Pendiente)

- [ ] Integración con pasarela de pagos (Stripe/MercadoPago)
- [ ] Gestión de facturas
- [ ] Historial de pagos detallado
- [ ] Webhooks para renovaciones automáticas
- [ ] Notificaciones de pago

### Fase 3 - Avanzado (Pendiente)

- [ ] Reportes y analytics avanzados
- [ ] Sistema de notificaciones por email
- [ ] Impersonation (acceder como tenant desde admin)
- [ ] Logs de actividad y auditoría
- [ ] Exportación de datos (CSV, PDF)
- [ ] API REST para integraciones
- [ ] Sistema de tickets/soporte

## Vistas Completadas

### Admin

- ✅ `admin/layouts/admin.blade.php`
- ✅ `admin/dashboard/index.blade.php`
- ✅ `admin/auth/login.blade.php`
- ✅ `admin/restaurants/index.blade.php`
- ✅ `admin/restaurants/show.blade.php`
- ✅ `admin/restaurants/edit.blade.php`
- ✅ `admin/subscriptions/index.blade.php`
- ✅ `admin/subscriptions/show.blade.php`
- ✅ `admin/subscriptions/edit.blade.php`
- ✅ `admin/plans/index.blade.php`
- ✅ `admin/plans/create.blade.php`
- ✅ `admin/plans/edit.blade.php`

### Landing

- ✅ `landing/layouts/app.blade.php`
- ✅ `landing/index.blade.php`
- ✅ `landing/features.blade.php`
- ✅ `landing/pricing.blade.php`
- ✅ `landing/contact.blade.php`

## Notas Importantes

1. **Diseño**: El panel admin usa el mismo template base (Materialize) manteniendo colores y componentes
2. **Landing**: Usa diseño personalizado pero mantiene la paleta de colores del template
3. **Autenticación**: Sistema completamente separado entre admin y tenants
4. **Base de datos**: Los admins se guardan en la tabla `admins` (base de datos central)

## Cómo Probar

### 1. Acceso al Panel Admin

1. Acceder a `/admin/login`
2. Usar credenciales: `admin@admin.com` / `admin123`
3. Serás redirigido al dashboard

### 2. Probar Gestión de Planes

1. Ir a "Planes" en el menú lateral
2. Crear un nuevo plan:
   - Click en "Nuevo Plan"
   - Llenar formulario (nombre, precio, características)
   - El slug se genera automáticamente
   - Guardar
3. Editar un plan existente:
   - Click en "Editar" en cualquier plan
   - Modificar datos
   - Agregar/eliminar características
   - Guardar cambios
4. Eliminar un plan (solo si no tiene suscripciones)

### 3. Probar Gestión de Restaurantes

1. Ir a "Restaurantes" en el menú lateral
2. Ver lista de restaurantes con sus estados
3. Click en un restaurante para ver detalles:
   - Información general
   - Suscripción activa
   - Estadísticas de uso
4. Editar configuración de un restaurante:
   - Cambiar nombre, email, teléfono
   - Modificar configuración de pedidos online
   - Actualizar moneda y símbolo
   - Guardar cambios

### 4. Probar Gestión de Suscripciones

1. Ir a "Suscripciones" en el menú lateral
2. Ver todas las suscripciones con filtros por estado
3. Click en una suscripción para ver detalles
4. Editar suscripción:
   - Cambiar estado (activa/suspendida/cancelada)
   - Modificar fechas
   - Guardar

### 5. Probar Sitio Público

1. Acceder a `/` (página principal)
2. Navegar a `/features` (características)
3. Ver `/pricing` (planes y precios)
4. Visitar `/contact` (contacto)
5. Verificar que el diseño sea responsive
6. Comprobar que los colores del template se mantienen

### 6. Verificar Separación de Autenticación

1. Iniciar sesión en admin (`/admin/login`)
2. En otra pestaña, intentar acceder a un tenant (ej: `/demo/dashboard`)
3. Verificar que son sesiones independientes
4. Cerrar sesión en admin no debe afectar sesión de tenant

## Seguridad

- Las contraseñas se hashean con bcrypt
- Middleware de autenticación protege todas las rutas admin
- Guards separados previenen conflictos entre admin y tenants
- CSRF protection en todos los formularios
