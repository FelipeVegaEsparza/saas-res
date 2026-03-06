# Sistema de Activación/Desactivación de Módulos

Sistema que permite a cada tenant activar o desactivar los módulos de Mesas y Delivery según las necesidades de su negocio.

## 📋 Características

- ✅ Activar/desactivar módulo de Mesas
- ✅ Activar/desactivar módulo de Delivery
- ✅ Configuración desde Settings
- ✅ Menú lateral dinámico (oculta módulos desactivados)
- ✅ Por defecto ambos módulos están activados

## 🎯 Casos de Uso

### Restaurante solo con Mesas

- Activa: Módulo de Mesas ✓
- Desactiva: Módulo de Delivery ✗
- Resultado: Solo ve Caja y Mesas en el menú

### Restaurante solo Delivery

- Desactiva: Módulo de Mesas ✗
- Activa: Módulo de Delivery ✓
- Resultado: Solo ve Caja y Delivery en el menú

### Restaurante Completo

- Activa: Módulo de Mesas ✓
- Activa: Módulo de Delivery ✓
- Resultado: Ve Caja, Mesas y Delivery en el menú

## 🔧 Configuración

### Para el Usuario

1. Ir a **Ajustes** (Settings)
2. Buscar la sección **"Módulos del Sistema"**
3. Activar/desactivar los switches según necesidad:
   - **Módulo de Mesas**: Gestión de mesas y pedidos en local
   - **Módulo de Delivery**: Pedidos para llevar y delivery
4. Guardar cambios

### Comportamiento

- Al desactivar un módulo:

  - Desaparece del menú lateral
  - No se puede acceder a sus rutas
  - Se puede reactivar en cualquier momento

- Al activar un módulo:
  - Aparece en el menú lateral
  - Se puede acceder a todas sus funciones

## 📁 Archivos Modificados

### Migración

- `database/migrations/2026_03_06_144252_add_module_settings_to_restaurants_table.php`
  - Agrega campos: `module_tables_enabled`, `module_delivery_enabled`

### Modelo

- `app/Models/Restaurant.php`
  - Agrega campos al `$fillable` y `$casts`

### Controlador

- `app/Http/Controllers/Tenant/SettingsController.php`
  - Actualiza validación y guardado de módulos

### Vistas

- `resources/views/tenant/settings/index.blade.php`
  - Agrega sección "Módulos del Sistema"
- `resources/views/tenant/layouts/admin.blade.php`
  - Menú lateral dinámico según módulos activos

## 🚀 Despliegue

### En Desarrollo

```bash
php artisan migrate
```

### En Producción

```bash
# La migración se ejecutará automáticamente con el sistema de despliegue
git push origin main
# En el servidor, el deploy.sh ejecutará las migraciones
```

## 💾 Base de Datos

### Campos Agregados a `restaurants`

| Campo                     | Tipo    | Default | Descripción                         |
| ------------------------- | ------- | ------- | ----------------------------------- |
| `module_tables_enabled`   | boolean | true    | Activa/desactiva módulo de Mesas    |
| `module_delivery_enabled` | boolean | true    | Activa/desactiva módulo de Delivery |

## 🎨 Interfaz

La sección en Settings muestra:

- Cards visuales para cada módulo
- Switch para activar/desactivar
- Lista de funcionalidades incluidas
- Alerta informativa sobre el comportamiento

## ⚠️ Notas Importantes

1. **Por defecto ambos módulos están activados** para no afectar tenants existentes
2. **La sección "Punto de Venta" se oculta** si ambos módulos están desactivados
3. **Caja siempre está visible** independiente de los módulos
4. **Los cambios son inmediatos** al guardar en Settings

## 🔄 Migración de Tenants Existentes

Los tenants existentes tendrán ambos módulos activados por defecto (true), por lo que no verán cambios hasta que decidan desactivar alguno.

## 📝 Ejemplo de Uso

```php
// Obtener configuración del restaurante
$restaurant = tenant()->restaurant();

// Verificar si módulo de mesas está activo
if ($restaurant->module_tables_enabled) {
    // Mostrar funcionalidad de mesas
}

// Verificar si módulo de delivery está activo
if ($restaurant->module_delivery_enabled) {
    // Mostrar funcionalidad de delivery
}
```

## ✨ Beneficios

- 🎯 Interfaz más limpia y enfocada
- 🚀 Mejor experiencia de usuario
- 📊 Cada cliente ve solo lo que necesita
- 🔧 Fácil de configurar
- 🔄 Reversible en cualquier momento
