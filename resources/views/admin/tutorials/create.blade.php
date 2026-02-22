@extends('admin.layouts.admin')

@section('title', 'Nuevo Tutorial - Admin')

@section('content')
<div class="mb-4">
    <h1 class="mb-1">Nuevo Tutorial</h1>
    <p class="text-muted">Agrega un nuevo video tutorial</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.tutorials.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="tutorial_category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select @error('tutorial_category_id') is-invalid @enderror"
                                id="tutorial_category_id" name="tutorial_category_id" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('tutorial_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tutorial_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="youtube_url" class="form-label">URL de YouTube <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                               id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}"
                               placeholder="https://www.youtube.com/watch?v=..." required>
                        <small class="text-muted">Pega la URL completa del video de YouTube</small>
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label">Orden</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror"
                               id="order" name="order" value="{{ old('order', 0) }}" min="0">
                        <small class="text-muted">Orden de visualización (menor número aparece primero)</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">Tutorial activo</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Tutorial
                        </button>
                        <a href="{{ route('admin.tutorials.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
