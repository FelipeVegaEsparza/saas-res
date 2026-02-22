<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Restaurant;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['restaurant', 'plan']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        $subscriptions = $query->latest()->paginate(20);
        $plans = Plan::all();

        return view('admin.subscriptions.index', compact('subscriptions', 'plans'));
    }

    public function show($id)
    {
        $subscription = Subscription::with(['restaurant', 'plan'])->findOrFail($id);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function edit($id)
    {
        $subscription = Subscription::with('restaurant')->findOrFail($id);
        $plans = Plan::where('active', true)->get();

        return view('admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,cancelled,expired,suspended',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $validated['amount'] = $plan->price;

        $subscription->update($validated);

        return redirect()
            ->route('admin.subscriptions.show', $subscription->id)
            ->with('success', 'Suscripción actualizada exitosamente');
    }

    public function cancel($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => 'cancelled']);

        return back()->with('success', 'Suscripción cancelada exitosamente');
    }

    public function renew($id)
    {
        $subscription = Subscription::findOrFail($id);

        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        return back()->with('success', 'Suscripción renovada exitosamente');
    }
}
