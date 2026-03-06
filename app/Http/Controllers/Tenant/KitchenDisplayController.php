<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\PreparationArea;
use App\Models\Tenant\Order;
use App\Models\Tenant\DeliveryOrder;
use Illuminate\Http\Request;

class KitchenDisplayController extends Controller
{
    /**
     * Vista KDS unificada (mesas + delivery) para una estación específica
     */
    public function index($tenant, $area_id)
    {
        $area = PreparationArea::findOrFail($area_id);

        // Obtener pedidos activos de mesas que tienen productos de esta estación
        $tableOrders = Order::whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->whereHas('items.product', function($query) use ($area_id) {
                $query->where('preparation_area_id', $area_id);
            })
            ->with(['table', 'items' => function($query) use ($area_id) {
                $query->whereHas('product', function($q) use ($area_id) {
                    $q->where('preparation_area_id', $area_id);
                });
            }, 'items.product', 'waiter'])
            ->get();

        // Obtener pedidos activos de delivery que tienen productos de esta estación
        $deliveryOrders = DeliveryOrder::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'on_delivery'])
            ->whereHas('items.product', function($query) use ($area_id) {
                $query->where('preparation_area_id', $area_id);
            })
            ->with(['items' => function($query) use ($area_id) {
                $query->whereHas('product', function($q) use ($area_id) {
                    $q->where('preparation_area_id', $area_id);
                });
            }, 'items.product'])
            ->get();

        // Agrupar pedidos por estado de preparación
        $ordersByStatus = [
            'pending' => collect(),
            'preparing' => collect(),
            'ready' => collect(),
        ];

        // Procesar pedidos de mesas
        foreach ($tableOrders as $order) {
            $status = $this->getOrderPreparationStatus($order->items);
            $order->order_type = 'table';
            $order->display_name = 'Mesa ' . $order->table->number;
            $ordersByStatus[$status]->push($order);
        }

        // Procesar pedidos de delivery
        foreach ($deliveryOrders as $order) {
            $status = $this->getOrderPreparationStatus($order->items);
            $order->order_type = 'delivery';
            $order->display_name = $order->order_number;
            $ordersByStatus[$status]->push($order);
        }

        // Ordenar por tiempo (más antiguos primero)
        foreach ($ordersByStatus as $status => $orders) {
            $ordersByStatus[$status] = $orders->sortBy('created_at');
        }

        return view('tenant.kds.index', compact('area', 'ordersByStatus'));
    }

    /**
     * Determinar el estado de preparación de un pedido basado en sus items
     */
    private function getOrderPreparationStatus($items)
    {
        $statuses = $items->pluck('preparation_status')->unique();

        // Si todos están listos
        if ($statuses->count() === 1 && $statuses->first() === 'ready') {
            return 'ready';
        }

        // Si alguno está preparando o todos están preparando
        if ($statuses->contains('preparing')) {
            return 'preparing';
        }

        // Por defecto, pendiente
        return 'pending';
    }

    /**
     * Actualizar estado de preparación de un pedido completo (todos sus items de esta estación)
     */
    public function updateOrderStatus(Request $request, $tenant, $area_id, $order_type, $order_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready',
        ]);

        if ($order_type === 'table') {
            $order = Order::findOrFail($order_id);
        } else {
            $order = DeliveryOrder::findOrFail($order_id);
        }

        // Actualizar solo los items de esta estación
        $updated = $order->items()
            ->whereHas('product', function($query) use ($area_id) {
                $query->where('preparation_area_id', $area_id);
            })
            ->update(['preparation_status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'status' => $validated['status'],
            'items_updated' => $updated
        ]);
    }
}
