# 🚀 Quick Start Guide

## Inicio Rápido en 3 Pasos

### 1️⃣ Ejecutar Migraciones y Seeders

```bash
# Migrar base de datos central (admin, restaurantes, planes)
php artisan migrate

# Crear usuario admin
php artisan db:seed --class=AdminSeeder
```

### 2️⃣ Iniciar el Servidor

```bash
php artisan serve
```

El servidor estará disponible en: `http://localhost:8000`

### 3️⃣ Acceder al Sistema

#### Panel de Administración

- URL: http://localhost:8000/admin/login
- Email: `admin@admin.com`
- Password: `admin123`

#### Sitio Público

- URL: http://localhost:8000/

#### Panel Tenant (Demo)

- URL: http://localhost:8000/demo/login
- Email: `admin@demo.com`
- Password: `demo123`

---

## 📋 Checklist de Configuración

- [ ] Base de datos configurada en `.env`
- [ ] Migraciones ejecutadas (`php artisan migrate`)
- [ ] Admin seeder ejecutado (`php artisan db:seed --class=AdminSeeder`)
- [ ] Servidor iniciado (`php artisan serve`)
- [ ] Acceso al admin verificado

---

## 🎯 Primeros Pasos en el Admin

### 1. Crear un Plan

1. Login en `/admin/login`
2. Ir a "Planes" en el menú
3. Click en "Nuevo Plan"
4. Llenar formulario:
   - Nombre: "Plan Básico"
   - Slug: "plan-basico"
   - Precio: 9990
   - Ciclo: Mensual
   - Límites: 5 usuarios, 10 mesas, 50 productos
5. Agregar características
6. Guardar

### 2. Ver Restaurantes

1. Ir a "Restaurantes"
2. Ver lista de restaurantes existentes
3. Click en un restaurante para ver detalles
4. Editar configuración si es necesario

### 3. Gestionar Suscripciones

1. Ir a "Suscripciones"
2. Ver todas las suscripciones
3. Filtrar por estado
4. Editar o cancelar según necesidad

---

## 🔧 Comandos Útiles

### Limpiar Caché

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Ver Rutas

```bash
# Ver todas las rutas admin
php artisan route:list --path=admin

# Ver todas las rutas tenant
php artisan route:list --path=demo
```

### Crear Nuevo Tenant

```bash
php artisan tenant:create {nombre}
php artisan tenant:migrate-direct {nombre}
php artisan tenant:seed-direct {nombre}
```

### Verificar Estado de Tenant

```bash
php artisan tenant:status {nombre}
```

---

## 🐛 Solución de Problemas

### Error: "Admin not found"

**Solución**: Ejecutar el seeder

```bash
php artisan db:seed --class=AdminSeeder
```

### Error: "Table admins doesn't exist"

**Solución**: Ejecutar migraciones

```bash
php artisan migrate
```

### Error: "Route not found"

**Solución**: Limpiar caché de rutas

```bash
php artisan route:clear
php artisan config:clear
```

### No puedo iniciar sesión en admin

**Verificar**:

1. Credenciales correctas: `admin@admin.com` / `admin123`
2. Tabla `admins` existe en la base de datos
3. Usuario admin existe en la tabla

---

## 📚 Documentación Adicional

- `ADMIN_SETUP.md` - Documentación completa del panel admin
- `ACCESO_SISTEMA.md` - Todas las credenciales y URLs
- `DESARROLLO_COMPLETADO.md` - Resumen de lo implementado

---

## 🎉 ¡Listo!

Ahora puedes:

- ✅ Gestionar restaurantes desde el admin
- ✅ Crear y editar planes de suscripción
- ✅ Administrar suscripciones
- ✅ Ver métricas en el dashboard
- ✅ Promocionar el SaaS con el sitio público

**¿Necesitas ayuda?** Revisa la documentación en los archivos `.md` del proyecto.
