# Sistema de Configuración de Emails Personalizable

## ✅ Implementación Completada

El sistema ahora permite personalizar completamente los emails de bienvenida desde el dashboard de administración.

## 🎯 Características Implementadas

### 1. Modelo SystemSetting

- Almacena configuraciones en la base de datos (tabla `system_settings`)
- Métodos disponibles:
  - `SystemSetting::get($key, $default)` - Obtener valor
  - `SystemSetting::set($key, $value, $type, $group)` - Establecer valor
  - `SystemSetting::getGroup($group)` - Obtener todas las configuraciones de un grupo
- Sistema de caché automático (1 hora)

### 2. Configuraciones Disponibles

#### Grupo: Email

- `email_welcome_subject` - Asunto del email de bienvenida
- `email_welcome_message` - Mensaje principal del email
- `email_footer_text` - Texto del footer del email

#### Grupo: General

- `company_name` - Nombre de la empresa
- `support_email` - Email de soporte

### 3. Interfaz de Administración

- Ruta: `/admin/settings`
- Formulario intuitivo con campos agrupados
- Validación de datos
- Mensajes de éxito/error con SweetAlert2
- Consejos y ayuda contextual

### 4. Integración con Emails

- `app/Mail/RestaurantCreated.php` - Usa SystemSetting para cargar configuraciones
- `resources/views/emails/restaurant-created.blade.php` - Usa variables dinámicas
- Los cambios se aplican inmediatamente sin reiniciar

## 🚀 Cómo Usar

### Paso 1: Acceder a Configuración

1. Inicia sesión en el dashboard admin: `http://localhost:8000/admin/login`
2. Credenciales: `admin@admin.com` / `admin123`
3. Ve al menú "Configuración" en la barra lateral

### Paso 2: Personalizar Configuraciones

1. Modifica los campos según tus necesidades:
   - Asunto del email
   - Mensaje de bienvenida
   - Texto del footer
   - Nombre de la empresa
   - Email de soporte
2. Haz clic en "Guardar Cambios"

### Paso 3: Probar

1. Ve a "Restaurantes" → "Crear Nuevo"
2. Completa el formulario y crea un restaurante
3. Verifica el email en `storage/logs/laravel.log`
4. El email debe contener tus configuraciones personalizadas

## 📧 Ejemplo de Email Personalizado

```
Asunto: ¡Bienvenido! Tu cuenta ha sido activada - Mi Restaurante

Hola Juan Pérez,

Tu restaurante Mi Restaurante ha sido configurado exitosamente.

[Tu mensaje personalizado aquí]

Credenciales:
- URL: http://localhost:8000/mirestaurante/login
- Email: admin@mirestaurante.com
- Contraseña: [la que configuraste]

[Tu texto de footer personalizado]

Saludos,
El Equipo de [Tu Empresa]
```

## 🔧 Configuración de Email Real

Para enviar emails reales (no solo logs), actualiza tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Tu Empresa"
```

## 📝 Valores por Defecto

Si no se encuentra una configuración, se usan estos valores:

- `email_welcome_subject`: "¡Bienvenido! Tu cuenta ha sido activada"
- `email_welcome_message`: "Tu cuenta está lista para usar..."
- `email_footer_text`: "Este es un correo automático..."
- `company_name`: "Sistema de Gestión de Restaurantes"
- `support_email`: El configurado en `MAIL_FROM_ADDRESS`

## 🎨 Personalización Avanzada

Si necesitas personalizar más el email:

1. Edita `resources/views/emails/restaurant-created.blade.php`
2. Agrega nuevas configuraciones en `database/seeders/SystemSettingsSeeder.php`
3. Ejecuta: `php artisan db:seed --class=SystemSettingsSeeder`
4. Actualiza `app/Mail/RestaurantCreated.php` para cargar las nuevas configuraciones

## ✅ Verificación

Para verificar que las configuraciones están funcionando:

```bash
# Ver todas las configuraciones
php artisan tinker
>>> App\Models\SystemSetting::all();

# Obtener una configuración específica
>>> App\Models\SystemSetting::get('company_name');

# Establecer una configuración
>>> App\Models\SystemSetting::set('company_name', 'Mi Empresa', 'text', 'general');
```

## 🎉 ¡Listo!

El sistema está completamente funcional. Ahora puedes:

- ✅ Personalizar emails desde el dashboard
- ✅ Los cambios se aplican inmediatamente
- ✅ Crear restaurantes con proceso 100% automático
- ✅ Enviar emails con credenciales personalizadas
- ✅ Mantener una experiencia de marca consistente
