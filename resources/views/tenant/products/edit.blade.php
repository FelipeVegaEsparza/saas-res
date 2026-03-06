@extends('tenant.layouts.admin')

@section('title', 'Editar Producto')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="mb-4">
    <h1>Editar Producto</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('tenant.path.products.update', ['tenant' => request()->route('tenant'), 'product' => $product]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Categoría *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preparation_area_id" class="form-label">Estación de Preparación *</label>
                                <select class="form-select @error('preparation_area_id') is-invalid @enderror"
                                        id="preparation_area_id" name="preparation_area_id" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($preparationAreas as $area)
                                        <option value="{{ $area->id }}"
                                                {{ old('preparation_area_id', $product->preparation_area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('preparation_area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Precio *</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preparation_time" class="form-label">Tiempo de Preparación (minutos)</label>
                                <input type="number" class="form-control @error('preparation_time') is-invalid @enderror"
                                       id="preparation_time" name="preparation_time" value="{{ old('preparation_time', $product->preparation_time) }}">
                                @error('preparation_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Imagen del Producto</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: JPG, PNG, WEBP. Máx: 2MB</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="available" name="available" value="1"
                                   {{ old('available', $product->available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="available">Disponible</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1"
                                   {{ old('featured', $product->featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">Destacado</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="available_for_delivery" name="available_for_delivery" value="1"
                                   {{ old('available_for_delivery', $product->available_for_delivery) ? 'checked' : '' }}>
                            <label class="form-check-label" for="available_for_delivery">Disponible para Delivery</label>
                        </div>
                        <small class="text-muted">El producto aparecerá en pedidos online</small>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="track_stock" name="track_stock" value="1"
                                   {{ old('track_stock', $product->track_stock) ? 'checked' : '' }}>
                            <label class="form-check-label" for="track_stock">Seguir Stock</label>
                        </div>
                        <small class="text-muted">Activar control de inventario</small>
                    </div>

                    <div id="stock-fields" style="display: {{ old('track_stock', $product->track_stock) ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Cantidad en Stock</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="min_stock" class="form-label">Stock Mínimo</label>
                            <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                   id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0">
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Alerta cuando el stock esté bajo</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('tenant.path.products.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trackStockCheckbox = document.getElementById('track_stock');
    const stockFields = document.getElementById('stock-fields');

    function toggleStockFields() {
        if (trackStockCheckbox.checked) {
            stockFields.style.display = 'block';
        } else {
            stockFields.style.display = 'none';
        }
    }

    trackStockCheckbox.addEventListener('change', toggleStockFields);
    toggleStockFields();
});
</script>
@endpush
