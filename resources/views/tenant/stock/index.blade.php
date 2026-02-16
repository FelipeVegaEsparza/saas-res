@extends('tenant.layouts.admin')

@section('title', 'Control de Stock')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Control de Stock</h1>
            <p class="text-muted">Gestiona el inventario de productos</p>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Productos con Stock</p>
                        <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-primary">
                        <i class="ri ri-archive-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Stock Bajo</p>
                        <h3 class="mb-0">{{ $stats['low_stock'] }}</h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-warning">
                        <i class="ri ri-alert-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Sin Stock</p>
                        <h3 class="mb-0">{{ $stats['out_of_stock'] }}</h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-danger">
                        <i class="ri ri-close-circle-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Valor Total</p>
                        <h3 class="mb-0">${{ number_format($stats['total_value'], 2) }}</h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-success">
                        <i class="ri ri-money-dollar-circle-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Lista -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Inventario</h5>
        <div class="btn-group" role="group">
            <a href="{{ route('tenant.path.stock.index', ['tenant' => request()->route('tenant')]) }}"
               class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                Todos
            </a>
            <a href="{{ route('tenant.path.stock.index', ['tenant' => request()->route('tenant'), 'filter' => 'low']) }}"
               class="btn btn-sm {{ $filter === 'low' ? 'btn-warning' : 'btn-outline-warning' }}">
                Stock Bajo
            </a>
            <a href="{{ route('tenant.path.stock.index', ['tenant' => request()->route('tenant'), 'filter' => 'out']) }}"
               class="btn btn-sm {{ $filter === 'out' ? 'btn-danger' : 'btn-outline-danger' }}">
                Sin Stock
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock Actual</th>
                            <th class="text-center">Stock Mínimo</th>
                            <th class="text-center">Estado</th>
                            <th>Precio</th>
                            <th>Valor Total</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri ri-image-line"></i>
                                                </span>
                                            </div>
                                        @endif
                                        <span class="fw-medium">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $product->category->name }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ $product->stock_quantity }}</span>
                                </td>
                                <td class="text-center">
                                    {{ $product->min_stock }}
                                </td>
                                <td class="text-center">
                                    @if($product->isOutOfStock())
                                        <span class="badge bg-danger">Sin Stock</span>
                                    @elseif($product->hasLowStock())
                                        <span class="badge bg-warning">Stock Bajo</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                </td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td class="fw-medium">${{ number_format($product->price * $product->stock_quantity, 2) }}</td>
                                <td class="text-end">
                                    <button type="button"
                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStockModal{{ $product->id }}">
                                        <i class="ri ri-edit-line"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal para editar stock -->
                            <div class="modal fade" id="editStockModal{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Actualizar Stock: {{ $product->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('tenant.path.stock.update', ['tenant' => request()->route('tenant'), 'id' => $product->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Stock Actual</label>
                                                    <input type="number" name="stock_quantity" class="form-control"
                                                           value="{{ $product->stock_quantity }}" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Stock Mínimo</label>
                                                    <input type="number" name="min_stock" class="form-control"
                                                           value="{{ $product->min_stock }}" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Notas (opcional)</label>
                                                    <textarea name="notes" class="form-control" rows="2"
                                                              placeholder="Ej: Reposición de inventario"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="ri ri-archive-line ri-48px"></i>
                    </span>
                </div>
                <h5 class="mb-1">No hay productos con seguimiento de stock</h5>
                <p class="text-muted mb-4">Activa el seguimiento de stock en los productos que desees controlar</p>
                <a href="{{ route('tenant.path.products.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                    <i class="ri ri-settings-line me-1"></i> Ir a Productos
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
