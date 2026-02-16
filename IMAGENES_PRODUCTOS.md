# Sistema de Subida de Imágenes para Productos

## Descripción

Se ha implementado la funcionalidad de subida de imágenes para productos en el sistema. Ahora puedes agregar imágenes directamente desde tu computadora al crear o editar productos.

## Características

- **Subida de archivos**: Sube imágenes desde tu dispositivo
- **Formatos soportados**: JPG, PNG, WEBP
- **Tamaño máximo**: 2MB por imagen
- **Almacenamiento**: Las imágenes se guardan en `storage/app/public/products`
- **Vista previa**: Al editar un producto, se muestra la imagen actual
- **Eliminación automática**: Al eliminar un producto, su imagen también se elimina

## Uso

### Crear un Producto

1. Ve a **Productos** > **Nuevo Producto**
2. Completa los campos del formulario
3. En el campo **Imagen del Producto**, haz clic en "Elegir archivo"
4. Selecciona una imagen de tu dispositivo
5. Guarda el producto

### Editar un Producto

1. Ve a **Productos** y haz clic en el ícono de editar
2. Si el producto tiene una imagen, se mostrará una vista previa
3. Para cambiar la imagen, selecciona un nuevo archivo
4. Guarda los cambios

### Visualización

Las imágenes se muestran en:

- **Lista de productos**: Miniatura de 50x50px
- **Menú público**: Imagen de 200px de alto
- **Detalle del producto**: Imagen completa

## Configuración Requerida

Para que las imágenes se muestren correctamente, debes crear el enlace simbólico de storage:

```bash
php artisan storage:link
```

Este comando crea un enlace simbólico desde `public/storage` a `storage/app/public`, permitiendo que las imágenes sean accesibles públicamente.

## Estructura de Archivos

```
storage/
└── app/
    └── public/
        ├── products/          # Imágenes de productos
        ├── logos/             # Logos del restaurante
        └── menu-backgrounds/  # Fondos del menú
```

## Notas Técnicas

- Las imágenes se almacenan con nombres únicos generados automáticamente
- Al actualizar una imagen, la anterior se elimina automáticamente
- El campo `image` en la base de datos almacena la ruta relativa (ej: `products/abc123.jpg`)
- Se usa `Storage::url()` para generar las URLs públicas de las imágenes

## Migración de Datos Existentes

Si tienes productos con URLs externas en el campo `image`, estas seguirán funcionando. Sin embargo, se recomienda:

1. Descargar las imágenes externas
2. Subirlas usando el nuevo sistema
3. Esto mejorará el rendimiento y evitará enlaces rotos
