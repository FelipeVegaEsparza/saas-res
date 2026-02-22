@extends('admin.layouts.admin')

@section('title', 'Editar Restaurante - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Editar Restaurante</h1>
            <p class="text-muted">{{ $restaurant->name }}</p>
        </div>
        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre del Restaurante *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $restaurant->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $restaurant->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $restaurant->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  rows="3">{{ old('address', $restaurant->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="active" id="active"
                                   {{ old('active', $restaurant->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">
                                Restaurante Activo
                            </label>
                        </div>
                        <small class="text-muted">Si está inactivo, el restaurante no podrá acceder al sistema</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Tenant ID</label>
                    <p class="mb-0"><code>{{ $restaurant->tenant->id ?? 'N/A' }}</code></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Fecha de Registro</label>
                    <p class="mb-0">{{ $restaurant->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Última Actualización</label>
                    <p class="mb-0">{{ $restaurant->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        @if($restaurant->activeSubscription)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Suscripción Actual</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ $restaurant->activeSubscription->plan->name }}</strong>
                </div>
                <div class="mb-2">
                    <span class="badge bg-label-{{ $restaurant->activeSubscription->status === 'active' ? 'success' : 'warning' }}">
                        {{ ucfirst($restaurant->activeSubscription->status) }}
                    </span>
                </div>
                <div class="text-muted small">
                    Vence: {{ $restaurant->activeSubscription->ends_at->format('d/m/Y') }}
                </div>
                <a href="{{ route('admin.subscriptions.edit', $restaurant->activeSubscription->id) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                    <i class="ri ri-edit-line me-1"></i>Editar Suscripción
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
