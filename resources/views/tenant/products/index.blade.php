@extends('tenant.layouts.admin')

@section('title', 'Productos')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Productos</h1>
    <a href="{{ route('tenant.path.products.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nuevo Producto
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tenant.path.products.index', ['tenant' => request()->route('tenant')]) }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre del producto..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Categoría</label>
                    <select name="category" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Disponibilidad</label>
                    <select name="available" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('available') === '1' ? 'selected' : '' }}>Disponible</option>
                        <option value="0" {{ request('available') === '0' ? 'selected' : '' }}>No disponible</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Destacado</label>
                    <select name="featured" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Destacados</option>
                        <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>No destacados</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-search-line"></i>
                    </button>
                </div>
            </div>
            @if(request()->hasAny(['search', 'category', 'available', 'featured']))
                <div class="mt-3">
                    <a href="{{ route('tenant.path.products.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri ri-close-line me-1"></i> Limpiar filtros
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="ri ri-image-line text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->description)
                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>@price($product->price)</td>
                            <td>
                                <span class="badge bg-{{ $product->available ? 'success' : 'secondary' }}">
                                    {{ $product->available ? 'Disponible' : 'No disponible' }}
                                </span>
                            </td>
                            <td>
                                @if($product->featured)
                                    <i class="ri ri-star-fill text-warning"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tenant.path.products.edit', ['tenant' => request()->route('tenant'), 'product' => $product]) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-edit-box-line ri-20px"></i>
                                </a>
                                <form action="{{ route('tenant.path.products.destroy', ['tenant' => request()->route('tenant'), 'product' => $product]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-center">
                                    <i class="ri ri-restaurant-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay productos registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
