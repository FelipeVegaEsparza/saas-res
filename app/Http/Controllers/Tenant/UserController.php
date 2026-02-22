<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index($tenant)
    {
        $users = User::latest()->paginate(20);
        return view('tenant.users.index', compact('users'));
    }

    public function create($tenant)
    {
        return view('tenant.users.create');
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenant.users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:owner,manager,staff,waiter',
            'active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['active'] = $request->boolean('active', true);

        User::create($validated);

        return redirect()->route('tenant.path.users.index', ['tenant' => $tenant])
            ->with('success', 'Usuario creado exitosamente');
    }

    public function edit($tenant, User $user)
    {
        return view('tenant.users.edit', compact('user'));
    }

    public function update(Request $request, $tenant, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenant.users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => 'required|in:owner,manager,staff,waiter',
            'active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['active'] = $request->boolean('active');

        $user->update($validated);

        return redirect()->route('tenant.path.users.index', ['tenant' => $tenant])
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($tenant, User $user)
    {
        // Prevenir eliminar el último usuario activo
        if (User::active()->count() <= 1 && $user->active) {
            return back()->with('error', 'No se puede eliminar el último usuario activo');
        }

        $user->delete();

        return redirect()->route('tenant.path.users.index', ['tenant' => $tenant])
            ->with('success', 'Usuario eliminado exitosamente');
    }

    public function show($tenant, User $user)
    {
        return redirect()->route('tenant.path.users.edit', ['tenant' => $tenant, 'user' => $user]);
    }
}
