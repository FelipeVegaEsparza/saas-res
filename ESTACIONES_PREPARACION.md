# Sistema de Estaciones de Preparación (KDS Virtual)

## Descripción General

Sistema completo de gestión de estaciones de preparación que permite organizar el flujo de trabajo en la cocina y áreas de preparación del restaurante. Funciona como un KDS (Kitchen Display System) virtual sin necesidad de impresoras asignadas.

## Características Principales

### 1. Gestión de Estaciones

- **CRUD Completo**: Crear, editar y eliminar estaciones de preparación
- **Configuración por Estación**:
  - Nombre personalizado (Cocina, Barra, Postres, etc.)
  - Color de identificación visual
  - Icono representativo
  - Orden de aparición en el menú
  - Estado activo/inactivo

### 2. Asignación de Productos

- **Obligatorio**: Cada producto DEBE tener una estación asignada
- **Exclusivo**: Un producto pertenece a UNA sola estación
- **Selector en Formulario**: Campo obligatorio en creación/edición de productos

### 3. Impresión de Comandas por Estación

#### Desde Vista de Mesas:

- **Botón "Imprimir Comanda Completa"**: Imprime todos los productos del pedido
- **Botones por Estación**: Un botón por cada estación que tenga productos en el pedido
- **Filtrado Automático**: Solo muestra botones de estaciones con productos en ese pedido específico
- **Formato Térmico 80mm**: Optimizado para impresoras térmicas estándar

#### Desde Vista de Delivery:

- **Botón "Imprimir Comanda Completa"**: Imprime todos los productos del pedido
- **Botones por Estación**: Un botón por cada estación que tenga productos en el pedido
- **Información del Cliente**: Incluye nombre, teléfono y dirección (si es delivery)
- **Formato Térmico 80mm**: Optimizado para impresoras térmicas estándar

### 4. Diseño de Comandas por Estación

#### Elementos Visuales:

- **Header con Nombre de Estación**: Destacado en negro con fondo invertido
- **Información del Pedido**: Número, fecha, hora, mesero (si aplica)
- **Tipo de Pedido**: Mesa, Delivery o Para Llevar
- **Datos del Cliente**: Solo en comandas de delivery/para llevar
- **Items Filtrados**: Solo productos de esa estación específica
- **Notas**: Notas del pedido y notas por producto
- **Márgenes Mejorados**: Padding 8mm 6mm, line-height 1.5

## Estructura de Archivos

### Migraciones

```
database/migrations/2026_02_24_205742_create_preparation_areas_table.php
database/migrations/2026_02_24_205807_add_preparation_area_id_to_products_table.php
```

### Modelos

```
app/Models/PreparationArea.php
app/Models/Tenant/Product.php (actualizado con relación)
```

### Controladores

```
app/Http/Controllers/Tenant/PreparationAreaController.php
app/Http/Controllers/Tenant/ProductController.php (actualizado)
app/Http/Controllers/Tenant/TableController.php (métodos de impresión)
app/Http/Controllers/Tenant/DeliveryOrderController.php (métodos de impresión)
```

### Vistas

#### CRUD de Estaciones:

```
resources/views/tenant/preparation-areas/index.blade.php
resources/views/tenant/preparation-areas/create.blade.php
resources/views/tenant/preparation-areas/edit.blade.php
```

#### Formularios de Productos (actualizados):

```
resources/views/tenant/products/create.blade.php
resources/views/tenant/products/edit.blade.php
```

#### Vistas de Impresión por Estación:

```
resources/views/tenant/tables/print-comanda-area.blade.php
resources/views/tenant/delivery/print-comanda-area.blade.php
```

#### Vistas de Pedidos (actualizadas):

```
resources/views/tenant/tables/show-order.blade.php
resources/views/tenant/delivery/show.blade.php
```

### Rutas

```php
// CRUD Estaciones
Route::resource('preparation-areas', PreparationAreaController::class)

// Impresión por Estación - Mesas
Route::get('tables/{table_id}/print-comanda/{area_id}', [TableController::class, 'printComandaByArea'])

// Impresión por Estación - Delivery
Route::get('delivery/{deliveryOrder}/print-comanda/{area_id}', [DeliveryOrderController::class, 'printComandaByArea'])
```

## Flujo de Trabajo

### 1. Configuración Inicial

1. Ir a **Configuración > Estaciones**
2. Crear las estaciones necesarias (Cocina, Barra, Postres, etc.)
3. Configurar nombre, color, icono y orden
4. Activar las estaciones

### 2. Asignación de Productos

1. Al crear/editar un producto
2. Seleccionar la estación de preparación (campo obligatorio)
3. Guardar el producto

### 3. Uso en Operación

#### Desde Mesas:

1. Tomar pedido en una mesa
2. En la vista del pedido, ir a sección "Acciones"
3. Opciones disponibles:
   - **Imprimir Comanda Completa**: Todos los productos
   - **Botones por Estación**: Solo productos de esa estación
4. Hacer clic en el botón deseado
5. Se abre ventana de impresión automáticamente

#### Desde Delivery:

1. Recibir pedido online o crear pedido delivery/para llevar
2. En la vista del pedido, ir a sección "Estado del Pedido"
3. Opciones disponibles:
   - **Imprimir Comanda Completa**: Todos los productos
   - **Botones por Estación**: Solo productos de esa estación
4. Hacer clic en el botón deseado
5. Se abre ventana de impresión automáticamente

## Validaciones y Reglas

### Estaciones:

- ✅ Nombre obligatorio
- ✅ Color obligatorio (formato hexadecimal)
- ✅ Icono obligatorio (selección de lista predefinida)
- ✅ No se puede eliminar una estación con productos asignados
- ✅ Orden automático si no se especifica

### Productos:

- ✅ Estación de preparación obligatoria
- ✅ Solo una estación por producto
- ✅ Validación en formulario y backend

### Impresión:

- ✅ Solo muestra botones de estaciones con productos en el pedido
- ✅ Filtra automáticamente items por estación
- ✅ Mensaje de error si no hay productos de esa estación
- ✅ Auto-impresión al abrir ventana

## Iconos Disponibles

- 🍳 Cocina: `ri-restaurant-2-line`
- 🍹 Barra: `ri-goblet-line`
- 🍰 Postres: `ri-cake-3-line`
- 🥗 Ensaladas: `ri-bowl-line`
- 🔥 Parrilla: `ri-fire-line`
- ☕ Cafetería: `ri-cup-line`
- 🔪 Preparación: `ri-knife-line`

## Próximas Funcionalidades (Pendientes)

### KDS Virtual - Vistas por Estación:

- [ ] Controlador `KitchenDisplayController`
- [ ] Vista KDS por estación para Mesas
- [ ] Vista KDS por estación para Delivery
- [ ] Estados: Pendiente, En Preparación, Entregado
- [ ] Actualización en tiempo real
- [ ] Menú lateral dinámico con entradas por estación

### Características Adicionales:

- [ ] Notificaciones sonoras por estación
- [ ] Tiempo promedio de preparación por estación
- [ ] Estadísticas de rendimiento por estación
- [ ] Priorización de pedidos
- [ ] Integración con pantallas dedicadas

## Notas Técnicas

### Base de Datos:

- Tabla `preparation_areas` en base de datos central
- Campo `preparation_area_id` en tabla `products` (tenant)
- Relación: `Product belongsTo PreparationArea`
- Relación: `PreparationArea hasMany Product`

### Scopes Disponibles:

```php
PreparationArea::active() // Solo estaciones activas
PreparationArea::ordered() // Ordenadas por campo 'order' y 'name'
```

### Helpers:

```php
$area->products() // Obtener productos de la estación
$product->preparationArea // Obtener estación del producto
```

## Solución de Problemas

### No aparecen botones de estación en pedido:

- Verificar que los productos tengan estación asignada
- Verificar que las estaciones estén activas
- Verificar que el pedido tenga items

### Error al imprimir comanda por estación:

- Verificar que la estación exista
- Verificar que haya productos de esa estación en el pedido
- Verificar permisos de impresión del navegador

### No se puede eliminar estación:

- Verificar que no tenga productos asignados
- Reasignar productos a otra estación primero

## Comandos Útiles

```bash
# Ejecutar migraciones en tenant específico
php artisan tenant:migrate-direct {slug}

# Ver estado de migraciones
php artisan tenant:status {slug}

# Crear estación de ejemplo (desde tinker)
php artisan tinker
PreparationArea::create([
    'name' => 'Cocina Principal',
    'color' => '#667eea',
    'icon' => 'ri-restaurant-2-line',
    'order' => 1,
    'active' => true
]);
```

## Compatibilidad

- ✅ Impresoras térmicas 80mm estándar
- ✅ Navegadores modernos (Chrome, Firefox, Edge, Safari)
- ✅ Dispositivos móviles y tablets
- ✅ Sistema multi-tenant con path-based tenancy
- ✅ Compatible con sistema de notificaciones existente

## Autor y Fecha

- **Implementado**: Febrero 2026
- **Versión**: 1.0
- **Sistema**: Restaurant Management System
