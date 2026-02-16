#!/bin/bash

# Script de inicialización para el primer despliegue
# Este script se ejecuta después del primer despliegue en Easypanel

echo "🚀 Iniciando configuración del proyecto..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando a que MySQL esté listo..."
sleep 10

# Ejecutar migraciones
echo "📦 Ejecutando migraciones..."
php artisan migrate --force

# Crear enlace simbólico de storage
echo "🔗 Creando enlace simbólico de storage..."
php artisan storage:link

# Optimizar aplicación
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear tenant de demostración (opcional)
echo "🏪 ¿Deseas crear un tenant de demostración? (s/n)"
read -r response
if [[ "$response" =~ ^([sS][iI]|[sS])$ ]]; then
    echo "Creando tenant de demostración..."
    php artisan tenant:create demo "Demo Restaurant" admin@demo.com
    echo "✅ Tenant creado: demo"
    echo "   URL: https://tudominio.com/demo"
    echo "   Email: admin@demo.com"
    echo "   Password: password"
fi

echo "✅ Configuración completada!"
echo ""
echo "📝 Próximos pasos:"
echo "1. Accede a tu aplicación en: ${APP_URL}"
echo "2. Cambia las contraseñas por defecto"
echo "3. Configura el correo electrónico en las variables de entorno"
echo "4. Revisa la configuración en /settings"
