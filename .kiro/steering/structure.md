# Project Structure

## Laravel Application Structure

### Core Directories

```
app/
├── Console/Commands/     # Artisan commands for tenant management
├── Http/
│   ├── Controllers/
│   │   ├── Admin/       # Central admin panel controllers
│   │   ├── Tenant/      # Tenant-specific controllers
│   │   └── authentications/ # Auth controllers
│   └── Middleware/      # Custom middleware (tenancy, auth)
├── Models/
│   ├── Tenant/         # Tenant-scoped models (Order, Product, etc.)
│   └── *.php           # Central models (Restaurant, Plan, etc.)
├── Mail/               # Email templates
└── Providers/          # Service providers
```

### Configuration

```
config/
├── tenancy.php         # Multi-tenancy configuration
├── database.php        # Database connections (landlord/tenant)
└── app.php            # Application settings
```

### Database Structure

```
database/
├── migrations/
│   ├── landlord/       # Central database migrations
│   └── tenant/         # Tenant database migrations
└── seeders/           # Database seeders
```

## Naming Conventions

### Models

- **Central models**: `Restaurant`, `Plan`, `Subscription` (use `landlord` connection)
- **Tenant models**: Namespace `App\Models\Tenant\` (use default connection)
- **Relationships**: Follow Laravel conventions (`hasMany`, `belongsTo`)

### Controllers

- **Admin controllers**: `App\Http\Controllers\Admin\*Controller`
- **Tenant controllers**: `App\Http\Controllers\Tenant\*Controller`
- **Method naming**: RESTful conventions (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)

### Routes

- **Admin routes**: Prefix `/admin`, middleware `admin.auth`
- **Tenant routes**: Prefix `/{tenant}`, middleware `tenant.path`, `tenant.auth`
- **Public routes**: No prefix for landing pages

### Database Connections

- **Central data**: Use `landlord` connection explicitly
- **Tenant data**: Use default connection (dynamically switched)
- **Migration paths**: Separate directories for landlord/tenant migrations

## Multi-Tenancy Architecture

### Tenant Identification

- **Path-based**: `/{tenant}/dashboard` for development
- **Domain-based**: `tenant.domain.com` for production
- **Middleware**: `InitializeTenancyByPath` handles tenant resolution

### Data Isolation

- **Database level**: Each tenant has separate database (`tenant_{id}`)
- **Connection switching**: Automatic based on tenant context
- **File storage**: Tenant-specific directories for uploads

### Key Files

- `app/Models/Tenant.php` - Base tenant model with restaurant relationship
- `config/tenancy.php` - Tenancy configuration and bootstrappers
- `app/Http/Middleware/InitializeTenancyByPath.php` - Tenant resolution
- `routes/web.php` - Route definitions with tenant grouping

## Code Organization Patterns

### Service Layer

- Business logic should be extracted to service classes
- Place in `app/Services/` directory
- Inject via dependency injection

### Repository Pattern

- For complex data access, use repository pattern
- Place in `app/Repositories/` directory
- Interface-based for testability

### Form Requests

- Validation logic in form request classes
- Place in `app/Http/Requests/` directory
- Separate for Admin and Tenant contexts
