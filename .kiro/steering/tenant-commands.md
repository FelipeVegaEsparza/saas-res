# Comandos de Tenancy - Referencia Rápida

Esta es una referencia completa de todos los comandos de tenancy disponibles en el sistema.

## 🚀 Comandos Más Usados

### Despliegue en Producción

```bash
# Migrar todos los tenants de forma segura (CON BACKUP)
php artisan tenants:safe-migrate-production

# Migrar todos los tenants (sin backup)
php artisan tenants:migrate-all

# Verificar estado de migraciones
php artisan tenants:check-migrations
```

### Gestión Diaria

```bash
# Listar todos los tenants
php artisan tenants:list

# Crear nuevo tenant
php artisan tenant:create

# Verificar estado de un tenant
php artisan tenant:status {tenant_id}
```

## 📋 Comandos Completos por Categoría

### 🔄 Migraciones

| Comando                           | Descripción                    | Uso                                   |
| --------------------------------- | ------------------------------ | ------------------------------------- |
| `tenants:safe-migrate-production` | Migra con backup automático    | **PRODUCCIÓN**                        |
| `tenants:migrate-all`             | Migra todos los tenants        | Desarrollo/Producción                 |
| `tenants:migrate {tenant}`        | Migra tenant específico        | `php artisan tenants:migrate feroces` |
| `tenants:check-migrations`        | Verifica estado de migraciones | Diagnóstico                           |
| `tenants:migrate-fresh`           | ⚠️ BORRA TODO y remigra        | **SOLO DESARROLLO**                   |
| `tenants:rollback`                | Revierte migraciones           | Rollback                              |

### 🏢 Gestión de Tenants

| Comando                      | Descripción                | Uso                                 |
| ---------------------------- | -------------------------- | ----------------------------------- |
| `tenants:list`               | Lista todos los tenants    | Información                         |
| `tenant:create`              | Crea nuevo tenant          | Onboarding                          |
| `tenant:status {tenant}`     | Estado del tenant          | `php artisan tenant:status feroces` |
| `tenant:reset-user {tenant}` | Resetea usuario del tenant | Soporte                             |

### 🌱 Seeding

| Comando                       | Descripción            | Uso                                      |
| ----------------------------- | ---------------------- | ---------------------------------------- |
| `tenants:seed`                | Seed todos los tenants | Datos iniciales                          |
| `tenant:seed-direct {tenant}` | Seed tenant específico | `php artisan tenant:seed-direct feroces` |

### 💾 Backup y Mantenimiento

| Comando                      | Descripción                     | Uso           |
| ---------------------------- | ------------------------------- | ------------- |
| `tenants:backup`             | Backup de todas las BD          | Respaldo      |
| `tenants:emergency-fix-cash` | Fix de emergencia para caja     | Soporte       |
| `tenants:fix-cash-sessions`  | Arregla sesiones de caja        | Mantenimiento |
| `tenants:fix-migrations`     | Registra migraciones existentes | Fix BD        |
| `tenant:sync-table-status`   | Sincroniza estado de mesas      | Mantenimiento |

### 🔧 Comandos Generales

| Comando                            | Descripción                  | Ejemplo                                                  |
| ---------------------------------- | ---------------------------- | -------------------------------------------------------- |
| `tenants:run "comando"`            | Ejecuta comando en todos     | `php artisan tenants:run "migrate:status"`               |
| `tenants:run "comando" --tenant=X` | Ejecuta en tenant específico | `php artisan tenants:run "cache:clear" --tenant=feroces` |

## 🎯 Flujos de Trabajo Comunes

### Despliegue de Nueva Funcionalidad

```bash
# 1. Verificar estado actual
php artisan tenants:check-migrations

# 2. Migrar de forma segura
php artisan tenants:safe-migrate-production

# 3. Limpiar cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Crear Nuevo Tenant

```bash
# 1. Crear tenant
php artisan tenant:create

# 2. Verificar que se creó correctamente
php artisan tenants:list

# 3. Verificar estado
php artisan tenant:status {nuevo_tenant_id}
```

### Diagnóstico de Problemas

```bash
# 1. Verificar estado de migraciones
php artisan tenants:check-migrations

# 2. Ver estado específico de un tenant
php artisan tenants:run "migrate:status" --tenant=feroces

# 3. Verificar tablas en BD
php artisan tenants:run "schema:dump" --tenant=feroces
```

### Mantenimiento de Emergencia

```bash
# Fix de sesiones de caja
php artisan tenants:fix-cash-sessions

# Fix de migraciones
php artisan tenants:fix-migrations

# Sincronizar estado de mesas
php artisan tenant:sync-table-status feroces
```

## ⚠️ Comandos Peligrosos

**NUNCA usar en producción:**

- `tenants:migrate-fresh` - Borra todas las tablas
- `tenants:rollback` - Sin backup previo

**Usar con precaución:**

- `tenants:migrate-all` - Sin backup automático
- `tenants:emergency-fix-cash` - Solo para emergencias

## 🔍 Comandos de Diagnóstico

```bash
# Ver todos los comandos de tenancy disponibles
php artisan list | grep tenant

# Ayuda de comando específico
php artisan tenants:migrate-all --help

# Estado detallado de migraciones
php artisan tenants:run "migrate:status"

# Verificar conexiones de BD
php artisan tenants:run "db:show"
```

## 📝 Notas Importantes

1. **Siempre usar `tenants:safe-migrate-production` en producción**
2. **Los comandos `tenant:*` (singular) son para tenant específico**
3. **Los comandos `tenants:*` (plural) son para todos los tenants**
4. **Usar `--tenant=nombre` para comandos específicos con `tenants:run`**
5. **Hacer backup manual antes de comandos peligrosos**

---

_Última actualización: Marzo 2026_
_Para más información: `php artisan list | grep tenant`_
