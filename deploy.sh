#!/bin/bash

# Script de despliegue automático para producción
# Este script se ejecuta automáticamente después de un git pull o push

set -e  # Detener en caso de error

echo "=========================================="
echo "🚀 Iniciando despliegue a producción"
echo "=========================================="
echo ""

# Colores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para imprimir con color
print_step() {
    echo -e "${BLUE}▶ $1${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# 1. Modo mantenimiento
print_step "Activando modo mantenimiento..."
php artisan down --retry=60 || true
print_success "Modo mantenimiento activado"
echo ""

# 2. Actualizar código (si se ejecuta manualmente)
if [ "$1" != "--skip-pull" ]; then
    print_step "Actualizando código desde repositorio..."
    git pull origin main
    print_success "Código actualizado"
    echo ""
fi

# 3. Instalar/actualizar dependencias de Composer
print_step "Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader --no-interaction
print_success "Dependencias de Composer instaladas"
echo ""

# 4. Instalar/actualizar dependencias de NPM (si es necesario)
if [ -f "package.json" ]; then
    print_step "Instalando dependencias de NPM..."
    npm ci --production
    print_success "Dependencias de NPM instaladas"
    echo ""

    print_step "Compilando assets..."
    npm run build
    print_success "Assets compilados"
    echo ""
fi

# 5. Limpiar cachés
print_step "Limpiando cachés..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_success "Cachés limpiados"
echo ""

# 6. Ejecutar migraciones de la base de datos central
print_step "Ejecutando migraciones de la base de datos central..."
php artisan migrate --force
print_success "Migraciones centrales ejecutadas"
echo ""

# 7. Ejecutar migraciones de TODOS los tenants
print_step "Ejecutando migraciones de todos los tenants..."
php artisan tenants:migrate-all
print_success "Migraciones de tenants ejecutadas"
echo ""

# 8. Optimizar aplicación
print_step "Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
print_success "Aplicación optimizada"
echo ""

# 9. Reiniciar servicios (si usas queue workers)
if command -v supervisorctl &> /dev/null; then
    print_step "Reiniciando queue workers..."
    supervisorctl restart all || print_warning "No se pudieron reiniciar los workers"
    echo ""
fi

# 10. Desactivar modo mantenimiento
print_step "Desactivando modo mantenimiento..."
php artisan up
print_success "Modo mantenimiento desactivado"
echo ""

echo "=========================================="
echo -e "${GREEN}✓ Despliegue completado exitosamente${NC}"
echo "=========================================="
echo ""
echo "Resumen:"
echo "  - Código actualizado"
echo "  - Dependencias instaladas"
echo "  - Migraciones ejecutadas (central + tenants)"
echo "  - Cachés optimizados"
echo "  - Aplicación en línea"
echo ""
