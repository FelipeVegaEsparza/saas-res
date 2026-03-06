#!/bin/bash

# Script de instalación del sistema de despliegue automático
# Ejecutar una sola vez en el servidor de producción

echo "=========================================="
echo "📦 Instalando sistema de despliegue automático"
echo "=========================================="
echo ""

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

print_step() {
    echo -e "${BLUE}▶ $1${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

# 1. Dar permisos de ejecución
print_step "Configurando permisos de ejecución..."
chmod +x deploy.sh
chmod +x .git-hooks/post-merge
print_success "Permisos configurados"
echo ""

# 2. Instalar hook de Git
print_step "Instalando hook de Git..."
if [ -f ".git/hooks/post-merge" ]; then
    echo "⚠️  Ya existe un hook post-merge, creando backup..."
    mv .git/hooks/post-merge .git/hooks/post-merge.backup
fi

cp .git-hooks/post-merge .git/hooks/post-merge
chmod +x .git/hooks/post-merge
print_success "Hook de Git instalado"
echo ""

# 3. Verificar instalación
print_step "Verificando instalación..."
if [ -x "deploy.sh" ] && [ -x ".git/hooks/post-merge" ]; then
    print_success "Instalación completada correctamente"
else
    echo "❌ Error en la instalación"
    exit 1
fi
echo ""

echo "=========================================="
echo -e "${GREEN}✓ Sistema de despliegue instalado${NC}"
echo "=========================================="
echo ""
echo "Ahora cada vez que hagas 'git pull' se ejecutará automáticamente:"
echo "  1. Instalación de dependencias"
echo "  2. Migraciones de base de datos central"
echo "  3. Migraciones de TODOS los tenants"
echo "  4. Optimización de cachés"
echo ""
echo "También puedes ejecutar manualmente: ./deploy.sh"
echo ""
echo "Para más información, lee: DEPLOYMENT.md"
echo ""
