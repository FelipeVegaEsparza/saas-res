<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestaurantCreated;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::with(['activeSubscription.plan']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('subscriptions', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $restaurants = $query->latest()->paginate(20);

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $plans = Plan::where('active', true)->get();
        return view('admin.restaurants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurants,slug',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
            'plan_id' => 'required|exists:plans,id',
            'trial_days' => 'nullable|integer|min:0|max:90',
        ]);

        try {
            $tenant = Tenant::create([
                'id' => $validated['slug'],
                'restaurant_name' => $validated['name'],
                'plan' => 'free',
            ]);

            $restaurant = Restaurant::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'domain' => $validated['slug'] . '.localhost',
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'active' => true,
                'db_name' => 'tenant_' . $validated['slug'],
                'db_username' => 'tenant_' . $validated['slug'],
                'db_password' => Str::random(16),
            ]);

            $plan = Plan::findOrFail($validated['plan_id']);
            $trialDays = (int) ($validated['trial_days'] ?? 0);

            $startsAt = now();
            if ($trialDays > 0) {
                $endsAt = now()->addDays($trialDays);
            } else {
                $endsAt = $plan->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth();
            }

            Subscription::create([
                'restaurant_id' => $restaurant->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'amount' => $plan->price,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            // Ejecutar migraciones automáticamente
            try {
                Artisan::call('tenant:migrate-direct', [
                    'tenant' => $validated['slug']
                ]);

                $migrateOutput = Artisan::output();
                Log::info("Migraciones ejecutadas para {$validated['slug']}: " . $migrateOutput);
            } catch (\Exception $e) {
                Log::error("Error en migraciones para {$validated['slug']}: " . $e->getMessage());
                throw new \Exception("Error al ejecutar migraciones: " . $e->getMessage());
            }

            // Crear usuario administrador con las credenciales proporcionadas
            try {
                Artisan::call('tenant:reset-user', [
                    'tenant' => $validated['slug'],
                    'email' => $validated['admin_email'],
                    'password' => $validated['admin_password']
                ]);

                // Actualizar el nombre del usuario
                $dbName = 'tenant_' . $validated['slug'];
                config(['database.connections.tenant.database' => $dbName]);
                DB::purge('tenant');

                DB::connection('tenant')->table('users')
                    ->where('email', $validated['admin_email'])
                    ->update(['name' => $validated['admin_name']]);

                Log::info("Usuario administrador creado para {$validated['slug']}: {$validated['admin_email']}");
            } catch (\Exception $e) {
                Log::error("Error al crear usuario para {$validated['slug']}: " . $e->getMessage());
                throw new \Exception("Error al crear usuario administrador: " . $e->getMessage());
            }

            // Ejecutar seeder para datos de demostración (categorías, productos, mesas)
            try {
                Artisan::call('tenant:seed-direct', [
                    'tenant' => $validated['slug']
                ]);

                $seedOutput = Artisan::output();
                Log::info("Seeder ejecutado para {$validated['slug']}: " . $seedOutput);
            } catch (\Exception $e) {
                Log::error("Error en seeder para {$validated['slug']}: " . $e->getMessage());
                throw new \Exception("Error al insertar datos de demostración: " . $e->getMessage());
            }

            $credentials = "
                <strong>Credenciales de acceso:</strong><br>
                URL: <a href='/{$validated['slug']}/login' target='_blank'>/{$validated['slug']}/login</a><br>
                Email: {$validated['admin_email']}<br>
                Password: (la que configuraste)
            ";

            // Enviar email de bienvenida con credenciales
            try {
                Mail::to($validated['admin_email'])->send(
                    new RestaurantCreated(
                        $validated['name'],
                        $validated['slug'],
                        $validated['admin_name'],
                        $validated['admin_email'],
                        $validated['admin_password'],
                        $plan->name,
                        $trialDays
                    )
                );
                Log::info("Email de bienvenida enviado a {$validated['admin_email']}");
            } catch (\Exception $e) {
                Log::error("Error al enviar email para {$validated['slug']}: " . $e->getMessage());
                // No lanzamos excepción para que no falle la creación del restaurante
            }

            return redirect()
                ->route('admin.restaurants.show', $restaurant->id)
                ->with('success', '¡Restaurante creado exitosamente! Se ha enviado un email con las credenciales de acceso a ' . $validated['admin_email'])
                ->with('info', $credentials);

        } catch (\Exception $e) {
            // Limpiar en caso de error
            if (isset($restaurant)) {
                try {
                    $restaurant->subscriptions()->delete();
                } catch (\Exception $ex) {}
            }

            if (isset($tenant)) {
                try {
                    // Intentar eliminar la base de datos si se creó
                    $dbName = 'tenant_' . $validated['slug'];
                    DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
                } catch (\Exception $ex) {}

                try {
                    $tenant->delete();
                } catch (\Exception $ex) {}
            }

            if (isset($restaurant)) {
                try {
                    $restaurant->forceDelete();
                } catch (\Exception $ex) {}
            }

            return back()->withInput()->with('error', 'Error al crear el restaurante: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $restaurant = Restaurant::with(['activeSubscription.plan', 'tenant'])->findOrFail($id);
        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function edit($id)
    {
        $restaurant = Restaurant::with('activeSubscription')->findOrFail($id);
        $plans = Plan::where('active', true)->get();
        return view('admin.restaurants.edit', compact('restaurant', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        $restaurant->update($validated);

        return redirect()
            ->route('admin.restaurants.show', $restaurant->id)
            ->with('success', 'Restaurante actualizado exitosamente');
    }

    public function destroy($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
            $dbName = $restaurant->db_name;

            $restaurant->subscriptions()->delete();

            if ($restaurant->tenant) {
                $restaurant->tenant->delete();
            }

            DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
            $restaurant->forceDelete();

            return redirect()
                ->route('admin.restaurants.index')
                ->with('success', 'Restaurante eliminado exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->active = !$restaurant->active;
        $restaurant->save();

        return back()->with('success', 'Estado actualizado');
    }
}
