# 📋 Instrucciones para Configurar en el Servidor

## Paso 1: Subir los archivos al servidor

Haz commit y push de los nuevos archivos:

```bash
git add .
git commit -m "Add: Sistema de despliegue automático con migraciones de tenants"
git push origin main
```

## Paso 2: En el servidor de producción

Conéctate por SSH a tu servidor y ve al directorio del proyecto:

```bash
ssh usuario@tu-servidor.com
cd /var/www/html  # o la ruta donde esté tu proyecto
```

## Paso 3: Actualizar el código

```bash
git pull origin main
```

## Paso 4: Instalar el sistema de despliegue

```bash
chmod +x install-deploy.sh
./install-deploy.sh
```

## Paso 5: Ejecutar el primer despliegue

```bash
./deploy.sh
```

Esto ejecutará:

- Migraciones de la base de datos central
- Migraciones de TODOS los tenants existentes (feroces y prueba)
- Optimización de cachés

## ✅ Verificación

Después del despliegue, verifica que todo funcione:

```bash
# Ver lista de tenants
php artisan tenant:list

# Verificar que las tablas existan en un tenant
php artisan tenant:migrate-direct feroces --pretend
```

## 🎉 ¡Listo!

Desde ahora, cada vez que hagas `git pull` en producción, el sistema automáticamente:

- Instalará dependencias
- Ejecutará migraciones de la BD central
- Ejecutará migraciones de TODOS los tenants
- Optimizará la aplicación

## 🔄 Uso Futuro

Cada vez que hagas cambios en desarrollo:

1. **En desarrollo:**

   ```bash
   git add .
   git commit -m "Tu mensaje"
   git push origin main
   ```

2. **En producción:**
   ```bash
   git pull origin main
   # ¡Todo se ejecuta automáticamente! 🎉
   ```

## 📝 Notas Importantes

- El sistema es seguro: solo ejecuta migraciones nuevas, no borra datos
- Durante el despliegue, la app estará en modo mantenimiento ~1-2 minutos
- Si un tenant falla, los demás continuarán
- Todos los logs se muestran en la consola

## 🆘 Si algo sale mal

```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Ejecutar migraciones manualmente para un tenant específico
php artisan tenant:migrate-direct {slug}

# Rollback si es necesario
git reset --hard HEAD~1
php artisan migrate:rollback --path=database/migrations/tenant
```

---

**¿Necesitas ayuda?** Revisa `DEPLOYMENT.md` para documentación completa.
