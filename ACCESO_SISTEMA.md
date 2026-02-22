# 🎯 ACCESO AL SISTEMA - TENANT DEMO

## ✅ Estado: Sistema Listo

La base de datos del tenant "demo" está completamente configurada con datos de prueba.

## 📋 Datos de Acceso

### Usuario Administrador

- **Email**: admin@demo.com
- **Password**: demo123

### Datos en la Base de Datos

- ✅ 1 usuario administrador
- ✅ 4 categorías de productos
- ✅ 12 productos en el menú
- ✅ 15 mesas configuradas
- ✅ Sistema de delivery y para llevar

## 🚀 Iniciar el Servidor

```bash
php artisan serve
```

El servidor iniciará en: http://127.0.0.1:8000

## 🔗 URLs de Acceso (Path-Based)

### Menú Público (Carta Digital)

```
http://localhost:8000/demo/menu
```

No requiere autenticación. Los clientes pueden ver el menú directamente.

### Panel de Administración

```
http://localhost:8000/demo/login
```

Credenciales:

- Email: admin@demo.com
- Password: demo123

### Dashboard

```
http://localhost:8000/demo/dashboard
```

### Gestión de Productos

```
http://localhost:8000/demo/products
```

### Gestión de Categorías

```
http://localhost:8000/demo/categories
```

### Gestión de Mesas

```
http://localhost:8000/demo/tables
```

### Gestión de Delivery

```
http://localhost:8000/demo/delivery
```

### Códigos QR

```
http://localhost:8000/demo/qr/print-all
```

## 📝 Nota sobre URLs

El sistema ahora usa **path-based tenancy**, lo que significa que el identificador del tenant va en la URL:

- ✅ **Formato**: `http://localhost:8000/{tenant}/{ruta}`
- ✅ **Ejemplo**: `http://localhost:8000/demo/menu`
- ✅ **No requiere** modificar el archivo hosts
- ✅ **Funciona** directamente en localhost

### Ventajas

- No necesitas configurar subdominios en desarrollo
- Más fácil para pruebas locales
- Funciona inmediatamente después de clonar el proyecto

### Para Producción

En producción puedes usar subdominios (demo.tusistema.com) activando las rutas con `InitializeTenancyByDomain` en lugar de path-based.

## 🛠️ Comandos Útiles

### Verificar estado del tenant

```bash
php artisan tenant:status demo
```

### Ver datos del tenant

```bash
docker exec -it saasres-mysql-1 mysql -u sail -ppassword -e "USE tenant_demo; SELECT * FROM users;"
```

### Ver todas las tablas

```bash
docker exec -it saasres-mysql-1 mysql -u sail -ppassword -e "USE tenant_demo; SHOW TABLES;"
```

### Contar registros

```bash
docker exec -it saasres-mysql-1 mysql -u sail -ppassword -e "USE tenant_demo; SELECT COUNT(*) as users FROM users; SELECT COUNT(*) as categories FROM categories; SELECT COUNT(*) as products FROM products; SELECT COUNT(*) as tables FROM tables;"
```

## 🔧 Solución de Problemas

### Error: "Tenant could not be identified"

**Causa**: El tenant no existe o el ID es incorrecto.

**Solución**: Verifica que el tenant existe:

```bash
php artisan tenant:status demo
```

### Error: "SQLSTATE[HY000] [1049] Unknown database"

**Causa**: La base de datos del tenant no existe.

**Solución**:

```bash
php artisan tenant:migrate-direct demo
```

### No se ven productos en el menú

**Causa**: Los datos no se han insertado.

**Solución**:

```bash
php artisan tenant:seed-direct demo
```

### Error 404 en las rutas

**Causa**: La caché de rutas está desactualizada.

**Solución**:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 📊 Estructura de la Base de Datos Tenant

```
tenant_demo
├── users (usuarios del restaurante)
├── categories (categorías de productos)
├── products (productos del menú)
├── tables (mesas del restaurante)
├── orders (pedidos)
├── order_items (items de pedidos)
├── payments (pagos)
├── cash_sessions (sesiones de caja)
├── delivery_orders (pedidos delivery/para llevar)
└── delivery_order_items (items de pedidos delivery)
```

## 🎉 ¡Todo Listo!

Accede directamente a:

1. **Menú Público**: http://localhost:8000/demo/menu
2. **Login**: http://localhost:8000/demo/login
3. **Dashboard**: http://localhost:8000/demo/dashboard

¡No necesitas configurar nada más!

---

## 🔐 Panel de Administración (Admin)

### Acceso al Admin

- **URL**: http://localhost:8000/admin/login
- **Email**: `admin@admin.com`
- **Contraseña**: `admin123`

### Funcionalidades del Admin

El panel de administración permite gestionar todo el sistema SaaS:

- **Dashboard**: Métricas generales del sistema
- **Restaurantes**: Gestión completa de clientes (crear, editar, ver detalles)
- **Planes**: Crear y gestionar planes de suscripción
- **Suscripciones**: Ver y editar suscripciones de restaurantes

### URLs del Admin

```
http://localhost:8000/admin/login          → Login del administrador
http://localhost:8000/admin/dashboard      → Dashboard con métricas
http://localhost:8000/admin/restaurants    → Gestión de restaurantes
http://localhost:8000/admin/plans          → Gestión de planes
http://localhost:8000/admin/subscriptions  → Gestión de suscripciones
```

---

## 🌐 Sitio Público (Landing)

El sitio público promociona el sistema SaaS:

```
http://localhost:8000/           → Página principal
http://localhost:8000/features   → Características del sistema
http://localhost:8000/pricing    → Planes y precios
http://localhost:8000/contact    → Formulario de contacto
```

---

## 📐 Arquitectura del Sistema

### Tres Niveles de Acceso

1. **Sitio Público** (`/`)

   - Landing page para promocionar el SaaS
   - No requiere autenticación
   - Muestra planes, características y contacto

2. **Panel Admin** (`/admin/*`)

   - Gestión del sistema SaaS
   - Autenticación independiente (guard: admin)
   - Administra restaurantes, planes y suscripciones

3. **Panel Tenant** (`/{tenant}/*`)
   - Sistema de cada restaurante
   - Autenticación independiente (guard: web)
   - Gestión de mesas, productos, pedidos, etc.

### Separación de Bases de Datos

- **Base de datos central**: Admins, restaurantes, planes, suscripciones
- **Base de datos por tenant**: Productos, mesas, pedidos, usuarios del restaurante

---

## 🔄 Flujo de Trabajo Completo

### 1. Cliente Potencial

1. Visita el sitio público (`/`)
2. Ve características y planes (`/features`, `/pricing`)
3. Se contacta o registra

### 2. Administrador del SaaS

1. Accede al admin (`/admin/login`)
2. Crea un nuevo restaurante
3. Asigna un plan de suscripción
4. Gestiona pagos y renovaciones

### 3. Restaurante (Tenant)

1. Accede a su panel (`/demo/login`)
2. Configura productos, mesas, usuarios
3. Usa el sistema POS
4. Recibe pedidos online

---

## 🎯 Resumen de Credenciales

| Sistema     | URL            | Email           | Contraseña |
| ----------- | -------------- | --------------- | ---------- |
| Admin       | `/admin/login` | admin@admin.com | admin123   |
| Tenant Demo | `/demo/login`  | admin@demo.com  | demo123    |

---

## 📚 Documentación Adicional

- Ver `ADMIN_SETUP.md` para detalles completos del panel de administración
- Ver documentación de Laravel Tenancy para gestión de tenants
