@php
    $minutesElapsed = $order->created_at->diffInMinutes(now());
    $timeClass = '';
    if ($minutesElapsed > 20) {
        $timeClass = 'time-danger';
    } elseif ($minutesElapsed > 10) {
        $timeClass = 'time-warning';
    }

    $currentStatus = $order->items->first()->preparation_status ?? 'pending';
@endphp

<div class="card kds-card {{ $timeClass }} mb-3"
     data-order-type="{{ $order->order_type }}"
     data-order-id="{{ $order->id }}"
     data-current-status="{{ $currentStatus }}">
    <div class="card-body">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="mb-1">
                    @if($order->order_type === 'table')
                        <i class="ri ri-table-line me-1" style="color: {{ $area->color }};"></i>
                        {{ $order->display_name }}
                    @else
                        <i class="ri ri-e-bike-2-line me-1" style="color: {{ $area->color }};"></i>
                        {{ $order->display_name }}
                    @endif
                </h5>
                <small class="text-muted">
                    <i class="ri ri-time-line"></i> {{ $order->created_at->format('H:i') }}
                    <span class="ms-2 {{ $minutesElapsed > 20 ? 'text-danger fw-bold' : ($minutesElapsed > 10 ? 'text-warning fw-bold' : '') }}">
                        ({{ $minutesElapsed }} min)
                    </span>
                </small>
            </div>
            <div>
                @if($order->order_type === 'table')
                    <span class="badge bg-label-primary">Mesa</span>
                @else
                    <span class="badge bg-label-info">
                        @if($order->type === 'delivery')
                            Delivery
                        @else
                            Para Llevar
                        @endif
                    </span>
                @endif
            </div>
        </div>

        <!-- Info adicional -->
        @if($order->order_type === 'table' && $order->waiter)
            <div class="mb-2">
                <small class="text-muted">
                    <i class="ri ri-user-line"></i> {{ $order->waiter->name }}
                </small>
            </div>
        @endif

        @if($order->order_type === 'delivery')
            <div class="mb-2 customer-info">
                <small>
                    <strong><i class="ri ri-user-line"></i> {{ $order->customer_name }}</strong><br>
                    <i class="ri ri-phone-line"></i> {{ $order->customer_phone }}
                </small>
            </div>
        @endif

        <!-- Items del pedido -->
        <div class="mb-3">
            @foreach($order->items as $item)
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span><strong>{{ $item->quantity }}x</strong> {{ $item->product->name }}</span>
                </div>
                @if($item->notes)
                    <div class="ps-3">
                        <small class="text-muted"><i class="ri ri-message-2-line"></i> {{ $item->notes }}</small>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Notas del pedido -->
        @if($order->order_type === 'table' && $order->kitchen_notes)
            <div class="alert alert-info py-2 mb-3">
                <small><strong><i class="ri ri-information-line"></i> Notas:</strong> {{ $order->kitchen_notes }}</small>
            </div>
        @elseif($order->order_type === 'delivery' && $order->notes)
            <div class="alert alert-info py-2 mb-3">
                <small><strong><i class="ri ri-information-line"></i> Notas:</strong> {{ $order->notes }}</small>
            </div>
        @endif

        <!-- Botones de acción -->
        <div class="d-grid gap-2">
            @if($currentStatus === 'pending')
                <button class="btn btn-warning btn-change-status"
                        data-next-status="preparing"
                        data-area-id="{{ $area->id }}"
                        data-tenant="{{ request()->route('tenant') }}">
                    <i class="ri ri-restaurant-2-line me-1"></i> Iniciar Preparación
                </button>
            @elseif($currentStatus === 'preparing')
                <button class="btn btn-success btn-change-status"
                        data-next-status="ready"
                        data-area-id="{{ $area->id }}"
                        data-tenant="{{ request()->route('tenant') }}">
                    <i class="ri ri-checkbox-circle-line me-1"></i> Marcar Listo
                </button>
            @elseif($currentStatus === 'ready')
                <button class="btn btn-outline-secondary btn-sm btn-change-status"
                        data-next-status="preparing"
                        data-area-id="{{ $area->id }}"
                        data-tenant="{{ request()->route('tenant') }}">
                    <i class="ri ri-arrow-left-line me-1"></i> Volver a Preparando
                </button>
            @endif
        </div>
    </div>
</div>
