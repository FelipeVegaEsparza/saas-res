# Guía de Despliegue en Easypanel

## Requisitos Previos

- VPS con Easypanel instalado
- Acceso a Easypanel (panel de administración)
- Repositorio Git (GitHub, GitLab, o Bitbucket)

## Paso 1: Preparar el Proyecto para Producción

### 1.1 Crear archivo `.env.production`

Crea un archivo de ejemplo para producción:

```env
APP_NAME="Restaurant SaaS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tudominio.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=restaurant_saas
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### 1.2 Crear Dockerfile

```dockerfile
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . /var/www

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de Node y compilar assets
RUN npm install
RUN npm run build

# Configurar permisos
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap/cache

# Copiar configuración de Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copiar configuración de Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

### 1.3 Crear configuración de Nginx

Crea el directorio `docker/` y el archivo `docker/nginx.conf`:

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 10M;
}
```

### 1.4 Crear configuración de Supervisor

Crea el archivo `docker/supervisord.conf`:

```ini
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm
autostart=true
autorestart=true
priority=5
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
priority=10
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```

### 1.5 Crear archivo `.dockerignore`

```
.git
.env
.env.*
node_modules
vendor
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
bootstrap/cache/*
.phpunit.result.cache
```

## Paso 2: Subir el Proyecto a GitHub

### 2.1 Inicializar Git (si no está inicializado)

```bash
git init
git add .
git commit -m "Initial commit"
```

### 2.2 Crear repositorio en GitHub

1. Ve a https://github.com/new
2. Crea un nuevo repositorio (puede ser privado)
3. No inicialices con README, .gitignore o licencia

### 2.3 Conectar y subir

```bash
git remote add origin https://github.com/tu-usuario/tu-repositorio.git
git branch -M main
git push -u origin main
```

## Paso 3: Configurar en Easypanel

### 3.1 Crear Nuevo Proyecto

1. Accede a tu panel de Easypanel
2. Click en "Create Project"
3. Selecciona "From Git Repository"

### 3.2 Configurar Repositorio

1. Conecta tu cuenta de GitHub
2. Selecciona el repositorio del proyecto
3. Selecciona la rama `main`

### 3.3 Configurar Servicio de Aplicación

**Configuración del Servicio:**

- **Name**: restaurant-app
- **Type**: Application
- **Build Method**: Dockerfile
- **Port**: 80

### 3.4 Configurar Base de Datos MySQL

1. En el mismo proyecto, click en "Add Service"
2. Selecciona "MySQL"
3. Configuración:
   - **Name**: mysql
   - **Version**: 8.0
   - **Database Name**: restaurant_saas
   - **Root Password**: (genera una contraseña segura)

### 3.5 Configurar Variables de Entorno

En la sección "Environment Variables" de tu aplicación:

```
APP_NAME=Restaurant SaaS
APP_ENV=production
APP_KEY=base64:GENERA_UNA_KEY_AQUI
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=restaurant_saas
DB_USERNAME=root
DB_PASSWORD=TU_PASSWORD_MYSQL

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

FILESYSTEM_DISK=public
```

**Generar APP_KEY:**
Ejecuta localmente: `php artisan key:generate --show`

### 3.6 Configurar Volúmenes (Storage)

Agrega volúmenes para persistir datos:

1. **Storage**: `/var/www/storage` → Volumen persistente
2. **Public Storage**: `/var/www/public/storage` → Volumen persistente

### 3.7 Configurar Dominio

1. En la sección "Domains"
2. Agrega tu dominio personalizado
3. Easypanel configurará automáticamente SSL con Let's Encrypt

## Paso 4: Desplegar

### 4.1 Iniciar Despliegue

1. Click en "Deploy"
2. Easypanel construirá la imagen Docker
3. Espera a que el despliegue termine (puede tomar 5-10 minutos)

### 4.2 Ejecutar Migraciones

Una vez desplegado, accede a la terminal del contenedor:

```bash
# Ejecutar migraciones
php artisan migrate --force

# Crear enlace simbólico de storage
php artisan storage:link

# Limpiar y optimizar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4.3 Crear Tenant de Prueba

```bash
php artisan tenant:create demo "Demo Restaurant" admin@demo.com
```

## Paso 5: Configuración Post-Despliegue

### 5.1 Configurar Cron Jobs (Opcional)

Si necesitas tareas programadas, agrega un servicio de Worker:

1. Crea un nuevo servicio tipo "Worker"
2. Comando: `php artisan schedule:work`

### 5.2 Configurar Queue Worker (Opcional)

Si usas colas:

1. Cambia `QUEUE_CONNECTION=database` en variables de entorno
2. Crea un servicio Worker con comando: `php artisan queue:work --tries=3`

### 5.3 Backup de Base de Datos

Configura backups automáticos en Easypanel:

1. Ve a tu servicio MySQL
2. Sección "Backups"
3. Configura backup diario

## Paso 6: Monitoreo y Logs

### 6.1 Ver Logs

En Easypanel:

1. Selecciona tu aplicación
2. Click en "Logs"
3. Puedes ver logs en tiempo real

### 6.2 Monitoreo de Recursos

Easypanel muestra:

- Uso de CPU
- Uso de RAM
- Uso de disco
- Tráfico de red

## Troubleshooting

### Error: "Permission denied" en storage

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error: "No application encryption key"

Genera una nueva key:

```bash
php artisan key:generate
```

### Error de conexión a base de datos

Verifica:

1. Que el servicio MySQL esté corriendo
2. Las credenciales en variables de entorno
3. El nombre del host (debe ser el nombre del servicio MySQL)

### Assets no se cargan

```bash
npm run build
php artisan storage:link
```

## Actualizaciones Futuras

Para actualizar la aplicación:

1. Haz push de cambios a GitHub:

```bash
git add .
git commit -m "Descripción de cambios"
git push
```

2. En Easypanel, click en "Redeploy"
3. Ejecuta migraciones si es necesario

## Seguridad

### Recomendaciones:

1. **Nunca** subas el archivo `.env` a Git
2. Usa contraseñas fuertes para la base de datos
3. Mantén `APP_DEBUG=false` en producción
4. Configura SSL/HTTPS (Easypanel lo hace automáticamente)
5. Actualiza regularmente las dependencias
6. Configura backups automáticos

## Costos Estimados

Con un VPS básico:

- **VPS**: $5-10/mes (DigitalOcean, Hetzner, etc.)
- **Dominio**: $10-15/año
- **Total**: ~$7-12/mes

## Soporte

Si encuentras problemas:

1. Revisa los logs en Easypanel
2. Verifica las variables de entorno
3. Asegúrate de que todos los servicios estén corriendo
4. Consulta la documentación de Easypanel: https://easypanel.io/docs
