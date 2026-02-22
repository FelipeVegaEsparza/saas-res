@extends('admin.layouts.admin')

@section('title', 'Editar Plan - Admin')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">Planes</a></li>
            <li class="breadcrumb-item active">Editar Plan</li>
        </ol>
    </nav>
    <h1 class="mb-1">Editar Plan: {{ $plan->name }}</h1>
    <p class="text-muted">Modifica los detalles del plan de suscripción</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nombre del Plan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                   id="slug" name="slug" value="{{ old('slug', $plan->slug) }}" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Ej: plan-basico</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', $plan->price) }}" min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="billing_cycle" class="form-label">Ciclo de Facturación</label>
                            <select class="form-select @error('billing_cycle') is-invalid @enderror"
                                    id="billing_cycle" name="billing_cycle" required>
                                <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle) == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle) == 'yearly' ? 'selected' : '' }}>Anual</option>
                            </select>
                            @error('billing_cycle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="max_users" class="form-label">Máximo de Usuarios</label>
                            <input type="number" class="form-control @error('max_users') is-invalid @enderror"
                                   id="max_users" name="max_users" value="{{ old('max_users', $plan->max_users) }}" min="1">
                            @error('max_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Dejar vacío para ilimitado</small>
                        </div>
                        <div class="col-md-4">
                            <label for="max_tables" class="form-label">Máximo de Mesas</label>
                            <input type="number" class="form-control @error('max_tables') is-invalid @enderror"
                                   id="max_tables" name="max_tables" value="{{ old('max_tables', $plan->max_tables) }}" min="1">
                            @error('max_tables')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Dejar vacío para ilimitado</small>
                        </div>
                        <div class="col-md-4">
                            <label for="max_products" class="form-label">Máximo de Productos</label>
                            <input type="number" class="form-control @error('max_products') is-invalid @enderror"
                                   id="max_products" name="max_products" value="{{ old('max_products', $plan->max_products) }}" min="1">
                            @error('max_products')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Dejar vacío para ilimitado</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Características</label>
                        <div id="features-container">
                            @if(old('features') || ($plan->features && count($plan->features) > 0))
                                @foreach(old('features', $plan->features ?? []) as $feature)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="features[]" value="{{ $feature }}" placeholder="Ej: Soporte 24/7">
                                        <button type="button" class="btn btn-outline-danger remove-feature">
                                            <i class="ri ri-close-line"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="features[]" placeholder="Ej: Soporte 24/7">
                                    <button type="button" class="btn btn-outline-danger remove-feature" style="display: none;">
                                        <i class="ri ri-close-line"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature">
                            <i class="ri ri-add-line me-1"></i>Agregar Característica
                        </button>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                   {{ old('active', $plan->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Plan Activo</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-label-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ri ri-information-line me-2"></i>Información
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        El slug debe ser único y sin espacios
                    </li>
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        Los límites vacíos significan ilimitado
                    </li>
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        Las características se muestran en el sitio público
                    </li>
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        Los planes inactivos no se muestran en el sitio
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ri ri-bar-chart-line me-2"></i>Estadísticas
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block">Suscripciones Activas</small>
                    <h4 class="mb-0">{{ $plan->subscriptions()->count() }}</h4>
                </div>
                @if($plan->subscriptions()->count() > 0)
                    <div class="alert alert-warning mb-0">
                        <i class="ri ri-alert-line me-2"></i>
                        <small>Este plan tiene suscripciones activas. Los cambios pueden afectar a los clientes.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');

    // Add feature
    addFeatureBtn.addEventListener('click', function() {
        const newFeature = document.createElement('div');
        newFeature.className = 'input-group mb-2';
        newFeature.innerHTML = `
            <input type="text" class="form-control" name="features[]" placeholder="Ej: Soporte 24/7">
            <button type="button" class="btn btn-outline-danger remove-feature">
                <i class="ri ri-close-line"></i>
            </button>
        `;
        featuresContainer.appendChild(newFeature);
        updateRemoveButtons();
    });

    // Remove feature
    featuresContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const features = featuresContainer.querySelectorAll('.input-group');
        features.forEach((feature, index) => {
            const removeBtn = feature.querySelector('.remove-feature');
            if (features.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // Initialize remove buttons visibility
    updateRemoveButtons();
});
</script>
@endpush
@endsection
