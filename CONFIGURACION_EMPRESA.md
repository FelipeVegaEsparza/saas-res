# Configuración de Información de la Empresa

## ✅ Implementación Completada

El sistema ahora permite configurar completamente la información de tu empresa desde el dashboard de administración, incluyendo logo, datos de contacto y redes sociales. Esta información se utiliza automáticamente en el sitio web público (landing page).

## 🎯 Características Implementadas

### 1. Configuración Básica de la Empresa

Desde `/admin/settings` puedes configurar:

#### Información de la Empresa

- **Nombre de la Empresa** - Se muestra en navbar, footer y meta tags
- **Logo** - Imagen que reemplaza el nombre en navbar y footer
  - Formatos: JPG, PNG, SVG
  - Tamaño máximo: 2MB
  - Se guarda en `storage/app/public/logos/`
- **Teléfono** - Número de contacto visible en footer y página de contacto
- **Email** - Email de contacto principal
- **Dirección** - Dirección física (opcional)
- **Descripción** - Texto descriptivo para footer y meta description

#### Redes Sociales

- Facebook
- Instagram
- Twitter
- LinkedIn
- YouTube

Cada red social acepta la URL completa del perfil (ej: `https://facebook.com/tupagina`)

### 2. Integración con Landing Page

Todas las configuraciones se aplican automáticamente en:

#### Navbar

- Logo o nombre de la empresa
- Enlaces de navegación

#### Footer

- Logo o nombre de la empresa
- Descripción de la empresa
- Iconos de redes sociales (solo se muestran las configuradas)
- Información de contacto (email, teléfono, dirección)
- Copyright con nombre de la empresa

#### Página de Contacto

- Información de contacto dinámica
- Botones de redes sociales
- Placeholder con teléfono de la empresa

#### Meta Tags

- Title dinámico con nombre de la empresa
- Meta description con descripción de la empresa

### 3. Sistema de Almacenamiento

- Las imágenes se guardan en `storage/app/public/logos/`
- El enlace simbólico `public/storage` permite acceso público
- Al actualizar el logo, se elimina automáticamente el anterior
- Las configuraciones se cachean por 1 hora para mejor rendimiento

## 🚀 Cómo Usar

### Paso 1: Acceder a Configuración

1. Inicia sesión en el dashboard admin: `http://localhost:8000/admin/login`
2. Credenciales: `admin@admin.com` / `admin123`
3. Ve al menú "Configuración" en la barra lateral

### Paso 2: Configurar Información de la Empresa

1. En la sección "Información de la Empresa":

   - Ingresa el nombre de tu empresa
   - Sube tu logo (opcional)
   - Completa teléfono, email y dirección
   - Agrega una descripción atractiva

2. En la sección "Redes Sociales":

   - Agrega las URLs de tus perfiles sociales
   - Solo se mostrarán las que configures

3. Haz clic en "Guardar Cambios"

### Paso 3: Verificar Cambios

1. Visita la landing page: `http://localhost:8000`
2. Verifica que:
   - El navbar muestre tu logo o nombre
   - El footer tenga tu información
   - Las redes sociales aparezcan (si las configuraste)
3. Visita la página de contacto: `http://localhost:8000/contact`
4. Verifica que la información de contacto sea correcta

## 📋 Estructura de Configuraciones

### Grupo: company

```
company_name          - Nombre de la empresa
company_logo          - Ruta del logo (storage/logos/...)
company_phone         - Teléfono de contacto
company_email         - Email de contacto
company_address       - Dirección física
company_description   - Descripción de la empresa
```

### Grupo: social

```
social_facebook       - URL de Facebook
social_instagram      - URL de Instagram
social_twitter        - URL de Twitter
social_linkedin       - URL de LinkedIn
social_youtube        - URL de YouTube
```

## 🎨 Personalización del Logo

### Recomendaciones:

- **Formato**: PNG con fondo transparente (recomendado)
- **Dimensiones**: 200x50px o similar (proporción horizontal)
- **Tamaño**: Máximo 2MB
- **Colores**: Asegúrate de que sea visible en fondo blanco (navbar) y oscuro (footer)

### Ejemplo de Código:

```php
// En cualquier vista del landing
@if($companyLogo)
    <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}">
@else
    <span>{{ $companyName }}</span>
@endif
```

## 🔧 Configuración Técnica

### View Composer

El `LandingController` usa un View Composer para compartir las configuraciones con todas las vistas del landing:

```php
View::composer('landing.*', function ($view) {
    $view->with([
        'companyName' => SystemSetting::get('company_name', 'RestaurantSaaS'),
        'companyLogo' => SystemSetting::get('company_logo', ''),
        // ... más configuraciones
    ]);
});
```

### Caché

- Las configuraciones se cachean automáticamente por 1 hora
- Al actualizar una configuración, el caché se limpia automáticamente
- Puedes limpiar el caché manualmente: `php artisan cache:clear`

## 📝 Valores por Defecto

Si no se configuran, se usan estos valores:

```
company_name: "RestaurantSaaS"
company_logo: "" (vacío)
company_phone: "+56 9 1234 5678"
company_email: "info@restaurantsaas.com"
company_address: "" (vacío)
company_description: "Sistema completo de gestión para restaurantes..."
social_*: "" (vacío)
```

## 🎯 Casos de Uso

### Caso 1: White Label

Puedes personalizar completamente el sistema con tu marca:

- Sube tu logo
- Cambia el nombre de la empresa
- Agrega tu información de contacto
- Configura tus redes sociales

### Caso 2: Multi-Marca

Si manejas múltiples marcas, puedes cambiar fácilmente la configuración desde el dashboard sin tocar código.

### Caso 3: Actualización de Información

Cuando cambies de oficina, teléfono o redes sociales, actualiza desde el dashboard y los cambios se reflejan inmediatamente en todo el sitio.

## ✅ Verificación

Para verificar que todo funciona:

```bash
# Ver configuraciones actuales
php artisan tinker
>>> App\Models\SystemSetting::where('group', 'company')->get();
>>> App\Models\SystemSetting::where('group', 'social')->get();

# Limpiar caché si es necesario
>>> Cache::flush();
```

## 🎉 ¡Listo!

El sistema está completamente funcional. Ahora puedes:

- ✅ Personalizar la información de tu empresa
- ✅ Subir y cambiar el logo fácilmente
- ✅ Configurar redes sociales
- ✅ Ver los cambios reflejados automáticamente en el landing
- ✅ Mantener una marca consistente en todo el sistema
