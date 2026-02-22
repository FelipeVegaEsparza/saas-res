@extends('tenant.layouts.admin')

@section('title', 'Estadísticas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Estadísticas de Ventas</h1>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tenant.path.statistics.index', ['tenant' => request()->route('tenant')]) }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Período</label>
                    <select name="period" class="form-select" id="periodSelect">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hoy</option>
                        <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>Ayer</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Este Mes</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Este Año</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Personalizado</option>
                    </select>
                </div>
                <div class="col-md-3" id="customDates" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $start->format('Y-m-d') }}">
                </div>
                <div class="col-md-3" id="customDatesEnd" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $end->format('Y-m-d') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-search-line me-1"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ingresos Totales</p>
                        <h3 class="mb-0">@price($stats['total_revenue'])</h3>
                        @if(isset($comparison['revenue_change']))
                            <small class="text-{{ $comparison['revenue_change'] >= 0 ? 'success' : 'danger' }}">
                                <i class="ri ri-arrow-{{ $comparison['revenue_change'] >= 0 ? 'up' : 'down' }}-line"></i>
                                {{ number_format(abs($comparison['revenue_change']), 1) }}%
                            </small>
                        @endif
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
                        <p class="text-muted mb-1">Total Órdenes</p>
                        <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                        @if(isset($comparison['orders_change']))
                            <small class="text-{{ $comparison['orders_change'] >= 0 ? 'success' : 'danger' }}">
                                <i class="ri ri-arrow-{{ $comparison['orders_change'] >= 0 ? 'up' : 'down' }}-line"></i>
                                {{ number_format(abs($comparison['orders_change']), 1) }}%
                            </small>
                        @endif
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
                        <p class="text-muted mb-1">Ticket Promedio</p>
                        <h3 class="mb-0">@price($stats['average_ticket'])</h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-info">
                        <i class="ri ri-receipt-line ri-26px"></i>
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
                        <p class="text-muted mb-1">Items Vendidos</p>
                        <h3 class="mb-0">{{ $stats['total_items_sold'] }}</h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-warning">
                        <i class="ri ri-shopping-cart-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Desglose por Tipo -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Órdenes en Mesas</p>
                        <h4 class="mb-0">{{ $stats['table_orders'] }}</h4>
                    </div>
                    <div class="avatar bg-label-primary">
                        <i class="ri ri-table-line ri-22px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Órdenes Delivery</p>
                        <h4 class="mb-0">{{ $stats['delivery_orders'] }}</h4>
                    </div>
                    <div class="avatar bg-label-info">
                        <i class="ri ri-e-bike-2-line ri-22px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Ventas por Día -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ventas por Día</h5>
            </div>
            <div class="card-body">
                @if(count($salesByDay) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th class="text-end">Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesByDay as $day)
                                    <tr>
                                        <td>{{ $day['label'] }}</td>
                                        <td class="text-end">
                                            <strong>@price($day['revenue'])</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th>Total</th>
                                    <th class="text-end">@price(collect($salesByDay)->sum('revenue'))</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Sin datos para mostrar</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Horarios Pico -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Horarios Pico</h5>
            </div>
            <div class="card-body">
                @if($peakHours->isNotEmpty())
                    <div class="list-group list-group-flush">
                        @foreach($peakHours as $hour)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ str_pad($hour['hour'], 2, '0', STR_PAD_LEFT) }}:00</h6>
                                        <small class="text-muted">@price($hour['revenue'])</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $hour['orders'] }} órdenes
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4">Sin datos</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Productos Más Vendidos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos Más Vendidos</h5>
            </div>
            <div class="card-body">
                @if($topProducts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th class="text-end">Vendidos</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $index => $product)
                                    <tr>
                                        <td>
                                            <span class="badge bg-label-primary">{{ $index + 1 }}</span>
                                        </td>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td>@price($product->price)</td>
                                        <td class="text-end">{{ $product->total_sold }}</td>
                                        <td class="text-end">
                                            <strong>@price($product->price * $product->total_sold)</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Sin datos</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Productos Menos Vendidos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos con Menor Rotación</h5>
            </div>
            <div class="card-body">
                @if($lowProducts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th class="text-end">Vendidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowProducts as $product)
                                    <tr>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td>@price($product->price)</td>
                                        <td class="text-end">
                                            <span class="badge bg-label-warning">{{ $product->total_sold }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Sin datos</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Ventas por Categoría -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ventas por Categoría</h5>
            </div>
            <div class="card-body">
                @if($salesByCategory->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-end">Ingresos</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalCategoryRevenue = $salesByCategory->sum('total_revenue'); @endphp
                                @foreach($salesByCategory as $category)
                                    <tr>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td class="text-end">@price($category->total_revenue)</td>
                                        <td class="text-end">
                                            <span class="badge bg-label-primary">
                                                {{ $totalCategoryRevenue > 0 ? number_format(($category->total_revenue / $totalCategoryRevenue) * 100, 1) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Sin datos</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Métodos de Pago -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Métodos de Pago</h5>
            </div>
            <div class="card-body">
                @if($paymentMethods->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th class="text-end">Transacciones</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentMethods as $method)
                                    <tr>
                                        <td>
                                            <strong>{{ ucfirst($method->payment_method) }}</strong>
                                        </td>
                                        <td class="text-end">{{ $method->count }}</td>
                                        <td class="text-end">
                                            <strong>@price($method->total)</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Sin datos</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('periodSelect');
    const customDates = document.getElementById('customDates');
    const customDatesEnd = document.getElementById('customDatesEnd');

    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDates.style.display = 'block';
            customDatesEnd.style.display = 'block';
        } else {
            customDates.style.display = 'none';
            customDatesEnd.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
