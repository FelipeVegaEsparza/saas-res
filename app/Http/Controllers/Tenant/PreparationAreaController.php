<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\PreparationArea;
use Illuminate\Http\Request;

class PreparationAreaController extends Controller
{
    public function index()
    {
        $areas = PreparationArea::ordered()->get();
        return view('tenant.preparation-areas.index', compact('areas'));
    }

    public function create()
    {
        return view('tenant.preparation-areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['order'] = $validated['order'] ?? PreparationArea::max('order') + 1;
        $validated['active'] = $request->has('active') ? true : false;

        PreparationArea::create($validated);

        return redirect()
            ->route('tenant.path.preparation-areas.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Estación creada exitosamente');
    }

    public function edit($tenant, PreparationArea $preparation_area)
    {
        return view('tenant.preparation-areas.edit', compact('preparation_area'));
    }

    public function update(Request $request, $tenant, PreparationArea $preparation_area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['active'] = $request->has('active') ? true : false;

        $preparation_area->update($validated);

        return redirect()
            ->route('tenant.path.preparation-areas.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Estación actualizada exitosamente');
    }

    public function destroy($tenant, PreparationArea $preparation_area)
    {
        // Verificar si tiene productos asignados
        if ($preparation_area->products()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una estación que tiene productos asignados');
        }

        $preparation_area->delete();

        return redirect()
            ->route('tenant.path.preparation-areas.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Estación eliminada exitosamente');
    }
}
