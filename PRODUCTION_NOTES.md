# 🚀 NOTAS DE PRODUCCIÓN - MULTI-TENANCY

## 🌐 Configuración de DNS Wildcard

### Requisitos
Para que los subdominios funcionen automáticamente, necesitas configurar un registro DNS wildcard.

### Configuración en tu proveedor DNS

#### Cloudflare
```
Tipo: A
Nombre: *
Contenido: [IP_DE_TU_SERVIDOR]
Proxy: Activado (naranja)
TTL: Auto
```

#### cPanel / WHM
```
Tipo: A
Nombre: *.tusistema.com
Dirección: [IP_DE_TU_SERVIDOR]
TTL: 14400
```

#### AWS Route 53
```json
{
  "Name": "*.tusistema.com",
  "Type": "A",
  "TTL": 300,
  "ResourceRecords": [
    {
      "Value": "[IP_DE_TU_SERVIDOR]"
    }
  ]
}
```

### Verificar configuración
```bash
# Debe resolver a tu IP
dig restaurante1.tusistema.com
dig restaurante2.tusistema.com
dig cualquier-cosa.tusistema.com
```

## 🔒 Configuración SSL (HTTPS)

### Opción 1: Certbot con Wildcard (Recomendado)

#### Instalar Certbot
```bash
sudo apt-get update
sudo apt-get install certbot python3-certbot-nginx
```

#### Obtener certificado wildcard
```bash
sudo certbot certonly \
  --manual \
  --preferred-challenges=dns \
  --email admin@tusistema.com \
  --server https://acme-v02.api.letsencrypt.org/directory \
  --agree-tos \
  -d tusistema.com \
  -d *.tusistema.com
```

Certbot te pedirá crear un registro TXT en tu DNS:
```
Tipo: TXT
Nombre: _acme-challenge.tusistema.com
Contenido: [VALOR_PROPORCIONADO_POR_CERTBOT]
```

#### Configurar Nginx
```nginx
server {
    listen 443 ssl http2;
    server_name ~^(?<subdomain>.+)\.tusistema\.com$;
    
    ssl_certificate /etc/letsencrypt/live/tusistema.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tusistema.com/privkey.pem;
    
    root /var/www/saasres/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Redirigir HTTP a HTTPS
server {
    listen 80;
    server_name ~^(?<subdomain>.+)\.tusistema\.com$;
    return 301 https://$host$request_uri;
}
```

#### Renovación automática
```bash
# Agregar a crontab
sudo crontab -e

# Agregar esta línea
0 0 * * * certbot renew --quiet && systemctl reload nginx
```

### Opción 2: Cloudflare SSL (Más fácil)

1. Activar proxy (naranja) en el registro wildcard
2. En Cloudflare Dashboard:
   - SSL/TLS → Overview → Full (strict)
   - SSL/TLS → Edge Certificates → Always Use HTTPS: ON
   - SSL/TLS → Edge Certificates → Automatic HTTPS Rewrites: ON

3. Instalar certificado Origin en el servidor:
   - SSL/TLS → Origin Server → Create Certificate
   - Copiar certificado y clave privada
   - Guardar en `/etc/ssl/cloudflare/`

```nginx
server {
    listen 443 ssl http2;
    server_name ~^(?<subdomain>.+)\.tusistema\.com$;
    
    ssl_certificate /etc/ssl/cloudflare/cert.pem;
    ssl_certificate_key /etc/ssl/cloudflare/key.pem;
    
    # ... resto de la configuración
}
```

## ⚙️ Configuración del Servidor

### Variables de Entorno (.env)
```env
APP_NAME="Tu Sistema"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tusistema.com

DB_CONNECTION=landlord
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saasres_production
DB_USERNAME=saasres_user
DB_PASSWORD=[PASSWORD_SEGURO]

CENTRAL_DOMAIN=tusistema.com

# Cache y sesiones
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tusistema.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Permisos de Archivos
```bash
cd /var/www/saasres

# Propietario
sudo chown -R www-data:www-data .

# Permisos
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

# Storage y cache
sudo chmod -R 775 storage bootstrap/cache
```

### Optimizaciones Laravel
```bash
# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Optimizar autoload
composer install --optimize-autoloader --no-dev
```

## 🗄️ Base de Datos en Producción

### Crear usuario MySQL para tenants
```sql
-- Usuario con permisos para crear bases de datos
CREATE USER 'saasres_tenant'@'localhost' IDENTIFIED BY '[PASSWORD_SEGURO]';
GRANT ALL PRIVILEGES ON `tenant_%`.* TO 'saasres_tenant'@'localhost';
GRANT CREATE ON *.* TO 'saasres_tenant'@'localhost';
FLUSH PRIVILEGES;
```

### Backup automático
```bash
#!/bin/bash
# /usr/local/bin/backup-tenants.sh

BACKUP_DIR="/backups/saasres"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup landlord
mysqldump -u root -p[PASSWORD] saasres_production > "$BACKUP_DIR/landlord_$DATE.sql"

# Backup todos los tenants
mysql -u root -p[PASSWORD] -e "SHOW DATABASES LIKE 'tenant_%'" | grep tenant_ | while read db; do
    mysqldump -u root -p[PASSWORD] "$db" > "$BACKUP_DIR/${db}_$DATE.sql"
done

# Comprimir
tar -czf "$BACKUP_DIR/backup_$DATE.tar.gz" "$BACKUP_DIR"/*_$DATE.sql
rm "$BACKUP_DIR"/*_$DATE.sql

# Eliminar backups antiguos (más de 7 días)
find "$BACKUP_DIR" -name "backup_*.tar.gz" -mtime +7 -delete
```

```bash
# Agregar a crontab
0 2 * * * /usr/local/bin/backup-tenants.sh
```

## 🚦 Monitoreo y Logs

### Configurar logs
```php
// config/logging.php
'channels' => [
    'tenant' => [
        'driver' => 'daily',
        'path' => storage_path('logs/tenant.log'),
        'level' => 'debug',
        'days' => 14,
    ],
],
```

### Monitoreo con Laravel Telescope (Desarrollo)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Monitoreo en Producción
- **New Relic**: Monitoreo de rendimiento
- **Sentry**: Tracking de errores
- **Laravel Horizon**: Monitoreo de colas (si usas Redis)

## 🔥 Firewall y Seguridad

### UFW (Ubuntu)
```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

### Fail2Ban
```bash
sudo apt-get install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## 📊 Límites y Cuotas

### Implementar límites por plan
```php
// app/Http/Middleware/CheckTenantLimits.php
namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant\Product;

class CheckTenantLimits
{
    public function handle($request, Closure $next)
    {
        $restaurant = tenant()->restaurant();
        $plan = $restaurant->activeSubscription->plan ?? null;
        
        if (!$plan) {
            return redirect()->route('subscription.required');
        }
        
        // Verificar límite de productos
        if ($request->is('products/create')) {
            $productCount = Product::count();
            if ($plan->max_products > 0 && $productCount >= $plan->max_products) {
                return redirect()->back()->with('error', 'Has alcanzado el límite de productos de tu plan');
            }
        }
        
        return $next($request);
    }
}
```

## 🔄 Actualizaciones

### Proceso de actualización
```bash
# 1. Modo mantenimiento
php artisan down

# 2. Pull cambios
git pull origin main

# 3. Actualizar dependencias
composer install --no-dev --optimize-autoloader

# 4. Migrar landlord
php artisan migrate --force --path=database/migrations/landlord

# 5. Migrar todos los tenants
php artisan tenants:migrate --force

# 6. Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 7. Cachear nuevamente
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Salir de mantenimiento
php artisan up
```

## 📈 Escalabilidad

### Redis para caché y sesiones
```bash
sudo apt-get install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

### Queue Workers
```bash
# Supervisor config: /etc/supervisor/conf.d/saasres-worker.conf
[program:saasres-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/saasres/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/saasres/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start saasres-worker:*
```

## 🎯 Checklist de Producción

- [ ] DNS wildcard configurado
- [ ] SSL wildcard instalado
- [ ] Nginx configurado con regex para subdominios
- [ ] Variables de entorno configuradas
- [ ] Permisos de archivos correctos
- [ ] Usuario MySQL para tenants creado
- [ ] Backups automáticos configurados
- [ ] Firewall configurado
- [ ] Fail2Ban instalado
- [ ] Redis instalado y configurado
- [ ] Queue workers con Supervisor
- [ ] Logs configurados
- [ ] Monitoreo configurado
- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] Cachés optimizados

## 🆘 Comandos Útiles

```bash
# Ver tenants activos
php artisan tenants:list

# Ejecutar comando en todos los tenants
php artisan tenants:run --command="cache:clear"

# Ejecutar comando en un tenant específico
php artisan tenants:run {tenant_id} --command="db:seed"

# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Reiniciar workers
sudo supervisorctl restart saasres-worker:*

# Ver estado de workers
sudo supervisorctl status
```

---

**Nota**: Estos son los pasos esenciales para producción. Ajusta según tus necesidades específicas y proveedor de hosting.
