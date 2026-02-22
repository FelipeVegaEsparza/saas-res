@extends('tenant.layouts.admin')

@section('title', 'Mesas')

@section('page-style')
<style>
    .table-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .table-visual {
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .table-visual::before {
        content: '';
        position: absolute;
        width: 80%;
        height: 70%;
        border: 3px solid currentColor;
        border-radius: 8px;
        opacity: 0.3;
    }
    .table-visual.available {
        background: rgba(var(--bs-success-rgb), 0.12);
        color: var(--bs-success);
    }
    .table-visual.occupied {
        background: rgba(var(--bs-danger-rgb), 0.12);
        color: var(--bs-danger);
    }
    .table-visual.reserved {
        background: rgba(var(--bs-warning-rgb), 0.12);
        color: var(--bs-warning);
    }
    .table-icon {
        font-size: 2.5rem;
        position: relative;
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Mesas del Restaurante</h1>
            <p class="text-muted">Gestiona las mesas y toma pedidos</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('tenant.path.tables.syncStatus', ['tenant' => request()->route('tenant')]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary" title="Sincronizar estado de mesas">
                    <i class="ri ri-refresh-line me-1"></i> Sincronizar
                </button>
            </form>
            <a href="{{ route('tenant.path.tables.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                <i class="ri ri-add-line me-1"></i> Nueva Mesa
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri ri-check-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Disponibles</p>
                        <h4 class="mb-0">{{ $tables->where('status', 'available')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri ri-user-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Ocupadas</p>
                        <h4 class="mb-0">{{ $tables->where('status', 'occupied')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri ri-bookmark-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Reservadas</p>
                        <h4 class="mb-0">{{ $tables->where('status', 'reserved')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri ri-table-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Total Mesas</p>
                        <h4 class="mb-0">{{ $tables->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mapa de Mesas -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Mapa de Mesas</h5>
    </div>
    <div class="card-body">
        @if($tables->count() > 0)
            <div class="row g-4">
                @foreach($tables as $table)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="card table-card h-100"
                             onclick="handleTableClick({{ $table->id }}, '{{ $table->status }}', {{ $table->orders_count }})">

                            <!-- Representación visual de la mesa -->
                            <div class="table-visual {{ $table->status }}">
                                <i class="ri ri-restaurant-2-line table-icon"></i>
                            </div>

                            <div class="card-body text-center p-3">
                                <!-- Badge de estado -->
                                <div class="mb-2">
                                    @if($table->status === 'available')
                                        <span class="badge bg-label-success">Disponible</span>
                                    @elseif($table->status === 'occupied')
                                        <span class="badge bg-label-danger">Ocupada</span>
                                    @elseif($table->status === 'reserved')
                                        <span class="badge bg-label-warning">Reservada</span>
                                    @endif
                                </div>

                                <!-- Nombre de la mesa -->
                                <h5 class="mb-2">{{ $table->number }}</h5>

                                <!-- Información compacta -->
                                <div class="d-flex justify-content-center gap-3 text-muted small mb-2">
                                    <span>
                                        <i class="ri ri-user-line"></i> {{ $table->capacity }}
                                    </span>
                                    @if($table->location)
                                        <span>
                                            <i class="ri ri-map-pin-line"></i> {{ \Illuminate\Support\Str::limit($table->location, 8) }}
                                        </span>
                                    @endif
                                </div>

                                @if($table->orders_count > 0)
                                    <span class="badge bg-label-primary">
                                        <i class="ri ri-restaurant-line"></i> Pedido
                                    </span>
                                @endif
                            </div>

                            <!-- Acciones -->
                            <div class="card-footer bg-transparent border-top p-2">
                                <div class="d-flex justify-content-center gap-1">
                                    @if($table->status === 'available')
                                        <button class="btn btn-sm btn-success" onclick="event.stopPropagation(); takeOrder({{ $table->id }})" title="Tomar Pedido">
                                            <i class="ri ri-add-line"></i>
                                        </button>
                                    @elseif($table->status === 'occupied')
                                        <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); viewOrder({{ $table->id }})" title="Ver Pedido">
                                            <i class="ri ri-eye-line"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill" onclick="event.stopPropagation(); editTable({{ $table->id }})" title="Editar">
                                        <i class="ri ri-edit-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="ri ri-table-line ri-48px"></i>
                    </span>
                </div>
                <h5 class="mb-1">No hay mesas registradas</h5>
                <p class="text-muted mb-4">Crea tu primera mesa para comenzar</p>
                <a href="{{ route('tenant.path.tables.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                    <i class="ri ri-add-line me-1"></i> Crear Primera Mesa
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-script')
<script>
function handleTableClick(tableId, status, hasOrders) {
    if (status === 'available') {
        takeOrder(tableId);
    } else if (status === 'occupied' && hasOrders > 0) {
        viewOrder(tableId);
    }
}

function takeOrder(tableId) {
    window.location.href = `{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/take-order`;
}

function viewOrder(tableId) {
    window.location.href = `{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/show-order`;
}

function editTable(tableId) {
    window.location.href = `{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/edit`;
}
</script>
@endsection
