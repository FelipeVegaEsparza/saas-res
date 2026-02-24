<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\pages\MiscError;

// SITIO PÚBLICO (LANDING)
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/features', [LandingController::class, 'features'])->name('landing.features');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('landing.pricing');
Route::get('/contact', [LandingController::class, 'contact'])->name('landing.contact');
Route::post('/contact', [LandingController::class, 'submitContact'])->name('landing.contact.submit');
Route::get('/tutorials', [LandingController::class, 'tutorials'])->name('landing.tutorials');

// PANEL DE ADMINISTRACIÓN
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Autenticación
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Rutas protegidas
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Restaurantes
        Route::resource('restaurants', AdminRestaurantController::class);
        Route::post('restaurants/{id}/toggle-status', [AdminRestaurantController::class, 'toggleStatus'])->name('restaurants.toggleStatus');
        Route::get('restaurants/{id}/edit-credentials', [AdminRestaurantController::class, 'editCredentials'])->name('restaurants.editCredentials');
        Route::put('restaurants/{id}/update-credentials', [AdminRestaurantController::class, 'updateCredentials'])->name('restaurants.updateCredentials');

        // Suscripciones
        Route::resource('subscriptions', AdminSubscriptionController::class)->except(['create', 'store', 'destroy']);
        Route::post('subscriptions/{id}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('subscriptions/{id}/renew', [AdminSubscriptionController::class, 'renew'])->name('subscriptions.renew');

        // Planes
        Route::resource('plans', AdminPlanController::class);

        // Configuración
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        // Tutoriales
        Route::resource('tutorial-categories', \App\Http\Controllers\Admin\TutorialCategoryController::class);
        Route::resource('tutorials', \App\Http\Controllers\Admin\TutorialController::class);
    });
});

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// RUTAS PATH-BASED TENANT (para desarrollo sin modificar hosts)
use App\Http\Controllers\Tenant\MenuController;
use App\Http\Controllers\Tenant\OnlineOrderController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\QRController;
use App\Http\Controllers\Tenant\Auth\LoginController as TenantLoginController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\TableController;
use App\Http\Controllers\Tenant\DeliveryOrderController;
use App\Http\Controllers\Tenant\CashRegisterController;
use App\Http\Controllers\Tenant\StockController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\StatisticsController;
use App\Http\Controllers\Tenant\SettingsController;

Route::prefix('{tenant}')->middleware(['tenant.path'])->group(function () {

    // RUTAS PÚBLICAS
    Route::get('/menu', [MenuController::class, 'index'])->name('tenant.path.menu.index');
    Route::get('/menu/{slug}', [MenuController::class, 'show'])->name('tenant.path.menu.show');
    Route::get('/menu/search', [MenuController::class, 'search'])->name('tenant.path.menu.search');

    // PEDIDOS ONLINE
    Route::get('/order', [OnlineOrderController::class, 'index'])->name('tenant.path.online.index');
    Route::post('/order', [OnlineOrderController::class, 'store'])->name('tenant.path.online.store');

    // AUTENTICACIÓN
    Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.path.login');
    Route::post('/login', [TenantLoginController::class, 'login'])->name('tenant.path.login.post');
    Route::post('/logout', [TenantLoginController::class, 'logout'])->name('tenant.path.logout');

    // RUTAS PROTEGIDAS
    Route::middleware(['tenant.auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('tenant.path.dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.path.dashboard.index');

        Route::resource('products', ProductController::class)->names([
            'index' => 'tenant.path.products.index',
            'create' => 'tenant.path.products.create',
            'store' => 'tenant.path.products.store',
            'show' => 'tenant.path.products.show',
            'edit' => 'tenant.path.products.edit',
            'update' => 'tenant.path.products.update',
            'destroy' => 'tenant.path.products.destroy',
        ]);

        Route::resource('categories', CategoryController::class)->names([
            'index' => 'tenant.path.categories.index',
            'create' => 'tenant.path.categories.create',
            'store' => 'tenant.path.categories.store',
            'show' => 'tenant.path.categories.show',
            'edit' => 'tenant.path.categories.edit',
            'update' => 'tenant.path.categories.update',
            'destroy' => 'tenant.path.categories.destroy',
        ]);

        // Rutas de Mesas - Definidas individualmente sin prefix group
        Route::get('tables', [TableController::class, 'index'])->name('tenant.path.tables.index');
        Route::get('tables/create', [TableController::class, 'create'])->name('tenant.path.tables.create');
        Route::post('tables', [TableController::class, 'store'])->name('tenant.path.tables.store');
        Route::post('tables/update-positions', [TableController::class, 'updatePositions'])->name('tenant.path.tables.updatePositions');

        // Rutas específicas para gestión de pedidos - MUY ESPECÍFICAS PRIMERO
        Route::get('tables/{table_id}/take-order', [TableController::class, 'takeOrder'])->where('table_id', '[0-9]+')->name('tenant.path.tables.takeOrder');
        Route::post('tables/{table_id}/store-order', [TableController::class, 'storeOrder'])->where('table_id', '[0-9]+')->name('tenant.path.tables.storeOrder');
        Route::get('tables/{table_id}/show-order', [TableController::class, 'showOrder'])->where('table_id', '[0-9]+')->name('tenant.path.tables.showOrder');
        Route::post('tables/{table_id}/update-status', [TableController::class, 'updateOrderStatus'])->where('table_id', '[0-9]+')->name('tenant.path.tables.updateOrderStatus');
        Route::post('tables/sync-status', [TableController::class, 'syncStatus'])->name('tenant.path.tables.syncStatus');

        // Rutas CRUD - DESPUÉS
        Route::get('tables/{table_id}/edit', [TableController::class, 'edit'])->where('table_id', '[0-9]+')->name('tenant.path.tables.edit');
        Route::put('tables/{table_id}', [TableController::class, 'update'])->where('table_id', '[0-9]+')->name('tenant.path.tables.update');
        Route::delete('tables/{table_id}', [TableController::class, 'destroy'])->where('table_id', '[0-9]+')->name('tenant.path.tables.destroy');

        Route::prefix('qr')->name('tenant.path.qr.')->group(function () {
            Route::get('/table/{tableId}', [QRController::class, 'generate'])->where('tableId', '[0-9]+')->name('generate');
            Route::get('/table/{tableId}/download', [QRController::class, 'download'])->where('tableId', '[0-9]+')->name('download');
            Route::get('/print-all', [QRController::class, 'printAll'])->name('print-all');
        });

        Route::resource('delivery', DeliveryOrderController::class)->names([
            'index' => 'tenant.path.delivery.index',
            'create' => 'tenant.path.delivery.create',
            'store' => 'tenant.path.delivery.store',
            'show' => 'tenant.path.delivery.show',
            'edit' => 'tenant.path.delivery.edit',
            'update' => 'tenant.path.delivery.update',
            'destroy' => 'tenant.path.delivery.destroy',
        ]);
        Route::post('delivery/{deliveryOrder}/status', [DeliveryOrderController::class, 'updateStatus'])->name('tenant.path.delivery.updateStatus');

        // Rutas de Caja (POS)
        Route::prefix('cash')->name('tenant.path.cash.')->group(function () {
            Route::get('/', [CashRegisterController::class, 'index'])->name('index');
            Route::post('/open', [CashRegisterController::class, 'openSession'])->name('open');
            Route::post('/{cashSession}/close', [CashRegisterController::class, 'closeSession'])->name('close');
            Route::get('/pos', [CashRegisterController::class, 'pos'])->name('pos');
            Route::post('/payment', [CashRegisterController::class, 'processPayment'])->name('payment');
            Route::get('/{cashSession}/report', [CashRegisterController::class, 'report'])->name('report');
        });

        // Rutas de Stock
        Route::prefix('stock')->name('tenant.path.stock.')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('index');
            Route::put('/{id}', [StockController::class, 'update'])->name('update');
        });

        // Rutas de Usuarios
        Route::resource('users', UserController::class)->names([
            'index' => 'tenant.path.users.index',
            'create' => 'tenant.path.users.create',
            'store' => 'tenant.path.users.store',
            'show' => 'tenant.path.users.show',
            'edit' => 'tenant.path.users.edit',
            'update' => 'tenant.path.users.update',
            'destroy' => 'tenant.path.users.destroy',
        ]);

        // Rutas de Estadísticas
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('tenant.path.statistics.index');

        // Rutas de Configuración
        Route::get('/settings', [SettingsController::class, 'index'])->name('tenant.path.settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('tenant.path.settings.update');
        Route::get('/settings/download-qr', [SettingsController::class, 'downloadQR'])->name('tenant.path.settings.download-qr');
    });
});
