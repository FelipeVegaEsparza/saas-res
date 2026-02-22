<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'max_users' => 'nullable|integer|min:1',
            'max_tables' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);
        $validated['features'] = $request->input('features', []);

        // Si los campos están vacíos, usar valores por defecto
        $validated['max_users'] = $request->filled('max_users') ? $validated['max_users'] : 3;
        $validated['max_tables'] = $request->filled('max_tables') ? $validated['max_tables'] : 10;
        $validated['max_products'] = $request->filled('max_products') ? $validated['max_products'] : 50;

        Plan::create($validated);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan creado exitosamente');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);

        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'max_users' => 'nullable|integer|min:1',
            'max_tables' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        $validated['features'] = $request->input('features', []);

        // Si los campos están vacíos, usar valores por defecto
        $validated['max_users'] = $request->filled('max_users') ? $validated['max_users'] : 3;
        $validated['max_tables'] = $request->filled('max_tables') ? $validated['max_tables'] : 10;
        $validated['max_products'] = $request->filled('max_products') ? $validated['max_products'] : 50;

        $plan->update($validated);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan actualizado exitosamente');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);

        if ($plan->subscriptions()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un plan con suscripciones activas');
        }

        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan eliminado exitosamente');
    }
}
