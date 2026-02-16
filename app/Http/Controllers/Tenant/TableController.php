<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Table;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::withCount(['orders' => function($query) {
            $query->whereIn('status', ['pending', 'preparing', 'ready', 'served']);
        }])
        ->orderBy('number')
        ->get();

        return view('tenant.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tenant.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:tables,number',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);
        $validated['status'] = 'available';

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
            'number' => 'required|string|max:255|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        $table->update($validated);

        return redirect()->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Mesa actualizada exitosamente');
    }

    public function destroy($tenant, $table_id)
    {
        $table = Table::findOrFail($table_id);
        $table->delete();

        return redirect()->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Mesa eliminada exitosamente');
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
            'items.*.product_id' => 'required|exists:products,id',
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
                    $existingItem->subtotal = $existingItem->quantity * $existingItem->price;
                    $existingItem->save();
                } else {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
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
            ->with(['items.product', 'waiter'])
            ->firstOrFail();

        return view('tenant.tables.show-order', compact('table', 'order'));
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
        }

        return back()->with('success', 'Estado del pedido actualizado');
    }
}
