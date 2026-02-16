@extends('tenant.layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-1">Dashboard</h1>
            <p class="text-muted">{{ $restaurant->name }}</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Órdenes Hoy</p>
                            <h3 class="mb-0">{{ $stats['orders_today'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-label-primary">
                            <i class="ri ri-file-list-3-line ri-26px"></i>
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
                            <p class="text-muted mb-1">Ingresos Hoy</p>
                            <h3 class="mb-0">${{ number_format($stats['revenue_today'], 2) }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-label-success">
                            <i class="ri ri-money-dollar-circle-line ri-26px"></i>
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
                            <p class="text-muted mb-1">Delivery Hoy</p>
                            <h3 class="mb-0">{{ $stats['delivery_orders_today'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-label-info">
                            <i class="ri ri-e-bike-2-line ri-26px"></i>
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
                            <p class="text-muted mb-1">Pedidos Pendientes</p>
                            <h3 class="mb-0">{{ $stats['delivery_pending'] }}</h3>
                        </div>
                        <div class="avatar avatar-lg bg-label-warning">
                            <i class="ri ri-time-line ri-26px"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Órdenes Recientes -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Órdenes Recientes</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <p class="text-muted text-center py-4">No hay órdenes recientes</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Mesa</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->table->number ?? 'N/A' }}</td>
                                            <td>{{ $order->items->count() }} items</td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'preparing' => 'info',
                                                        'ready' => 'primary',
                                                        'delivered' => 'success',
                                                        'cancelled' => 'danger',
                                                    ];
                                                    $color = $statusColors[$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td>{{ $order->created_at->format('H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Productos (7 días)</h5>
                </div>
                <div class="card-body">
                    @if($topProducts->isEmpty())
                        <p class="text-muted text-center py-4">Sin datos</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $product)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            <small class="text-muted">${{ number_format($product->price, 2) }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $product->order_items_count }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Acceso Rápido -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Acceso Rápido</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-primary">
                            <i class="ri ri-e-bike-2-line"></i> Pedidos Delivery
                        </a>
                        <a href="{{ route('tenant.path.menu.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="ri ri-restaurant-line"></i> Ver Menú Público
                        </a>
                        <a href="{{ route('tenant.path.qr.print-all', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary" target="_blank">
                            <i class="ri ri-qr-code-line"></i> Imprimir QR Mesas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Delivery Recientes -->
    @if($recentDeliveryOrders->isNotEmpty())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pedidos Delivery Recientes</h5>
                    <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-sm btn-primary">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Hora</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDeliveryOrders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>
                                            <span class="badge bg-label-{{ $order->type === 'delivery' ? 'primary' : 'info' }}">
                                                {{ $order->type_label }}
                                            </span>
                                        </td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('H:i') }}</td>
                                        <td>
                                            <a href="{{ route('tenant.path.delivery.show', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order]) }}"
                                               class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                                <i class="ri ri-eye-line ri-20px"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
