<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\DeliveryOrder;
use App\Models\Tenant\Product;
use App\Models\Tenant\PreparationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index(Request $request, $tenant)
    {
        $query = DeliveryOrder::with('items.product')->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(20);

        return view('tenant.delivery.index', compact('orders'));
    }

    public function create($tenant)
    {
        // Verificar que haya una sesión de caja abierta
        $activeSession = \App\Models\Tenant\CashSession::where('status', 'open')->first();

        if (!$activeSession) {
            return redirect()
                ->route('tenant.path.cash.index', ['tenant' => request()->route('tenant')])
                ->with('error', 'Debes abrir una sesión de caja antes de crear pedidos de delivery');
        }

        $products = Product::where('available', true)->orderBy('name')->get();
        return view('tenant.delivery.create', compact('products'));
    }

    public function store(Request $request, $tenant)
    {
        // Verificar que haya una sesión de caja abierta
        $activeSession = \App\Models\Tenant\CashSession::where('status', 'open')->first();

        if (!$activeSession) {
            return back()
                ->with('error', 'Debes abrir una sesión de caja antes de crear pedidos de delivery')
                ->withInput();
        }

        $validated = $request->validate([
            'type' => 'required|in:delivery,takeaway',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'delivery_address' => 'required_if:type,delivery|nullable|string',
            'delivery_zone' => 'nullable|string|max:255',
            'delivery_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:tenant.products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::connection('tenant')->beginTransaction();

        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            $deliveryFee = $validated['type'] === 'delivery' ? ($validated['delivery_fee'] ?? 0) : 0;
            $total = $subtotal + $deliveryFee;

            $order = DeliveryOrder::create([
                'order_number' => DeliveryOrder::generateOrderNumber(),
                'type' => $validated['type'],
                'status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_zone' => $validated['delivery_zone'] ?? null,
                'delivery_fee' => $deliveryFee,
                'subtotal' => $subtotal,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            $order->items()->createMany($itemsData);

            DB::connection('tenant')->commit();

            return redirect()
                ->route('tenant.path.delivery.show', ['tenant' => $tenant, 'deliveryOrder' => $order])
                ->with('success', 'Pedido creado exitosamente');

        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            return back()->with('error', 'Error al crear el pedido: ' . $e->getMessage())->withInput();
        }
    }

    public function show($tenant, DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items.product.preparationArea');

        // Obtener las áreas de preparación que tienen productos en este pedido
        $preparationAreas = PreparationArea::whereHas('products', function($query) use ($deliveryOrder) {
            $query->whereIn('id', $deliveryOrder->items->pluck('product_id'));
        })->active()->ordered()->get();

        return view('tenant.delivery.show', compact('deliveryOrder', 'preparationAreas'));
    }

    public function updateStatus(Request $request, $tenant, DeliveryOrder $deliveryOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,on_delivery,delivered,cancelled',
        ]);

        $deliveryOrder->update(['status' => $validated['status']]);

        // Actualizar timestamps según el estado
        if ($validated['status'] === 'confirmed' && !$deliveryOrder->confirmed_at) {
            $deliveryOrder->update(['confirmed_at' => now()]);
        } elseif ($validated['status'] === 'ready' && !$deliveryOrder->ready_at) {
            $deliveryOrder->update(['ready_at' => now()]);
        } elseif ($validated['status'] === 'delivered' && !$deliveryOrder->delivered_at) {
            $deliveryOrder->update(['delivered_at' => now()]);
        }

        return back()->with('success', 'Estado actualizado correctamente');
    }

    public function printComanda($tenant, DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items.product');
        return view('tenant.delivery.print-comanda', compact('deliveryOrder'));
    }

    public function printComandaByArea($tenant, DeliveryOrder $deliveryOrder, $area_id)
    {
        $area = PreparationArea::findOrFail($area_id);
        $deliveryOrder->load('items.product');

        // Filtrar solo los items que pertenecen a esta área
        $items = $deliveryOrder->items()->whereHas('product', function($query) use ($area_id) {
            $query->where('preparation_area_id', $area_id);
        })->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No hay productos de esta estación en el pedido');
        }

        return view('tenant.delivery.print-comanda-area', compact('deliveryOrder', 'area', 'items'));
    }


    public function destroy($tenant, DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->delete();
        return redirect()
            ->route('tenant.path.delivery.index', ['tenant' => $tenant])
            ->with('success', 'Pedido eliminado correctamente');
    }
}
