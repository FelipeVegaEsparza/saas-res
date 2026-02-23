#!/bin/sh

# Esperar a que la base de datos esté lista
echo "Esperando a que la base de datos esté lista..."
sleep 10

# Verificar configuración
echo "Verificando configuración..."
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "DB_HOST: $DB_HOST"
echo "DB_DATABASE: $DB_DATABASE"

# Asegurar permisos correctos
echo "Configurando permisos..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Crear directorios necesarios
mkdir -p /var/www/html/storage/app/public/logos
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Asegurar permisos después de crear directorios
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Ejecutar migraciones de landlord primero
echo "Ejecutando migraciones de landlord..."
php artisan migrate --path=database/migrations/landlord --force

# Ejecutar migraciones generales
echo "Ejecutando migraciones generales..."
php artisan migrate --force

# Ejecutar seeders
echo "Ejecutando seeders..."
php artisan db:seed --class=SystemSettingsSeeder --force
php artisan db:seed --class=AdminSeeder --force

# Limpiar caché
echo "Limpiando caché..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico de storage
php artisan storage:link

# Crear directorio de logs para supervisor
mkdir -p /var/log/supervisor

# Iniciar supervisor
echo "Iniciando servicios..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
