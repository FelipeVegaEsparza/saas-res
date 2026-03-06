# 🚀 Sistema de Despliegue Automático

Sistema completo de despliegue que ejecuta automáticamente las migraciones de todos los tenants cada vez que se actualiza el código en producción.

## ⚡ Instalación Rápida (Una sola vez)

En tu servidor de producción, ejecuta:

```bash
chmod +x install-deploy.sh
./install-deploy.sh
```

¡Listo! Ya está configurado.

## 🎯 ¿Qué hace automáticamente?

Cada vez que hagas `git pull` en producción, el sistema ejecutará:

1. ✅ Modo mantenimiento
2. ✅ Instalación de dependencias (Composer + NPM)
3. ✅ Compilación de assets
4. ✅ Limpieza de cachés
5. ✅ **Migraciones de base de datos central**
6. ✅ **Migraciones de TODOS los tenants** ⭐
7. ✅ Optimización de cachés
8. ✅ Reinicio de workers
9. ✅ Desactivación de modo mantenimiento

## 📝 Uso Diario

### Despliegue Automático

```bash
# Simplemente haz pull y todo se ejecuta solo
git pull origin main
```

### Despliegue Manual

```bash
# Si prefieres ejecutarlo manualmente
./deploy.sh
```

### Solo Migrar Tenants

```bash
# Si solo necesitas migrar los tenants
php artisan tenants:migrate-all
```

## 📂 Archivos Creados

- `deploy.sh` - Script principal de despliegue
- `install-deploy.sh` - Script de instalación
- `.git-hooks/post-merge` - Hook de Git
- `app/Console/Commands/MigrateAllTenantsCommand.php` - Comando para migrar todos los tenants
- `DEPLOYMENT.md` - Documentación completa

## 🔒 Seguridad

- ✅ Solo ejecuta migraciones nuevas (no borra datos)
- ✅ Modo mantenimiento durante el proceso
- ✅ Manejo de errores por tenant
- ✅ Logs detallados de cada paso

## 🆘 Comandos Útiles

```bash
# Ver lista de tenants
php artisan tenant:list

# Migrar un tenant específico
php artisan tenant:migrate-direct {slug}

# Ver estado de migraciones de un tenant
php artisan tenant:migrate-direct {slug} --pretend

# Rollback de un tenant específico
php artisan tenant:migrate-direct {slug} --rollback
```

## 📖 Documentación Completa

Lee `DEPLOYMENT.md` para más detalles sobre:

- Configuración avanzada
- Solución de problemas
- Personalización del script
- Uso con Docker

## ✨ Beneficios

- 🚀 Despliegues más rápidos
- 🔄 Actualizaciones automáticas de todos los tenants
- 🛡️ Menos errores humanos
- 📊 Logs claros y detallados
- ⚡ Sin intervención manual necesaria

---

**Nota**: Este sistema está diseñado para producción y es completamente seguro. No borra datos existentes, solo ejecuta migraciones nuevas.
