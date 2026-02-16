<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\Table;
use App\Models\Tenant\DeliveryOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard principal del restaurante
     */
    public function index()
    {
        $restaurant = tenant()->restaurant();

        // Estadísticas del día
        $today = now()->startOfDay();

        $stats = [
            'orders_today' => Order::where('created_at', '>=', $today)->count(),
            'revenue_today' => Order::where('created_at', '>=', $today)
                ->where('status', 'delivered')
                ->sum('total'),
            'tables_occupied' => Table::where('status', 'occupied')->count(),
            'tables_available' => Table::where('status', 'available')->count(),
            'products_count' => Product::where('available', true)->count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'preparing'])->count(),

            // Estadísticas de delivery
            'delivery_orders_today' => DeliveryOrder::where('created_at', '>=', $today)->count(),
            'delivery_revenue_today' => DeliveryOrder::where('created_at', '>=', $today)
                ->where('status', 'delivered')
                ->sum('total'),
            'delivery_pending' => DeliveryOrder::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'on_delivery'])->count(),
        ];

        // Órdenes recientes
        $recentOrders = Order::with(['table', 'user', 'items.product'])
            ->latest()
            ->limit(10)
            ->get();

        // Pedidos delivery recientes
        $recentDeliveryOrders = DeliveryOrder::with('items.product')
            ->latest()
            ->limit(5)
            ->get();

        // Productos más vendidos (últimos 7 días)
        $topProducts = Product::withCount(['orderItems' => function ($query) {
            $query->whereHas('order', function ($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            });
        }])
        ->orderBy('order_items_count', 'desc')
        ->limit(5)
        ->get();

        return view('tenant.dashboard.index', compact(
            'restaurant',
            'stats',
            'recentOrders',
            'recentDeliveryOrders',
            'topProducts'
        ));
    }
}
