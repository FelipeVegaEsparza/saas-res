<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Product::where('track_stock', true)
            ->with('category')
            ->orderBy('name');

        if ($filter === 'low') {
            $query->whereRaw('stock_quantity <= min_stock');
        } elseif ($filter === 'out') {
            $query->where('stock_quantity', 0);
        }

        $products = $query->get();

        $stats = [
            'total_products' => Product::where('track_stock', true)->count(),
            'low_stock' => Product::where('track_stock', true)->whereRaw('stock_quantity <= min_stock')->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('track_stock', true)->where('stock_quantity', 0)->count(),
            'total_value' => Product::where('track_stock', true)->selectRaw('SUM(stock_quantity * price) as total')->value('total') ?? 0,
        ];

        return view('tenant.stock.index', compact('products', 'stats', 'filter'));
    }

    public function update(Request $request, $tenant, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $product->update([
            'stock_quantity' => $validated['stock_quantity'],
            'min_stock' => $validated['min_stock'],
        ]);

        // Registrar movimiento si se proporcionan notas
        if (!empty($validated['notes'])) {
            // Aquí podrías registrar el movimiento en una tabla de historial si lo deseas
        }

        return back()->with('success', 'Stock actualizado correctamente');
    }
}
