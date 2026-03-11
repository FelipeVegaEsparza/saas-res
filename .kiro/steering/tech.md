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
