<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\MenuController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\QRController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\TableController;

// RUTAS SUBDOMAIN-BASED (para producción con subdominios)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // RUTAS PÚBLICAS
    Route::get('/menu', [MenuController::class, 'index'])->name('tenant.menu.index');
    Route::get('/menu/{slug}', [MenuController::class, 'show'])->name('tenant.menu.show');
    Route::get('/menu/search', [MenuController::class, 'search'])->name('tenant.menu.search');

    // AUTENTICACIÓN
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('tenant.logout');

    // RUTAS PROTEGIDAS
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard.index');

        Route::resource('products', ProductController::class)->names([
            'index' => 'tenant.products.index',
            'create' => 'tenant.products.create',
            'store' => 'tenant.products.store',
            'edit' => 'tenant.products.edit',
            'update' => 'tenant.products.update',
            'destroy' => 'tenant.products.destroy',
        ]);

        Route::resource('categories', CategoryController::class)->names([
            'index' => 'tenant.categories.index',
            'create' => 'tenant.categories.create',
            'store' => 'tenant.categories.store',
            'edit' => 'tenant.categories.edit',
            'update' => 'tenant.categories.update',
            'destroy' => 'tenant.categories.destroy',
        ]);

        Route::resource('tables', TableController::class)->names([
            'index' => 'tenant.tables.index',
            'create' => 'tenant.tables.create',
            'store' => 'tenant.tables.store',
            'edit' => 'tenant.tables.edit',
            'update' => 'tenant.tables.update',
            'destroy' => 'tenant.tables.destroy',
        ]);
        Route::get('tables-map', [TableController::class, 'map'])->name('tenant.tables.map');
        Route::post('tables/update-positions', [TableController::class, 'updatePositions'])->name('tenant.tables.updatePositions');

        Route::prefix('qr')->name('tenant.qr.')->group(function () {
            Route::get('/table/{table}', [QRController::class, 'generate'])->name('generate');
            Route::get('/table/{table}/download', [QRController::class, 'download'])->name('download');
            Route::get('/print-all', [QRController::class, 'printAll'])->name('print-all');
        });
    });
});
