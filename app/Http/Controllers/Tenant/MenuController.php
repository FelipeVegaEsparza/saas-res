<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Category;
use App\Models\Tenant\Table;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Mostrar menú público (carta digital)
     */
    public function index(Request $request)
    {
        $restaurant = tenant()->restaurant();

        // Obtener categorías con productos activos
        $categories = Category::with(['activeProducts' => function ($query) {
            $query->orderBy('order')->orderBy('name');
        }])
        ->where('active', true)
        ->orderBy('order')
        ->orderBy('name')
        ->get();

        // Obtener mesa si viene desde QR
        $table = null;
        if ($request->has('table')) {
            $table = Table::where('qr_code', $request->table)
                ->where('active', true)
                ->first();
        }

        return view('tenant.menu.index', compact('restaurant', 'categories', 'table'));
    }

    /**
     * Mostrar detalle de producto
     */
    public function show($slug)
    {
        $restaurant = tenant()->restaurant();

        $product = \App\Models\Tenant\Product::where('slug', $slug)
            ->where('available', true)
            ->with('category')
            ->firstOrFail();

        return view('tenant.menu.show', compact('restaurant', 'product'));
    }

    /**
     * Buscar productos
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = \App\Models\Tenant\Product::where('available', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('category')
            ->limit(20)
            ->get();

        return response()->json($products);
    }
}
