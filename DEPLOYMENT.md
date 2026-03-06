# Guía de Despliegue Automático

Este proyecto incluye un sistema de despliegue automático que ejecuta migraciones de todos los tenants cada vez que se actualiza el código en producción.

## 📋 Configuración Inicial (Solo una vez)

### 1. Dar permisos de ejecución al script

```bash
chmod +x deploy.sh
chmod +x .git-hooks/post-merge
```

### 2. Configurar el hook de Git

```bash
# Copiar el hook a la carpeta de Git
cp .git-hooks/post-merge .git/hooks/post-merge
chmod +x .git/hooks/post-merge
```

O si prefieres usar un enlace simbólico:

```bash
ln -s ../../.git-hooks/post-merge .git/hooks/post-merge
```

## 🚀 Uso

### Despliegue Automático

Una vez configurado, cada vez que hagas `git pull` en producción, el sistema automáticamente:

1. ✅ Activa modo mantenimiento
2. ✅ Instala dependencias de Composer
3. ✅ Compila assets (si es necesario)
4. ✅ Limpia cachés
5. ✅ Ejecuta migraciones de la base de datos central
6. ✅ **Ejecuta migraciones de TODOS los tenants automáticamente**
7. ✅ Optimiza la aplicación
8. ✅ Reinicia workers (si existen)
9. ✅ Desactiva modo mantenimiento

```bash
# Simplemente haz pull y todo se ejecuta automáticamente
git pull origin main
```

### Despliegue Manual

Si prefieres ejecutar el despliegue manualmente:

```bash
# Ejecutar script completo (incluye git pull)
./deploy.sh

# O ejecutar sin hacer git pull
./deploy.sh --skip-pull
```

### Solo Migrar Tenants

Si solo necesitas ejecutar las migraciones de los tenants:

```bash
php artisan tenants:migrate-all
```

## 🔧 Comandos Disponibles

### Migración de Tenants

```bash
# Migrar todos los tenants (solo nuevas migraciones)
php artisan tenants:migrate-all

# Migrar todos los tenants y ejecutar seeders
php artisan tenants:migrate-all --seed

# Ver lista de tenants
php artisan tenant:list

# Migrar un tenant específico
php artisan tenant:migrate-direct {slug}
```

### Despliegue

```bash
# Despliegue completo con git pull
./deploy.sh

# Despliegue sin git pull
./deploy.sh --skip-pull
```

## ⚠️ Notas Importantes

1. **Seguridad**: El comando `tenants:migrate-all` solo ejecuta migraciones nuevas, NO borra datos existentes.

2. **Modo Mantenimiento**: Durante el despliegue, la aplicación estará en modo mantenimiento por aproximadamente 1-2 minutos.

3. **Rollback**: Si algo sale mal, puedes hacer rollback manualmente:

   ```bash
   git reset --hard HEAD~1
   php artisan migrate:rollback --path=database/migrations/tenant
   php artisan tenants:migrate-all
   ```

4. **Logs**: Todos los errores se mostrarán en la consola durante el despliegue.

## 🐳 Despliegue con Docker

Si usas Docker, ejecuta el script dentro del contenedor:

```bash
docker exec -it nombre_contenedor bash -c "./deploy.sh"
```

## 📝 Personalización

Puedes editar `deploy.sh` para agregar pasos adicionales según tus necesidades:

- Compilar assets adicionales
- Ejecutar tests
- Notificaciones (Slack, Discord, etc.)
- Backups automáticos
- Etc.

## 🆘 Solución de Problemas

### El hook no se ejecuta automáticamente

```bash
# Verificar que el hook existe y tiene permisos
ls -la .git/hooks/post-merge
chmod +x .git/hooks/post-merge
```

### Error de permisos

```bash
# Dar permisos al script
chmod +x deploy.sh
chmod +x .git-hooks/post-merge
```

### Migraciones fallan en un tenant

El script continuará con los demás tenants. Revisa los logs y ejecuta manualmente:

```bash
php artisan tenant:migrate-direct {slug}
```

## 📚 Más Información

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Tenancy](https://tenancyforlaravel.com/docs)
