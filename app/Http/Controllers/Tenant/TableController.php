<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Table;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use App\Models\Tenant\PreparationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();
        return view('tenant.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tenant.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:tenant.tables,number',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'shape' => 'nullable|in:square,round,rectangle',
            'orientation' => 'nullable|in:horizontal,vertical',
            'size' => 'nullable|in:small,medium,large',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);
        $validated['status'] = 'available';
        $validated['shape'] = $validated['shape'] ?? 'square';
        $validated['orientation'] = $validated['orientation'] ?? 'horizontal';
        $validated['size'] = $validated['size'] ?? 'medium';

        Table::create($validated);

        return redirect()->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Mesa creada exitosamente');
    }

    public function edit($tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);
        return view('tenant.tables.edit', compact('table'));
    }

    public function update(Request $request, $tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);

        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:tenant.tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'shape' => 'nullable|in:square,round,rectangle',
            'orientation' => 'nullable|in:horizontal,vertical',
            'size' => 'nullable|in:small,medium,large',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        $table->update($validated);

        return redirect()->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Mesa actualizada exitosamente');
    }

    public function destroy(Request $request, $tenant, $table_id)
    {
        try {
            $table = Table::findOrFail($table_id);

            // Verificar si la mesa tiene pedidos activos
            $hasActiveOrder = $table->orders()
                ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
                ->exists();

            if ($hasActiveOrder) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar una mesa con pedidos activos'
                    ], 400);
                }

                return redirect()
                    ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                    ->with('error', 'No se puede eliminar una mesa con pedidos activos');
            }

            $table->delete();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mesa eliminada exitosamente'
                ]);
            }

            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('success', 'Mesa eliminada exitosamente');

        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la mesa: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('error', 'Error al eliminar la mesa: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para tomar pedido en una mesa
     */
    public function takeOrder($tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);

        // Obtener pedido activo de la mesa o crear uno nuevo
        $order = $table->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->with('items.product')
            ->first();

        $categories = Category::where('active', true)
            ->withCount('products')
            ->orderBy('order')
            ->get();

        $products = Product::where('available', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('tenant.tables.take-order', compact('table', 'order', 'categories', 'products'));
    }

    /**
     * Guardar o actualizar pedido de una mesa
     */
    public function storeOrder(Request $request, $tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:tenant.products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
            'kitchen_notes' => 'nullable|string',
        ]);

        DB::connection('tenant')->beginTransaction();

        try {
            // Buscar pedido activo o crear uno nuevo
            $order = $table->orders()
                ->whereIn('status', ['pending', 'preparing'])
                ->first();

            if (!$order) {
                $order = Order::create([
                    'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . $table->number,
                    'table_id' => $table->id,
                    'waiter_id' => Auth::id(),
                    'status' => 'pending',
                    'kitchen_notes' => $validated['kitchen_notes'] ?? null,
                    'subtotal' => 0,
                    'total' => 0,
                ]);

                $table->occupy();
            }

            // Agregar items al pedido
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Verificar si el item ya existe en el pedido
                $existingItem = $order->items()
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingItem) {
                    $existingItem->quantity += $item['quantity'];
                    $existingItem->subtotal = $existingItem->quantity * $existingItem->unit_price;
                    $existingItem->save();
                } else {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Recalcular totales
            $order->calculateTotals();

            DB::connection('tenant')->commit();

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pedido registrado exitosamente',
                    'order_id' => $order->id
                ]);
            }

            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('success', 'Pedido registrado exitosamente');

        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();

            // Si es una petición AJAX, devolver error JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el pedido: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al registrar el pedido: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Ver detalle del pedido de una mesa
     */
    public function showOrder($tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);

        $order = $table->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served', 'closed'])
            ->with(['items.product.preparationArea', 'waiter'])
            ->first();

        // Si no hay orden activa, redirigir al índice de mesas
        if (!$order) {
            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('info', 'Esta mesa no tiene un pedido activo');
        }

        // Obtener las áreas de preparación que tienen productos en este pedido
        $preparationAreas = PreparationArea::whereHas('products', function($query) use ($order) {
            $query->whereIn('id', $order->items->pluck('product_id'));
        })->active()->ordered()->get();

        return view('tenant.tables.show-order', compact('table', 'order', 'preparationAreas'));
    }

    /**
     * Imprimir comanda del pedido
     */
    public function printComanda($tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);

        $order = $table->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served', 'closed'])
            ->with(['items.product', 'waiter'])
            ->first();

        if (!$order) {
            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('error', 'Esta mesa no tiene un pedido activo');
        }

        return view('tenant.tables.print-comanda', compact('table', 'order'));
    }

    public function printComandaByArea($tenant, $table_id, $area_id)
    {
        $table = Table::findOrFail($table_id);
        $area = PreparationArea::findOrFail($area_id);

        $order = $table->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served', 'closed'])
            ->with(['items.product', 'waiter'])
            ->first();

        if (!$order) {
            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('error', 'Esta mesa no tiene un pedido activo');
        }

        // Filtrar solo los items que pertenecen a esta área
        $items = $order->items()->whereHas('product', function($query) use ($area_id) {
            $query->where('preparation_area_id', $area_id);
        })->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No hay productos de esta estación en el pedido');
        }

        return view('tenant.tables.print-comanda-area', compact('table', 'order', 'area', 'items'));
    }


    /**
     * Actualizar estado del pedido
     */
    public function updateOrderStatus(Request $request, $tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);

        $validated = $request->validate([
            'status' => 'required|in:preparing,ready,served,closed,cancelled',
        ]);

        $order = $table->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->firstOrFail();

        $order->update(['status' => $validated['status']]);

        // Actualizar timestamps según el estado
        if ($validated['status'] === 'preparing' && !$order->preparing_at) {
            $order->update(['preparing_at' => now()]);
        } elseif ($validated['status'] === 'ready' && !$order->ready_at) {
            $order->update(['ready_at' => now()]);
        } elseif ($validated['status'] === 'served' && !$order->served_at) {
            $order->update(['served_at' => now()]);
        } elseif ($validated['status'] === 'closed' && !$order->closed_at) {
            $order->update(['closed_at' => now()]);
        } elseif ($validated['status'] === 'cancelled') {
            // Liberar la mesa cuando se cancela el pedido
            $table->free();

            return redirect()
                ->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
                ->with('success', 'Pedido cancelado exitosamente');
        }

        return back()->with('success', 'Estado del pedido actualizado');
    }

    /**
     * Sincronizar estado de todas las mesas
     */
    public function syncStatus($tenant)
    {
        $tables = Table::all();
        $updated = 0;

        foreach ($tables as $table) {
            $hasActiveOrder = $table->orders()
                ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
                ->exists();

            $expectedStatus = $hasActiveOrder ? 'occupied' : 'available';

            if ($table->status !== $expectedStatus) {
                $table->update(['status' => $expectedStatus]);
                $updated++;
            }
        }

        return redirect()
            ->route('tenant.path.tables.index', ['tenant' => $tenant])
            ->with('success', "Estado sincronizado. {$updated} mesas actualizadas.");
    }

    /**
     * Actualizar posiciones de las mesas en el mapa
     */
    public function updatePositions(Request $request, $tenant)
    {
        $validated = $request->validate([
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:tenant.tables,id',
            'positions.*.x' => 'required|integer|min:0',
            'positions.*.y' => 'required|integer|min:0',
        ]);

        try {
            foreach ($validated['positions'] as $position) {
                Table::where('id', $position['id'])->update([
                    'position_x' => $position['x'],
                    'position_y' => $position['y'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Posiciones actualizadas exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar posiciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar forma de la mesa
     */
    public function updateShape(Request $request, $tenant, $table_id)
    {
        $validated = $request->validate([
            'shape' => 'required|in:square,round,rectangle',
        ]);

        try {
            $table = Table::findOrFail($table_id);
            $table->update(['shape' => $validated['shape']]);

            return response()->json([
                'success' => true,
                'message' => 'Forma actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar forma: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar orientación de la mesa
     */
    public function updateOrientation(Request $request, $tenant, $table_id)
    {
        $validated = $request->validate([
            'orientation' => 'required|in:horizontal,vertical',
        ]);

        try {
            $table = Table::findOrFail($table_id);
            $table->update(['orientation' => $validated['orientation']]);

            return response()->json([
                'success' => true,
                'message' => 'Orientación actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar orientación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar tamaño de la mesa
     */
    public function updateSize(Request $request, $tenant, $table_id)
    {
        $validated = $request->validate([
            'size' => 'required|in:small,medium,large',
        ]);

        try {
            $table = Table::findOrFail($table_id);
            $table->update(['size' => $validated['size']]);

            return response()->json([
                'success' => true,
                'message' => 'Tamaño actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tamaño: ' . $e->getMessage()
            ], 500);
        }
    }
}
