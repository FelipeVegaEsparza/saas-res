<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\TutorialCategory;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::with('category')->orderBy('order')->paginate(20);
        return view('admin.tutorials.index', compact('tutorials'));
    }

    public function create()
    {
        $categories = TutorialCategory::where('is_active', true)->orderBy('order')->get();
        return view('admin.tutorials.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tutorial_category_id' => 'required|exists:tutorial_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Tutorial::create($validated);

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutorial creado exitosamente');
    }

    public function edit(Tutorial $tutorial)
    {
        $categories = TutorialCategory::where('is_active', true)->orderBy('order')->get();
        return view('admin.tutorials.edit', compact('tutorial', 'categories'));
    }

    public function update(Request $request, Tutorial $tutorial)
    {
        $validated = $request->validate([
            'tutorial_category_id' => 'required|exists:tutorial_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $tutorial->update($validated);

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutorial actualizado exitosamente');
    }

    public function destroy(Tutorial $tutorial)
    {
        $tutorial->delete();

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutorial eliminado exitosamente');
    }
}
