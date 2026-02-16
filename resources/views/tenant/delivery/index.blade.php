@extends('tenant.layouts.admin')

@section('title', 'Pedidos Delivery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Pedidos Delivery</h1>
        <p class="text-muted">Gestiona pedidos de delivery y para llevar</p>
    </div>
    <a href="{{ route('tenant.path.delivery.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nuevo Pedido
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Número, cliente, teléfono..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select name="type" class="form-select">
                    <option value="">Todos</option>
                    <option value="delivery" {{ request('type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="takeaway" {{ request('type') === 'takeaway' ? 'selected' : '' }}>Para Llevar</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparando</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Listo</option>
                    <option value="on_delivery" {{ request('status') === 'on_delivery' ? 'selected' : '' }}>En Camino</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregado</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="ri ri-search-line me-1"></i> Buscar
                </button>
                <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
                    <i class="ri ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de pedidos -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                                <br><small class="text-muted">{{ $order->items->count() }} items</small>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $order->type === 'delivery' ? 'primary' : 'info' }}">
                                    <i class="ri ri-{{ $order->type === 'delivery' ? 'e-bike-2' : 'shopping-bag' }}-line me-1"></i>
                                    {{ $order->type_label }}
                                </span>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_phone }}</td>
                            <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tenant.path.delivery.show', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order]) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-eye-line ri-20px"></i>
                                </a>
                                <form action="{{ route('tenant.path.delivery.destroy', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order]) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este pedido?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-center">
                                    <i class="ri ri-e-bike-2-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay pedidos registrados</p>
                                    <a href="{{ route('tenant.path.delivery.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary mt-2">
                                        <i class="ri ri-add-line me-1"></i> Crear Primer Pedido
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
