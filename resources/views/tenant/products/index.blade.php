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
                            <td>${{ number_format($product->price, 2) }}</td>
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
