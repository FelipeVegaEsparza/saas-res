<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use App\Models\Tenant\PreparationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index($tenant)
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(20);

        return view('tenant.products.index', compact('products'));
    }

    public function create($tenant)
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $preparationAreas = PreparationArea::active()->ordered()->get();
        return view('tenant.products.create', compact('categories', 'preparationAreas'));
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:tenant.categories,id',
            'preparation_area_id' => 'required|exists:tenant.preparation_areas,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'available' => 'boolean',
            'featured' => 'boolean',
            'preparation_time' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'allergens' => 'nullable|array',
            'track_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        // Manejar subida de imagen
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['available'] = $request->boolean('available', true);
        $validated['available_for_delivery'] = $request->boolean('available_for_delivery', true);
        $validated['featured'] = $request->boolean('featured', false);
        $validated['track_stock'] = $request->boolean('track_stock', false);
        $validated['stock_quantity'] = $request->input('stock_quantity', 0);
        $validated['min_stock'] = $request->input('min_stock', 5);

        Product::create($validated);

        return redirect()->route('tenant.path.products.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Producto creado exitosamente');
    }

    public function edit($tenant, Product $product)
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $preparationAreas = PreparationArea::active()->ordered()->get();
        return view('tenant.products.edit', compact('product', 'categories', 'preparationAreas'));
    }

    public function update(Request $request, $tenant, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:tenant.categories,id',
            'preparation_area_id' => 'required|exists:tenant.preparation_areas,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'available' => 'boolean',
            'featured' => 'boolean',
            'preparation_time' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'allergens' => 'nullable|array',
            'track_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        // Manejar subida de imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['available'] = $request->boolean('available');
        $validated['available_for_delivery'] = $request->boolean('available_for_delivery');
        $validated['featured'] = $request->boolean('featured');
        $validated['track_stock'] = $request->boolean('track_stock', false);
        $validated['stock_quantity'] = $request->input('stock_quantity', 0);
        $validated['min_stock'] = $request->input('min_stock', 5);

        $product->update($validated);

        return redirect()->route('tenant.path.products.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy($tenant, Product $product)
    {
        // Eliminar imagen si existe
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('tenant.path.products.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Producto eliminado exitosamente');
    }
}
