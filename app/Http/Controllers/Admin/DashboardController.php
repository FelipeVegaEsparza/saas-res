<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_restaurants' => Restaurant::count(),
            'active_restaurants' => Restaurant::whereHas('subscriptions', function($query) {
                $query->where('status', 'active');
            })->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount'),
            'new_this_month' => Restaurant::whereMonth('created_at', now()->month)->count(),
        ];

        // Restaurantes recientes
        $recent_restaurants = Restaurant::with('activeSubscription.plan')
            ->latest()
            ->take(5)
            ->get();

        // Suscripciones por vencer (próximos 7 días)
        $expiring_soon = Subscription::where('status', 'active')
            ->whereBetween('ends_at', [now(), now()->addDays(7)])
            ->with('restaurant')
            ->get();

        // Ingresos por mes (últimos 6 meses)
        $monthly_revenue = Subscription::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recent_restaurants',
            'expiring_soon',
            'monthly_revenue'
        ));
    }
}
