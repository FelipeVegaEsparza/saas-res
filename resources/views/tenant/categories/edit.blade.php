@extends('tenant.layouts.admin')
@section('title', 'Editar Categoría')
@section('content')
<h1 class="mb-4">Editar Categoría</h1>
<div class="card">
    <div class="card-body">
        <form action="{{ route('tenant.path.categories.update', ['tenant' => request()->route('tenant'), 'category' => $category]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" class="form-control" name="order" value="{{ old('order', $category->order) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="active" value="1" {{ old('active', $category->active) ? 'checked' : '' }}>
                            <label class="form-check-label">Activa</label>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('tenant.path.categories.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
