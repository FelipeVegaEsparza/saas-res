@extends('tenant.layouts.admin')
@section('title', 'Editar Mesa')
@section('content')
<h1 class="mb-4">Editar Mesa</h1>
<div class="card">
    <div class="card-body">
        <form action="{{ route('tenant.path.tables.update', ['tenant' => request()->route('tenant'), 'table_id' => $table->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Número *</label>
                        <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ old('number', $table->number) }}" required>
                        @error('number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Capacidad *</label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity', $table->capacity) }}" required>
                        @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Forma</label>
                        <select class="form-select" name="shape" id="shapeSelect">
                            <option value="square" {{ old('shape', $table->shape ?? 'square') === 'square' ? 'selected' : '' }}>Cuadrada</option>
                            <option value="round" {{ old('shape', $table->shape) === 'round' ? 'selected' : '' }}>Redonda</option>
                            <option value="rectangle" {{ old('shape', $table->shape) === 'rectangle' ? 'selected' : '' }}>Rectangular</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tamaño</label>
                        <select class="form-select" name="size">
                            <option value="small" {{ old('size', $table->size) === 'small' ? 'selected' : '' }}>Pequeño</option>
                            <option value="medium" {{ old('size', $table->size ?? 'medium') === 'medium' ? 'selected' : '' }}>Mediano</option>
                            <option value="large" {{ old('size', $table->size) === 'large' ? 'selected' : '' }}>Grande</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Orientación</label>
                        <select class="form-select" name="orientation" id="orientationSelect">
                            <option value="horizontal" {{ old('orientation', $table->orientation ?? 'horizontal') === 'horizontal' ? 'selected' : '' }}>Horizontal</option>
                            <option value="vertical" {{ old('orientation', $table->orientation) === 'vertical' ? 'selected' : '' }}>Vertical</option>
                        </select>
                        <small class="text-muted">Solo aplica a mesas rectangulares</small>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" name="location" value="{{ old('location', $table->location) }}">
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="active" value="1" {{ old('active', $table->active) ? 'checked' : '' }}>
                    <label class="form-check-label">Activa</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('tenant.path.tables.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
