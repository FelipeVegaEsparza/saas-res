# Sistema de Impresión de Comandas - Impresoras Térmicas 80mm

## Descripción

Sistema de impresión de comandas optimizado para impresoras térmicas de 80mm, disponible tanto para pedidos de mesas como para pedidos de delivery/para llevar.

## Características

### 1. Formato Optimizado para Impresoras Térmicas

- **Ancho**: 80mm (estándar para impresoras térmicas)
- **Fuente**: Courier New (monoespaciada para mejor alineación)
- **Tamaño de fuente**: 12px base
- **Márgenes**: 5mm
- **Auto-impresión**: Se abre el diálogo de impresión automáticamente

### 2. Comandas para Mesas

#### Información Incluida:

- Nombre del restaurante
- Título "COMANDA DE COCINA"
- Número de mesa
- Número de pedido
- Nombre del mesero
- Fecha y hora del pedido
- Lista de productos con cantidades
- Notas especiales de cada producto
- Notas generales para cocina
- Hora de impresión

#### Diseño:

```
================================
    NOMBRE DEL RESTAURANTE
    COMANDA DE COCINA
================================
Mesa: 5
Pedido: ORD-001
Mesero: Juan Pérez
Hora: 24/02/2026 14:30
--------------------------------
PRODUCTOS
--------------------------------
3x  Hamburguesa Completa
    * Sin cebolla

2x  Papas Fritas

1x  Coca Cola 500ml
--------------------------------
NOTAS ESPECIALES:
Cliente alérgico a mariscos
================================
¡Gracias por su preferencia!
Impreso: 24/02/2026 14:35:20
```

### 3. Comandas para Delivery/Para Llevar

#### Información Incluida:

- Nombre del restaurante
- Título "COMANDA DE COCINA"
- Tipo de pedido (DELIVERY o PARA LLEVAR) destacado
- Número de pedido
- Fecha y hora
- Estado del pedido
- **Datos del cliente** (nombre, teléfono, email)
- **Dirección de entrega** (solo para delivery) - destacada
- Zona de entrega
- Lista de productos con cantidades
- Notas especiales de cada producto
- Notas generales del pedido
- Subtotal, costo de envío y total
- Hora de impresión

#### Diseño:

```
================================
    NOMBRE DEL RESTAURANTE
    COMANDA DE COCINA
    [🚴 DELIVERY]
================================
Pedido: WEB-ABC123
Hora: 24/02/2026 14:30
Estado: Confirmado
--------------------------------
DATOS DEL CLIENTE
Nombre: María González
Teléfono: +56912345678
Email: maria@email.com
--------------------------------
📍 DIRECCIÓN DE ENTREGA:
Av. Principal 123, Depto 45
Zona: Centro
================================
PRODUCTOS
--------------------------------
2x  Pizza Napolitana
    * Extra queso

1x  Bebida 1.5L
--------------------------------
NOTAS ESPECIALES:
Tocar timbre 2 veces
================================
Subtotal: $15.000
Envío: $2.000
TOTAL: $17.000
================================
¡Gracias por su preferencia!
Impreso: 24/02/2026 14:35:20
```

## Archivos Creados

### Vistas de Impresión

1. `resources/views/tenant/tables/print-comanda.blade.php`

   - Vista de impresión para pedidos de mesas
   - Formato optimizado para 80mm
   - Auto-impresión y auto-cierre

2. `resources/views/tenant/delivery/print-comanda.blade.php`
   - Vista de impresión para pedidos delivery/para llevar
   - Incluye información del cliente y dirección
   - Diferenciación visual por tipo de pedido

### Controladores

1. `app/Http/Controllers/Tenant/TableController.php`

   - Método `printComanda()` agregado
   - Carga el pedido activo de la mesa con sus items

2. `app/Http/Controllers/Tenant/DeliveryOrderController.php`
   - Método `printComanda()` agregado
   - Carga el pedido con sus items

### Rutas

1. `routes/web.php`
   - `GET /{tenant}/tables/{table_id}/print-comanda` - Imprimir comanda de mesa
   - `GET /{tenant}/delivery/{deliveryOrder}/print-comanda` - Imprimir comanda de delivery

### Vistas Modificadas

1. `resources/views/tenant/tables/show-order.blade.php`

   - Botón "Imprimir Comanda" agregado en el header

2. `resources/views/tenant/delivery/show.blade.php`
   - Botón "Imprimir Comanda" agregado en el header

## Uso

### Desde Vista de Mesa

1. Ir a la vista de detalle del pedido de una mesa
2. Hacer clic en el botón "Imprimir Comanda"
3. Se abre una nueva ventana con la comanda
4. El diálogo de impresión se abre automáticamente
5. Seleccionar la impresora térmica
6. Imprimir

### Desde Vista de Delivery

1. Ir a la vista de detalle del pedido de delivery
2. Hacer clic en el botón "Imprimir Comanda"
3. Se abre una nueva ventana con la comanda
4. El diálogo de impresión se abre automáticamente
5. Seleccionar la impresora térmica
6. Imprimir

## Configuración de Impresora

### Configuración Recomendada

- **Tipo**: Impresora térmica de 80mm
- **Orientación**: Vertical (Portrait)
- **Márgenes**: Ninguno o mínimos
- **Escala**: 100%
- **Tamaño de papel**: 80mm x Continuo

### Impresoras Compatibles

- Epson TM-T20
- Epson TM-T88
- Star TSP100
- Bixolon SRP-350
- Cualquier impresora térmica de 80mm con driver ESC/POS

### Configuración en Windows

1. Panel de Control > Dispositivos e Impresoras
2. Clic derecho en la impresora térmica > Preferencias de impresión
3. Configurar:
   - Tamaño de papel: 80mm x Continuo
   - Orientación: Vertical
   - Márgenes: 0mm
   - Calidad: Normal o Alta

### Configuración en Navegador

Al imprimir, configurar:

- Destino: Seleccionar impresora térmica
- Diseño: Vertical
- Márgenes: Ninguno
- Escala: 100%
- Opciones de fondo: Activadas (para ver bordes)

## Características Técnicas

### CSS Print Media Query

```css
@page {
  size: 80mm auto;
  margin: 0;
}

@media print {
  body {
    padding: 0;
  }
  .no-print {
    display: none;
  }
}
```

### JavaScript Auto-Print

```javascript
window.onload = function () {
  window.print();
  setTimeout(function () {
    window.close();
  }, 100);
};
```

### Elementos Visuales

- **Bordes**: Líneas punteadas y continuas para separación
- **Secciones**: Claramente delimitadas
- **Tipografía**: Monoespaciada para alineación perfecta
- **Destacados**: Fondos de color para información importante
- **Iconos**: Emojis para mejor identificación visual

## Ventajas

1. **Optimizado para Cocina**: Formato claro y fácil de leer
2. **Ahorro de Papel**: Formato compacto de 80mm
3. **Rápido**: Auto-impresión sin pasos adicionales
4. **Completo**: Toda la información necesaria
5. **Profesional**: Diseño limpio y organizado
6. **Diferenciado**: Comandas distintas para mesas y delivery
7. **Información del Cliente**: Datos completos para delivery
8. **Dirección Destacada**: Fácil identificación para repartidores

## Mejoras Futuras Posibles

- Impresión automática al confirmar pedido
- Múltiples copias (cocina, bar, caja)
- Códigos QR para tracking
- Impresión de tickets de pago
- Integración con impresoras de red
- Configuración de plantillas personalizadas
- Soporte para impresoras de 58mm
- Impresión de etiquetas para delivery
- Logo del restaurante en la comanda
- Códigos de barras para pedidos
