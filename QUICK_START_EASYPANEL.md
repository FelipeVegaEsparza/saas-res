# Guía Rápida de Despliegue en Easypanel

## Resumen de 5 Pasos

### 1️⃣ Subir a GitHub

```bash
# Inicializar git (si no está inicializado)
git init
git add .
git commit -m "Initial commit"

# Crear repositorio en GitHub y conectar
git remote add origin https://github.com/tu-usuario/tu-repo.git
git branch -M main
git push -u origin main
```

### 2️⃣ Crear Proyecto en Easypanel

1. Accede a tu Easypanel
2. Click en **"Create Project"**
3. Nombre: `restaurant-saas`
4. Click en **"Create"**

### 3️⃣ Agregar Servicio de Base de Datos

1. Dentro del proyecto, click en **"Add Service"**
2. Selecciona **"MySQL"**
3. Configuración:
   - Name: `mysql`
   - Version: `8.0`
   - Database: `restaurant_saas`
   - Root Password: (genera una contraseña segura y guárdala)
4. Click en **"Create"**

### 4️⃣ Agregar Servicio de Aplicación

1. Click en **"Add Service"** nuevamente
2. Selecciona **"App"**
3. Configuración básica:

   - Name: `app`
   - Source: **"GitHub"**
   - Repository: Selecciona tu repositorio
   - Branch: `main`
   - Build Type: **"Dockerfile"**

4. **Variables de Entorno** (click en "Environment"):

   ```
   APP_NAME=Restaurant SaaS
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tudominio.com

   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=restaurant_saas
   DB_USERNAME=root
   DB_PASSWORD=TU_PASSWORD_MYSQL_DEL_PASO_3

   SESSION_DRIVER=file
   CACHE_DRIVER=file
   FILESYSTEM_DISK=public
   ```

5. **Generar APP_KEY**:

   - Ejecuta localmente: `php artisan key:generate --show`
   - Copia el resultado y agrégalo como variable: `APP_KEY=base64:...`

6. **Configurar Puerto**:

   - Port: `80`

7. **Volúmenes** (para persistir archivos):

   - Click en "Mounts"
   - Agregar: `/var/www/storage` → Crear nuevo volumen `storage`
   - Agregar: `/var/www/public/storage` → Crear nuevo volumen `public-storage`

8. Click en **"Create"**

### 5️⃣ Configurar Dominio y Desplegar

1. En el servicio `app`, ve a **"Domains"**
2. Agrega tu dominio (ej: `restaurant.tudominio.com`)
3. Easypanel configurará SSL automáticamente

4. Click en **"Deploy"** (botón verde)
5. Espera 5-10 minutos mientras se construye

6. Una vez desplegado, accede a la **Terminal** del servicio:

   ```bash
   # Ejecutar migraciones
   php artisan migrate --force

   # Crear enlace de storage
   php artisan storage:link

   # Optimizar
   php artisan config:cache
   php artisan route:cache

   # Crear tenant de prueba
   php artisan tenant:create demo "Demo Restaurant" admin@demo.com
   ```

## ✅ ¡Listo!

Accede a: `https://tudominio.com/demo`

- Email: `admin@demo.com`
- Password: `password`

## 🔧 Comandos Útiles

### Ver logs en tiempo real

En Easypanel → Servicio `app` → Logs

### Ejecutar comandos

En Easypanel → Servicio `app` → Terminal

### Actualizar aplicación

```bash
# En tu computadora
git add .
git commit -m "Actualización"
git push

# En Easypanel
Click en "Redeploy"
```

## 🆘 Problemas Comunes

### Error: "No application encryption key"

```bash
# Genera una key localmente
php artisan key:generate --show

# Agrégala en Variables de Entorno en Easypanel
APP_KEY=base64:tu-key-aqui
```

### Error: "Connection refused" (Base de datos)

- Verifica que el servicio MySQL esté corriendo (verde)
- Verifica que `DB_HOST=mysql` (nombre del servicio)
- Verifica la contraseña en las variables de entorno

### Assets no cargan (CSS/JS)

```bash
# En la terminal del contenedor
npm run build
php artisan storage:link
```

### Permisos de storage

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📊 Monitoreo

En Easypanel puedes ver:

- CPU y RAM en tiempo real
- Logs de la aplicación
- Logs de la base de datos
- Métricas de tráfico

## 🔐 Seguridad Post-Despliegue

1. Cambia la contraseña del usuario admin
2. Configura backups automáticos (Easypanel → MySQL → Backups)
3. Revisa que `APP_DEBUG=false`
4. Configura SMTP para emails (opcional)

## 💰 Costos

- VPS con Easypanel: $5-10/mes
- Dominio: $10-15/año
- **Total: ~$7-12/mes**

## 📚 Recursos

- [Documentación Easypanel](https://easypanel.io/docs)
- [Documentación Laravel](https://laravel.com/docs)
- Archivo completo: `DEPLOYMENT_EASYPANEL.md`
