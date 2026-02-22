<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Category;
use App\Models\Tenant\DeliveryOrder;
use App\Models\Tenant\DeliveryOrderItem;
use Illuminate\Http\Request;

class OnlineOrderController extends Controller
{
    /**
     * Mostrar menú para pedidos online
     */
    public function index()
    {
        $restaurant = tenant()->restaurant();

        // Verificar si el restaurante acepta pedidos online
        if (!$restaurant->accepts_online_orders) {
            return view('tenant.online.unavailable', compact('restaurant'));
        }

        // Obtener categorías con productos activos y disponibles para delivery
        $categories = Category::with(['activeProducts' => function ($query) {
            $query->where('available_for_delivery', true)
                  ->orderBy('order')
                  ->orderBy('name');
        }])
        ->where('active', true)
        ->orderBy('order')
        ->orderBy('name')
        ->get();

        return view('tenant.online.index', compact('restaurant', 'categories'));
    }

    /**
     * Procesar pedido online
     */
    public function store(Request $request)
    {
        $restaurant = tenant()->restaurant();

        if (!$restaurant->accepts_online_orders) {
            return response()->json(['error' => 'Pedidos online no disponibles'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|in:delivery,pickup',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'delivery_address' => 'required_if:type,delivery|nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Calcular total
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryFee = $validated['type'] === 'delivery' ? ($restaurant->delivery_fee ?? 0) : 0;
        $total = $subtotal + $deliveryFee;

        // Crear pedido
        $order = DeliveryOrder::create([
            'order_number' => 'WEB-' . strtoupper(uniqid()),
            'type' => $validated['type'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'delivery_address' => $validated['delivery_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Crear items del pedido
        foreach ($validated['items'] as $item) {
            DeliveryOrderItem::create([
                'delivery_order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        return response()->json([
            'success' => true,
            'order' => $order,
            'message' => 'Pedido recibido correctamente'
        ]);
    }
}
