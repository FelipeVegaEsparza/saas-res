#!/bin/sh

# Esperar a que la base de datos esté lista
echo "Esperando a que la base de datos esté lista..."
sleep 10

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
