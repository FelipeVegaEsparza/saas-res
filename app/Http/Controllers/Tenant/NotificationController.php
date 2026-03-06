<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\DeliveryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Verificar si hay nuevos pedidos online
     */
    public function checkNewOrders(Request $request)
    {
        $userId = auth()->id();
        $tenantId = tenant()->id;
        $cacheKey = "last_order_check_{$tenantId}_{$userId}";

        // Obtener el timestamp de la última verificación
        $lastCheck = Cache::get($cacheKey, now()->subMinutes(5));

        // Buscar pedidos nuevos desde la última verificación
        $newOrders = DeliveryOrder::where('created_at', '>', $lastCheck)
            ->where('status', 'pending')
            ->whereRaw("order_number LIKE 'WEB-%'")
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        // Actualizar el timestamp de última verificación solo si hay pedidos nuevos
        // o si han pasado más de 5 minutos desde la última actualización
        if ($newOrders->count() > 0 || now()->diffInMinutes($lastCheck) > 5) {
            Cache::put($cacheKey, now(), now()->addHours(24));
        }

        if ($newOrders->count() > 0) {
            return response()->json([
                'has_new_orders' => true,
                'orders' => $newOrders->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name,
                        'customer_phone' => $order->customer_phone,
                        'type' => $order->type,
                        'total' => $order->total,
                        'items_count' => $order->items->count(),
                        'created_at' => $order->created_at->format('H:i'),
                    ];
                })
            ]);
        }

        return response()->json([
            'has_new_orders' => false
        ]);
    }
}
