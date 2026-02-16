<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\DeliveryOrder;
use App\Models\Tenant\Product;
use App\Models\Tenant\Payment;
use App\Models\Tenant\CashSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request, $tenant)
    {
        // Obtener filtros
        $period = $request->get('period', 'today'); // today, week, month, year, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Configurar rango de fechas según el período
        [$start, $end] = $this->getDateRange($period, $startDate, $endDate);

        // Estadísticas generales
        $stats = $this->getGeneralStats($start, $end);

        // Ventas por día (para gráfico)
        $salesByDay = $this->getSalesByDay($start, $end);

        // Productos más vendidos
        $topProducts = $this->getTopProducts($start, $end, 10);

        // Productos menos vendidos
        $lowProducts = $this->getLowProducts($start, $end, 5);

        // Ventas por categoría
        $salesByCategory = $this->getSalesByCategory($start, $end);

        // Métodos de pago
        $paymentMethods = $this->getPaymentMethods($start, $end);

        // Horarios pico
        $peakHours = $this->getPeakHours($start, $end);

        // Comparación con período anterior
        $comparison = $this->getComparison($start, $end);

        return view('tenant.statistics.index', compact(
            'stats',
            'salesByDay',
            'topProducts',
            'lowProducts',
            'salesByCategory',
            'paymentMethods',
            'peakHours',
            'comparison',
            'period',
            'start',
            'end'
        ));
    }

    private function getDateRange($period, $startDate, $endDate)
    {
        switch ($period) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'yesterday':
                return [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
            case 'week':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'year':
                return [now()->startOfYear(), now()->endOfYear()];
            case 'custom':
                if ($startDate && $endDate) {
                    return [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ];
                }
                return [now()->startOfMonth(), now()->endOfMonth()];
            default:
                return [now()->startOfDay(), now()->endOfDay()];
        }
    }

    private function getGeneralStats($start, $end)
    {
        // Órdenes de mesas
        $tableOrders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->get();

        // Órdenes de delivery
        $deliveryOrders = DeliveryOrder::whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->get();

        $totalOrders = $tableOrders->count() + $deliveryOrders->count();
        $totalRevenue = $tableOrders->sum('total') + $deliveryOrders->sum('total');
        $averageTicket = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Pagos
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();
        $totalPayments = $payments->sum('amount');

        return [
            'total_orders' => $totalOrders,
            'table_orders' => $tableOrders->count(),
            'delivery_orders' => $deliveryOrders->count(),
            'total_revenue' => $totalRevenue,
            'average_ticket' => $averageTicket,
            'total_payments' => $totalPayments,
            'total_items_sold' => $tableOrders->sum(function($order) {
                return $order->items->sum('quantity');
            }) + $deliveryOrders->sum(function($order) {
                return $order->items->sum('quantity');
            }),
        ];
    }

    private function getSalesByDay($start, $end)
    {
        $days = [];
        $current = $start->copy();

        while ($current <= $end) {
            $dayStart = $current->copy()->startOfDay();
            $dayEnd = $current->copy()->endOfDay();

            $tableRevenue = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                ->where('status', 'delivered')
                ->sum('total');

            $deliveryRevenue = DeliveryOrder::whereBetween('created_at', [$dayStart, $dayEnd])
                ->where('status', 'delivered')
                ->sum('total');

            $days[] = [
                'date' => $current->format('Y-m-d'),
                'label' => $current->format('d/m'),
                'revenue' => $tableRevenue + $deliveryRevenue,
            ];

            $current->addDay();
        }

        return $days;
    }

    private function getTopProducts($start, $end, $limit = 10)
    {
        return Product::select('products.*')
            ->selectRaw('(
                SELECT COALESCE(SUM(order_items.quantity), 0)
                FROM order_items
                INNER JOIN orders ON order_items.order_id = orders.id
                WHERE order_items.product_id = products.id
                AND orders.created_at BETWEEN ? AND ?
                AND orders.status = "delivered"
            ) + (
                SELECT COALESCE(SUM(delivery_order_items.quantity), 0)
                FROM delivery_order_items
                INNER JOIN delivery_orders ON delivery_order_items.delivery_order_id = delivery_orders.id
                WHERE delivery_order_items.product_id = products.id
                AND delivery_orders.created_at BETWEEN ? AND ?
                AND delivery_orders.status = "delivered"
            ) as total_sold', [$start, $end, $start, $end])
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getLowProducts($start, $end, $limit = 5)
    {
        return Product::select('products.*')
            ->selectRaw('(
                SELECT COALESCE(SUM(order_items.quantity), 0)
                FROM order_items
                INNER JOIN orders ON order_items.order_id = orders.id
                WHERE order_items.product_id = products.id
                AND orders.created_at BETWEEN ? AND ?
                AND orders.status = "delivered"
            ) + (
                SELECT COALESCE(SUM(delivery_order_items.quantity), 0)
                FROM delivery_order_items
                INNER JOIN delivery_orders ON delivery_order_items.delivery_order_id = delivery_orders.id
                WHERE delivery_order_items.product_id = products.id
                AND delivery_orders.created_at BETWEEN ? AND ?
                AND delivery_orders.status = "delivered"
            ) as total_sold', [$start, $end, $start, $end])
            ->where('available', true)
            ->orderBy('total_sold', 'asc')
            ->limit($limit)
            ->get();
    }

    private function getSalesByCategory($start, $end)
    {
        return DB::connection('tenant')
            ->table('categories')
            ->select('categories.name', 'categories.id')
            ->selectRaw('COALESCE(SUM(order_items.quantity * order_items.unit_price), 0) + COALESCE(SUM(delivery_order_items.quantity * delivery_order_items.price), 0) as total_revenue')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($start, $end) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->where('orders.status', '=', 'delivered');
            })
            ->leftJoin('delivery_order_items', 'products.id', '=', 'delivery_order_items.product_id')
            ->leftJoin('delivery_orders', function($join) use ($start, $end) {
                $join->on('delivery_order_items.delivery_order_id', '=', 'delivery_orders.id')
                    ->whereBetween('delivery_orders.created_at', [$start, $end])
                    ->where('delivery_orders.status', '=', 'delivered');
            })
            ->groupBy('categories.id', 'categories.name')
            ->having('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    private function getPaymentMethods($start, $end)
    {
        return Payment::whereBetween('created_at', [$start, $end])
            ->select('payment_method')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();
    }

    private function getPeakHours($start, $end)
    {
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->get();

        $deliveryOrders = DeliveryOrder::whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->get();

        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyData[$i] = ['hour' => $i, 'orders' => 0, 'revenue' => 0];
        }

        foreach ($orders as $order) {
            $hour = (int) $order->created_at->format('H');
            $hourlyData[$hour]['orders']++;
            $hourlyData[$hour]['revenue'] += $order->total;
        }

        foreach ($deliveryOrders as $order) {
            $hour = (int) $order->created_at->format('H');
            $hourlyData[$hour]['orders']++;
            $hourlyData[$hour]['revenue'] += $order->total;
        }

        return collect($hourlyData)->sortByDesc('orders')->take(5)->values();
    }

    private function getComparison($start, $end)
    {
        $days = $start->diffInDays($end) + 1;
        $previousStart = $start->copy()->subDays($days);
        $previousEnd = $end->copy()->subDays($days);

        $currentStats = $this->getGeneralStats($start, $end);
        $previousStats = $this->getGeneralStats($previousStart, $previousEnd);

        return [
            'revenue_change' => $previousStats['total_revenue'] > 0
                ? (($currentStats['total_revenue'] - $previousStats['total_revenue']) / $previousStats['total_revenue']) * 100
                : 0,
            'orders_change' => $previousStats['total_orders'] > 0
                ? (($currentStats['total_orders'] - $previousStats['total_orders']) / $previousStats['total_orders']) * 100
                : 0,
        ];
    }
}
