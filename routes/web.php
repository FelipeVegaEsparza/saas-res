<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

// Main Page Route
Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

// RUTAS PATH-BASED TENANT (para desarrollo sin modificar hosts)
use App\Http\Controllers\Tenant\MenuController;
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

    // AUTENTICACIÓN
    Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.path.login');
    Route::post('/login', [TenantLoginController::class, 'login'])->name('tenant.path.login.post');
    Route::post('/logout', [TenantLoginController::class, 'logout'])->name('tenant.path.logout');

    // RUTAS PROTEGIDAS
    Route::middleware(['auth'])->group(function () {
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

        // Rutas específicas para gestión de pedidos - MUY ESPECÍFICAS PRIMERO
        Route::get('tables/{table_id}/take-order', [TableController::class, 'takeOrder'])->where('table_id', '[0-9]+')->name('tenant.path.tables.takeOrder');
        Route::post('tables/{table_id}/store-order', [TableController::class, 'storeOrder'])->where('table_id', '[0-9]+')->name('tenant.path.tables.storeOrder');
        Route::get('tables/{table_id}/show-order', [TableController::class, 'showOrder'])->where('table_id', '[0-9]+')->name('tenant.path.tables.showOrder');
        Route::post('tables/{table_id}/update-status', [TableController::class, 'updateOrderStatus'])->where('table_id', '[0-9]+')->name('tenant.path.tables.updateOrderStatus');

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
