<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Registrar directiva Blade para formatear precios
        Blade::directive('price', function ($expression) {
            return "<?php echo \App\Helpers\Helpers::formatPrice($expression); ?>";
        });

        Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
            if ($src !== null) {
                return [
                    'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
                ];
            }
            return [];
        });

        // Compartir estadísticas del turno en todas las vistas del tenant
        // DESHABILITADO TEMPORALMENTE - Causa conflictos con la inicialización del tenant
        /*
        view()->composer('tenant.layouts.admin', function ($view) {
            try {
                if (tenancy()->initialized) {
                    $activeSession = \App\Models\Tenant\CashSession::where('status', 'open')->first();
                    $shiftStart = $activeSession ? $activeSession->opened_at : now()->startOfDay();

                    $shiftStats = [
                        'shift_revenue' => \App\Models\Tenant\Order::where('created_at', '>=', $shiftStart)
                            ->where('status', 'delivered')
                            ->sum('total') +
                            \App\Models\Tenant\DeliveryOrder::where('created_at', '>=', $shiftStart)
                            ->where('status', 'delivered')
                            ->sum('total'),
                        'shift_delivery_count' => \App\Models\Tenant\DeliveryOrder::where('created_at', '>=', $shiftStart)->count(),
                        'shift_tables_served' => \App\Models\Tenant\Order::where('created_at', '>=', $shiftStart)
                            ->where('status', 'delivered')
                            ->distinct('table_id')
                            ->count('table_id'),
                        'shift_orders_count' => \App\Models\Tenant\Order::where('created_at', '>=', $shiftStart)->count() +
                            \App\Models\Tenant\DeliveryOrder::where('created_at', '>=', $shiftStart)->count(),
                        'has_active_session' => $activeSession !== null,
                    ];

                    // Calcular ticket promedio del turno
                    $shiftStats['shift_avg_ticket'] = $shiftStats['shift_orders_count'] > 0
                        ? $shiftStats['shift_revenue'] / $shiftStats['shift_orders_count']
                        : 0;

                    $view->with('shiftStats', $shiftStats);
                } else {
                    // Si no hay tenant inicializado, pasar datos vacíos
                    $view->with('shiftStats', [
                        'shift_revenue' => 0,
                        'shift_delivery_count' => 0,
                        'shift_tables_served' => 0,
                        'shift_orders_count' => 0,
                        'shift_avg_ticket' => 0,
                        'has_active_session' => false,
                    ]);
                }
            } catch (\Exception $e) {
                // En caso de error, pasar datos vacíos
                $view->with('shiftStats', [
                    'shift_revenue' => 0,
                    'shift_delivery_count' => 0,
                    'shift_tables_served' => 0,
                    'shift_orders_count' => 0,
                    'shift_avg_ticket' => 0,
                    'has_active_session' => false,
                ]);
            }
        });
        */
    }
}
