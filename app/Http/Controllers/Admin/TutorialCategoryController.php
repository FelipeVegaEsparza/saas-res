<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorialCategory;
use Illuminate\Http\Request;

class TutorialCategoryController extends Controller
{
    public function index()
    {
        $categories = TutorialCategory::withCount('tutorials')->orderBy('order')->paginate(20);
        return view('admin.tutorial-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.tutorial-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        TutorialCategory::create($validated);

        return redirect()->route('admin.tutorial-categories.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function edit(TutorialCategory $tutorialCategory)
    {
        return view('admin.tutorial-categories.edit', compact('tutorialCategory'));
    }

    public function update(Request $request, TutorialCategory $tutorialCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $tutorialCategory->update($validated);

        return redirect()->route('admin.tutorial-categories.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(TutorialCategory $tutorialCategory)
    {
        if ($tutorialCategory->tutorials()->count() > 0) {
            return redirect()->route('admin.tutorial-categories.index')
                ->with('error', 'No se puede eliminar una categoría con tutoriales asociados');
        }

        $tutorialCategory->delete();

        return redirect()->route('admin.tutorial-categories.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}
