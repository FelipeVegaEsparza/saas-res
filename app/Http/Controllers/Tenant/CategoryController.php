<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index($tenant)
    {
        $categories = Category::withCount('products')->latest()->paginate(20);
        return view('tenant.categories.index', compact('categories'));
    }

    public function create($tenant)
    {
        return view('tenant.categories.create');
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        // Generar slug único
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['active'] = $request->boolean('active', true);
        $validated['order'] = $validated['order'] ?? 0;

        Category::create($validated);

        return redirect()->route('tenant.path.categories.index', ['tenant' => $tenant])
            ->with('success', 'Categoría creada exitosamente');
    }

    public function edit($tenant, Category $category)
    {
        return view('tenant.categories.edit', compact('category'));
    }

    public function update(Request $request, $tenant, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        // Generar slug único (excluyendo la categoría actual)
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['active'] = $request->boolean('active');

        $category->update($validated);

        return redirect()->route('tenant.path.categories.index', ['tenant' => $tenant])
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy($tenant, Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una categoría con productos');
        }

        $category->delete();

        return redirect()->route('tenant.path.categories.index', ['tenant' => $tenant])
            ->with('success', 'Categoría eliminada exitosamente');
    }
}
