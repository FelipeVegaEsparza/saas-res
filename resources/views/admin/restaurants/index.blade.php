@extends('admin.layouts.admin')

@section('title', 'Restaurantes - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Restaurantes</h1>
            <p class="text-muted">Gestiona todos los restaurantes del sistema</p>
        </div>
        <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nuevo Restaurante
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.restaurants.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelados</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirados</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-search-line me-1"></i>Buscar
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                        <i class="ri ri-refresh-line me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Restaurantes -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Restaurante</th>
                        <th>Contacto</th>
                        <th>Plan</th>
                        <th>Estado</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $restaurant->name }}</strong><br>
                                    <small class="text-muted">ID: {{ $restaurant->id }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    {{ $restaurant->email }}<br>
                                    @if($restaurant->phone)
                                        <small class="text-muted">{{ $restaurant->phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($restaurant->activeSubscription)
                                    <span class="badge bg-label-primary">{{ $restaurant->activeSubscription->plan->name }}</span><br>
                                    <small class="text-muted">{{ ucfirst($restaurant->activeSubscription->status) }}</small>
                                @else
                                    <span class="badge bg-label-secondary">Sin plan</span>
                                @endif
                            </td>
                            <td>
                                @if($restaurant->active)
                                    <span class="badge bg-label-success">Activo</span>
                                @else
                                    <span class="badge bg-label-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $restaurant->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ver">
                                        <i class="ri ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Editar">
                                        <i class="ri ri-edit-line"></i>
                                    </a>
                                    <a href="{{ route('admin.restaurants.editCredentials', $restaurant->id) }}" class="btn btn-sm btn-icon btn-text-info" title="Cambiar Credenciales">
                                        <i class="ri ri-lock-password-line"></i>
                                    </a>
                                    <form action="{{ route('admin.restaurants.toggleStatus', $restaurant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-{{ $restaurant->active ? 'warning' : 'success' }}" title="{{ $restaurant->active ? 'Desactivar' : 'Activar' }}">
                                            <i class="ri ri-{{ $restaurant->active ? 'pause' : 'play' }}-circle-line"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-icon btn-text-danger delete-restaurant"
                                            data-id="{{ $restaurant->id }}"
                                            data-name="{{ $restaurant->name }}"
                                            title="Eliminar">
                                        <i class="ri ri-delete-bin-line"></i>
                                    </button>
                                    <form id="delete-form-{{ $restaurant->id }}" action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No se encontraron restaurantes</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $restaurants->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Eliminar restaurante con SweetAlert2
    document.querySelectorAll('.delete-restaurant').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;

            Swal.fire({
                title: '¿Eliminar restaurante?',
                html: `¿Estás seguro de eliminar <strong>${name}</strong>?<br><br>
                       <small class="text-danger">Esta acción no se puede deshacer y eliminará:</small><br>
                       <small>• Todos los datos del tenant</small><br>
                       <small>• La base de datos completa</small><br>
                       <small>• Todas las suscripciones</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
});
</script>
@endpush
@endsection
