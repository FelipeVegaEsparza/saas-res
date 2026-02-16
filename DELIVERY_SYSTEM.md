# Sistema de Delivery - Documentación

## Descripción General

Se ha implementado un sistema completo de gestión de pedidos de delivery y para llevar (takeaway) integrado con el sistema multi-tenant del restaurante.

## Características Implementadas

### 1. Tipos de Pedidos

- **Delivery**: Pedidos con entrega a domicilio
  - Incluye dirección de entrega
  - Zona de entrega
  - Costo de envío configurable
- **Para Llevar (Takeaway)**: Pedidos para recoger en el local
  - Sin dirección de entrega
  - Sin costo de envío

### 2. Estados del Pedido

El sistema maneja los siguientes estados:

1. **Pendiente** (pending): Pedido recibido, esperando confirmación
2. **Confirmado** (confirmed): Pedido confirmado por el restaurante
3. **Preparando** (preparing): Pedido en preparación
4. **Listo** (ready): Pedido listo para entrega/retiro
5. **En Camino** (on_delivery): Solo para delivery, pedido en ruta
6. **Entregado** (delivered): Pedido completado
7. **Cancelado** (cancelled): Pedido cancelado

### 3. Información del Cliente

Cada pedido registra:

- Nombre completo
- Teléfono (obligatorio)
- Email (opcional)
- Dirección de entrega (obligatorio para delivery)
- Zona de entrega (opcional)

### 4. Gestión de Productos

- Selección múltiple de productos
- Cantidad por producto
- Notas especiales por producto (ej: "sin cebolla", "extra queso")
- Cálculo automático de subtotales

### 5. Cálculos Financieros

- **Subtotal**: Suma de todos los productos
- **Costo de Envío**: Solo para pedidos delivery
- **Total**: Subtotal + Costo de Envío

### 6. Interfaz de Usuario

#### Vista Index (Lista de Pedidos)

- Tabla con todos los pedidos
- Filtros por:
  - Tipo (delivery/takeaway)
  - Estado
  - Búsqueda por número de pedido, cliente o teléfono
- Badges de colores para estados
- Iconos diferenciados por tipo de pedido

#### Vista Create (Crear Pedido)

- Formulario dinámico con JavaScript
- Selección de tipo de pedido con radio buttons estilizados
- Agregar/quitar productos dinámicamente
- Cálculo en tiempo real de totales
- Resumen del pedido en sidebar sticky
- Validaciones en frontend y backend

#### Vista Show (Detalle del Pedido)

- Información completa del pedido
- Lista de productos con cantidades y precios
- Timeline visual del historial del pedido
- Formulario para cambiar estado
- Desglose de costos (subtotal, envío, total)

### 7. Dashboard Integrado

El dashboard principal ahora incluye:

- Estadística de pedidos delivery del día
- Ingresos de delivery del día
- Pedidos delivery pendientes
- Tabla de pedidos delivery recientes
- Acceso rápido a la sección de delivery

### 8. Base de Datos

#### Tabla: delivery_orders

```sql
- id
- order_number (único, formato: DEL20260214XXXX)
- type (delivery/takeaway)
- status
- customer_name
- customer_phone
- customer_email
- delivery_address
- delivery_zone
- delivery_fee
- subtotal
- total
- notes
- confirmed_at
- ready_at
- delivered_at
- timestamps
- soft_deletes
```

#### Tabla: delivery_order_items

```sql
- id
- delivery_order_id
- product_id
- quantity
- price
- subtotal
- notes
- timestamps
```

### 9. Modelos

#### DeliveryOrder

- Relación con DeliveryOrderItem
- Generación automática de número de pedido
- Accessors para labels y colores de estado
- Accessor para tipo de pedido en español

#### DeliveryOrderItem

- Relación con DeliveryOrder
- Relación con Product

### 10. Rutas

Todas las rutas usan el prefijo path-based para desarrollo:

```php
GET    /demo/delivery              - Lista de pedidos
GET    /demo/delivery/create       - Formulario crear pedido
POST   /demo/delivery              - Guardar pedido
GET    /demo/delivery/{id}         - Ver detalle
POST   /demo/delivery/{id}/status  - Actualizar estado
DELETE /demo/delivery/{id}         - Eliminar pedido
```

### 11. Diseño y UX

- **Template**: Materialize (consistente con el resto del sistema)
- **Iconos**: Remix Icons
- **Colores**: Sistema de badges del template
- **Responsive**: Adaptado a móviles y tablets
- **Modo Oscuro**: Totalmente compatible
- **Componentes**: Cards, forms, tables, badges, buttons del template

### 12. Validaciones

#### Backend (Laravel)

- Tipo de pedido requerido
- Información del cliente obligatoria
- Dirección requerida solo para delivery
- Al menos un producto en el pedido
- Productos deben existir en la base de datos
- Cantidades mínimas de 1

#### Frontend (JavaScript)

- Cálculo automático de totales
- Validación de campos requeridos
- Prevención de envío sin productos

## Uso del Sistema

### Crear un Pedido

1. Ir a **Delivery** en el menú lateral
2. Click en **Nuevo Pedido**
3. Seleccionar tipo (Delivery o Para Llevar)
4. Completar información del cliente
5. Si es delivery, completar dirección y costo de envío
6. Agregar productos con el botón **Agregar Producto**
7. Seleccionar producto, cantidad y notas opcionales
8. Revisar el resumen en el sidebar
9. Click en **Crear Pedido**

### Gestionar Estados

1. Abrir el detalle del pedido
2. En el sidebar derecho, seleccionar nuevo estado
3. Click en **Actualizar Estado**
4. El timeline se actualiza automáticamente

### Filtrar Pedidos

1. En la lista de pedidos, usar los filtros superiores
2. Buscar por número, cliente o teléfono
3. Filtrar por tipo o estado
4. Click en **Buscar**

## Próximas Mejoras Sugeridas

1. **Notificaciones**: SMS o WhatsApp al cliente
2. **Impresión**: Ticket de pedido para cocina
3. **Mapa**: Integración con Google Maps para rutas
4. **Estadísticas**: Reportes de delivery por zona/horario
5. **Repartidores**: Asignación de pedidos a repartidores
6. **Tracking**: Seguimiento en tiempo real del pedido
7. **Pagos**: Integración con pasarelas de pago
8. **Propinas**: Sistema de propinas para repartidores

## Archivos Creados/Modificados

### Nuevos Archivos

- `database/migrations/tenant/2026_02_14_000009_create_delivery_orders_table.php`
- `database/migrations/tenant/2026_02_14_000010_create_delivery_order_items_table.php`
- `app/Models/Tenant/DeliveryOrder.php`
- `app/Models/Tenant/DeliveryOrderItem.php`
- `app/Http/Controllers/Tenant/DeliveryOrderController.php`
- `resources/views/tenant/delivery/index.blade.php`
- `resources/views/tenant/delivery/create.blade.php`
- `resources/views/tenant/delivery/show.blade.php`

### Archivos Modificados

- `routes/web.php` - Agregadas rutas de delivery
- `resources/views/tenant/layouts/admin.blade.php` - Agregado menú Delivery
- `app/Http/Controllers/Tenant/DashboardController.php` - Agregadas estadísticas
- `resources/views/tenant/dashboard/index.blade.php` - Agregada sección delivery

## Acceso

**URL**: `http://localhost:8000/demo/delivery`

**Credenciales de prueba**:

- Email: admin@demo.com
- Password: demo123
