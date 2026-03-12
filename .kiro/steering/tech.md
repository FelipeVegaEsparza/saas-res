# Technology Stack

## Backend Framework

- **Laravel 12** - PHP 8.2+ framework
- **Stancl/Tenancy** - Multi-tenancy package for tenant isolation
- **MySQL** - Primary database with separate tenant databases

## Frontend Stack

- **Vite** - Build tool and development server
- **Bootstrap 5.3.6** - CSS framework
- **jQuery 3.7.1** - JavaScript library
- **Materialize theme** - Admin dashboard template
- **ApexCharts** - Data visualization
- **DataTables** - Advanced table functionality
- **SweetAlert2** - Modal dialogs and notifications

## Key Libraries & Tools

- **Laravel Pint** - Code formatting (PSR-12)
- **PHPUnit** - Testing framework
- **Laravel Sail** - Docker development environment
- **Composer** - PHP dependency management
- **NPM** - JavaScript package management

## Database Architecture

- **Central database** (`landlord` connection) - Stores restaurants, plans, subscriptions
- **Tenant databases** (`tenant` connection) - Individual restaurant data (orders, products, etc.)
- **Dynamic connection switching** - Automatic tenant database selection based on context

## Development Commands

### Backend

```bash
# Start development environment
composer run dev

# Run migrations (central)
php artisan migrate

# Run tenant migrations
php artisan tenants:migrate

# Create new tenant
php artisan tenant:create

# Code formatting
./vendor/bin/pint
```

### Tenant Management Commands

```bash
# === MIGRATION COMMANDS ===
# Migrate all tenants (PRODUCTION SAFE - with backups)
php artisan tenants:safe-migrate-production

# Migrate all tenants (direct)
php artisan tenants:migrate-all

# Migrate specific tenant
php artisan tenants:migrate {tenant_id}

# Check migration status for all tenants
php artisan tenants:check-migrations

# Fresh migrations (DROP ALL TABLES - DANGEROUS)
php artisan tenants:migrate-fresh

# Rollback migrations
php artisan tenants:rollback

# === TENANT MANAGEMENT ===
# List all tenants
php artisan tenants:list

# Create new tenant
php artisan tenant:create

# Check tenant status
php artisan tenant:status {tenant_id}

# Reset tenant user
php artisan tenant:reset-user {tenant_id}

# === SEEDING COMMANDS ===
# Seed all tenants
php artisan tenants:seed

# Seed specific tenant directly
php artisan tenant:seed-direct {tenant_id}

# === BACKUP & MAINTENANCE ===
# Backup all tenant databases
php artisan tenants:backup

# Sync table status with orders
php artisan tenant:sync-table-status {tenant_id}

# Emergency cash session fix
php artisan tenants:emergency-fix-cash

# Fix cash sessions (add missing columns)
php artisan tenants:fix-cash-sessions

# Fix migrations table registration
php artisan tenants:fix-migrations

# === GENERAL TENANT COMMANDS ===
# Run any command for all tenants
php artisan tenants:run "command here"

# Run command for specific tenant
php artisan tenants:run "command here" --tenant={tenant_id}

# Examples:
php artisan tenants:run "migrate:status"
php artisan tenants:run "cache:clear"
php artisan tenants:run "queue:work" --tenant=feroces
```

### Frontend

```bash
# Install dependencies
npm install

# Development server
npm run dev

# Production build
npm run build
```

### Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## Configuration Notes

- Environment variables in `.env` file
- Tenant configuration in `config/tenancy.php`
- Database connections defined in `config/database.php`
- Custom helper functions in `app/Helpers/Helpers.php`
