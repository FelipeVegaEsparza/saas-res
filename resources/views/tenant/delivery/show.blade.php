@extends('tenant.layouts.admin')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Pedido {{ $deliveryOrder->order_number }}</h1>
            <p class="text-muted">{{ $deliveryOrder->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Información del pedido -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Información del Pedido</h5>
                <span class="badge bg-label-{{ $deliveryOrder->type === 'delivery' ? 'primary' : 'info' }}">
                    <i class="ri ri-{{ $deliveryOrder->type === 'delivery' ? 'e-bike-2' : 'shopping-bag' }}-line me-1"></i>
                    {{ $deliveryOrder->type_label }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Cliente</label>
                        <p class="mb-0"><strong>{{ $deliveryOrder->customer_name }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Teléfono</label>
                        <p class="mb-0">
                            <a href="tel:{{ $deliveryOrder->customer_phone }}">{{ $deliveryOrder->customer_phone }}</a>
                        </p>
                    </div>
                    @if($deliveryOrder->customer_email)
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $deliveryOrder->customer_email }}">{{ $deliveryOrder->customer_email }}</a>
                        </p>
                    </div>
                    @endif
                    @if($deliveryOrder->type === 'delivery')
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted small">Dirección de Entrega</label>
                        <p class="mb-0">{{ $deliveryOrder->delivery_address }}</p>
                    </div>
                    @if($deliveryOrder->delivery_zone)
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Zona</label>
                        <p class="mb-0">{{ $deliveryOrder->delivery_zone }}</p>
                    </div>
                    @endif
                    @endif
                    @if($deliveryOrder->notes)
                    <div class="col-12">
                        <label class="form-label text-muted small">Notas</label>
                        <p class="mb-0">{{ $deliveryOrder->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-end">Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveryOrder->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong>
                                    @if($item->notes)
                                        <br><small class="text-muted">{{ $item->notes }}</small>
                                    @endif
                                </td>
                                <td class="text-end">@price($item->price)</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end"><strong>@price($item->subtotal)</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end"><strong>@price($deliveryOrder->subtotal)</strong></td>
                            </tr>
                            @if($deliveryOrder->delivery_fee > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Envío:</strong></td>
                                <td class="text-end"><strong>${{ number_format($deliveryOrder->delivery_fee, 2) }}</strong></td>
                            </tr>
                            @endif
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                <td class="text-end"><h5 class="mb-0">@price($deliveryOrder->total)</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Estado del pedido -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Estado del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <span class="badge bg-{{ $deliveryOrder->status_color }} p-3" style="font-size: 1.1rem;">
                        {{ $deliveryOrder->status_label }}
                    </span>
                </div>

                <form action="{{ route('tenant.path.delivery.updateStatus', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $deliveryOrder]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Cambiar Estado</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $deliveryOrder->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmed" {{ $deliveryOrder->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                            <option value="preparing" {{ $deliveryOrder->status === 'preparing' ? 'selected' : '' }}>Preparando</option>
                            <option value="ready" {{ $deliveryOrder->status === 'ready' ? 'selected' : '' }}>Listo</option>
                            @if($deliveryOrder->type === 'delivery')
                            <option value="on_delivery" {{ $deliveryOrder->status === 'on_delivery' ? 'selected' : '' }}>En Camino</option>
                            @endif
                            <option value="delivered" {{ $deliveryOrder->status === 'delivered' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelled" {{ $deliveryOrder->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-refresh-line me-1"></i> Actualizar Estado
                    </button>
                </form>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Historial</h5>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Pedido Creado</h6>
                                <small class="text-muted">{{ $deliveryOrder->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </li>
                    @if($deliveryOrder->confirmed_at)
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-info"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Confirmado</h6>
                                <small class="text-muted">{{ $deliveryOrder->confirmed_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </li>
                    @endif
                    @if($deliveryOrder->ready_at)
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Listo</h6>
                                <small class="text-muted">{{ $deliveryOrder->ready_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </li>
                    @endif
                    @if($deliveryOrder->delivered_at)
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Entregado</h6>
                                <small class="text-muted">{{ $deliveryOrder->delivered_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
