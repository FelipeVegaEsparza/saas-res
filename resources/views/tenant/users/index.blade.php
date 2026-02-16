@extends('tenant.layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Usuarios</h1>
    <a href="{{ route('tenant.path.users.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nuevo Usuario
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-label-{{ $user->role === 'owner' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info') }}">
                                    {{ $user->role_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->active ? 'success' : 'secondary' }}">
                                    {{ $user->active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('tenant.path.users.edit', ['tenant' => request()->route('tenant'), 'user' => $user]) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-edit-box-line ri-20px"></i>
                                </a>
                                <form action="{{ route('tenant.path.users.destroy', ['tenant' => request()->route('tenant'), 'user' => $user]) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este usuario?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-center">
                                    <i class="ri ri-user-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay usuarios registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
