@extends('tenant.layouts.admin')

@section('title', 'Nueva Estación')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Nueva Estación de Preparación</h1>
            <p class="text-muted">Crea una nueva área de preparación</p>
        </div>
        <a href="{{ route('tenant.path.preparation-areas.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tenant.path.preparation-areas.store', ['tenant' => request()->route('tenant')]) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror"
                                   id="color" name="color" value="{{ old('color', '#667eea') }}" required>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="order" class="form-label">Orden</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Orden de aparición en el menú</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icono <span class="text-danger">*</span></label>
                        <select class="form-select @error('icon') is-invalid @enderror" id="icon" name="icon" required>
                            <option value="">Seleccionar icono</option>
                            <option value="ri-restaurant-2-line" {{ old('icon') === 'ri-restaurant-2-line' ? 'selected' : '' }}>🍳 Cocina</option>
                            <option value="ri-goblet-line" {{ old('icon') === 'ri-goblet-line' ? 'selected' : '' }}>🍹 Barra</option>
                            <option value="ri-cake-3-line" {{ old('icon') === 'ri-cake-3-line' ? 'selected' : '' }}>🍰 Postres</option>
                            <option value="ri-bowl-line" {{ old('icon') === 'ri-bowl-line' ? 'selected' : '' }}>🥗 Ensaladas</option>
                            <option value="ri-fire-line" {{ old('icon') === 'ri-fire-line' ? 'selected' : '' }}>🔥 Parrilla</option>
                            <option value="ri-cup-line" {{ old('icon') === 'ri-cup-line' ? 'selected' : '' }}>☕ Cafetería</option>
                            <option value="ri-knife-line" {{ old('icon') === 'ri-knife-line' ? 'selected' : '' }}>🔪 Preparación</option>
                        </select>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="active" name="active"
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Estación Activa</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i> Crear Estación
                        </button>
                        <a href="{{ route('tenant.path.preparation-areas.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
