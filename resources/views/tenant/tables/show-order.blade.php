@extends('tenant.layouts.admin')

@section('title', 'Pedido - Mesa ' . $table->number)

@section('content')
<!-- Header con info de la mesa -->
<div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div>
                        <h3 class="mb-0">
                            <i class="ri ri-table-line me-2"></i>Mesa {{ $table->number }}
                        </h3>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <small class="opacity-75">Pedido:</small>
                        <strong class="ms-1">{{ $order->order_number }}</strong>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <span class="badge bg-{{ $order->statusColor }}">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('tenant.path.tables.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-light btn-sm">
                    <i class="ri ri-arrow-left-line me-1"></i> Volver a Mesas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Detalle del Pedido -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Items del Pedido</h5>
                @if($order->canBeEdited())
                    <a href="{{ route('tenant.path.tables.takeOrder', ['tenant' => request()->route('tenant'), 'table_id' => $table->id]) }}"
                       class="btn btn-sm btn-primary">
                        <i class="ri ri-add-line me-1"></i> Agregar Items
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                        @if($item->notes)
                                            <br><small class="text-muted">{{ $item->notes }}</small>
                                        @endif
                                    </td>
                                    <td>@price($item->price)</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end"><strong>@price($item->subtotal)</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><h5 class="mb-0">@price($order->total)</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->kitchen_notes)
                    <div class="alert alert-info mt-3">
                        <strong><i class="ri ri-information-line me-2"></i>Notas para cocina:</strong>
                        <p class="mb-0 mt-1">{{ $order->kitchen_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Información y Acciones -->
    <div class="col-lg-4 mb-4">
        <!-- Estado del Pedido -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Estado del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Estado Actual</label>
                    <div>
                        <span class="badge bg-{{ $order->statusColor }} fs-6">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                </div>

                @if($order->waiter)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Mesero</label>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ substr($order->waiter->name, 0, 1) }}
                                </span>
                            </div>
                            <span>{{ $order->waiter->name }}</span>
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label text-muted small">Hora del Pedido</label>
                    <p class="mb-0">{{ $order->created_at->format('H:i') }}</p>
                </div>

                @if($order->preparing_at)
                    <div class="mb-3">
                        <label class="form-label text-muted small">En Preparación</label>
                        <p class="mb-0">{{ $order->preparing_at->format('H:i') }}</p>
                    </div>
                @endif

                @if($order->ready_at)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Listo</label>
                        <p class="mb-0">{{ $order->ready_at->format('H:i') }}</p>
                    </div>
                @endif

                @if($order->served_at)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Servido</label>
                        <p class="mb-0">{{ $order->served_at->format('H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tenant.path.tables.updateOrderStatus', ['tenant' => request()->route('tenant'), 'table_id' => $table->id]) }}" method="POST">
                    @csrf

                    @if($order->status === 'pending')
                        <button type="submit" name="status" value="preparing" class="btn btn-info w-100 mb-2">
                            <i class="ri ri-restaurant-2-line me-1"></i> Marcar En Preparación
                        </button>
                    @endif

                    @if($order->status === 'preparing')
                        <button type="submit" name="status" value="ready" class="btn btn-primary w-100 mb-2">
                            <i class="ri ri-check-line me-1"></i> Marcar Listo
                        </button>
                    @endif

                    @if($order->status === 'ready')
                        <button type="submit" name="status" value="served" class="btn btn-success w-100 mb-2">
                            <i class="ri ri-restaurant-line me-1"></i> Marcar Servido
                        </button>
                    @endif

                    @if($order->status === 'served')
                        <button type="submit" name="status" value="closed" class="btn btn-secondary w-100 mb-2">
                            <i class="ri ri-file-list-line me-1"></i> Cerrar Cuenta
                        </button>
                    @endif

                    @if($order->status === 'closed')
                        <div class="alert alert-success">
                            <i class="ri ri-check-line me-2"></i>
                            <strong>Cuenta cerrada</strong>
                            <p class="mb-0 mt-1 small">Dirígete a Caja para procesar el pago</p>
                        </div>
                        <a href="{{ route('tenant.path.cash.index', ['tenant' => request()->route('tenant')]) }}"
                           class="btn btn-success w-100 mb-2">
                            <i class="ri ri-cash-line me-1"></i> Ir a Caja
                        </a>
                    @endif

                    @if(in_array($order->status, ['pending', 'preparing', 'ready', 'served']))
                        <button type="submit" name="status" value="cancelled" class="btn btn-outline-danger w-100"
                                onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                            <i class="ri ri-close-line me-1"></i> Cancelar Pedido
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
