@extends('tenant.layouts.admin')

@section('title', 'Reporte de Créditos')

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Reporte de Créditos</h1>
            <p class="text-muted mb-0">Gestión y seguimiento de créditos otorgados</p>
        </div>
        <a href="{{ route('tenant.path.customers.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i> Volver a Clientes
        </a>
    </div>
</div>

<!-- Resumen de Créditos -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-2">
                    <span class="avatar-initial rounded bg-label-danger">
                        <i class="ri ri-money-dollar-circle-line ri-24px"></i>
                    </span>
                </div>
                <h4 class="mb-1 text-danger">@price($totalDebt)</h4>
                <p class="mb-0 text-muted">Total Adeudado</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-2">
                    <span class="avatar-initial rounded bg-label-primary">
                        <i class="ri ri-bank-card-line ri-24px"></i>
                    </span>
                </div>
                <h4 class="mb-1 text-primary">@price($totalCreditLimit)</h4>
                <p class="mb-0 text-muted">Crédito Total Otorgado</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-2">
                    <span class="avatar-initial rounded bg-label-warning">
                        <i class="ri ri-user-line ri-24px"></i>
                    </span>
                </div>
                <h4 class="mb-1 text-warning">{{ $customersWithDebt->count() }}</h4>
                <p class="mb-0 text-muted">Clientes con Deuda</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-2">
                    <span class="avatar-initial rounded bg-label-success">
                        <i class="ri ri-percent-line ri-24px"></i>
                    </span>
                </div>
                <h4 class="mb-1 text-success">
                    {{ $totalCreditLimit > 0 ? number_format(($totalDebt / $totalCreditLimit) * 100, 1) : 0 }}%
                </h4>
                <p class="mb-0 text-muted">Utilización</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Clientes con Deuda -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Clientes con Deuda Pendiente</h6>
            </div>
            <div class="card-body">
                @if($customersWithDebt->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Límite</th>
                                    <th>Deuda</th>
                                    <th>Disponible</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customersWithDebt as $customer)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $customer->name }}</strong>
                                                @if($customer->document_number)
                                                    <br><small class="text-muted">{{ $customer->document_type }}: {{ $customer->document_number }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($customer->phone)
                                                <div><i class="ri ri-phone-line me-1"></i> {{ $customer->phone }}</div>
                                            @endif
                                            @if($customer->email)
                                                <div><i class="ri ri-mail-line me-1"></i> {{ $customer->email }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">@price($customer->credit_limit)</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">@price($customer->credit_used)</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">@price($customer->credit_available)</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri ri-more-2-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('tenant.path.customers.show', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                                                            <i class="ri ri-eye-line me-2"></i> Ver Detalles
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" onclick="showPaymentModal({{ $customer->id }}, '{{ $customer->name }}', {{ $customer->credit_used }})">
                                                            <i class="ri ri-money-dollar-circle-line me-2"></i> Registrar Pago
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri ri-check-line ri-48px"></i>
                            </span>
                        </div>
                        <h5 class="mb-1">¡Excelente!</h5>
                        <p class="text-muted mb-0">No hay clientes con deuda pendiente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transacciones Recientes -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Transacciones Recientes (7 días)</h6>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                    <div class="timeline">
                        @foreach($recentTransactions as $transaction)
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-{{ $transaction->type === 'payment' ? 'success' : ($transaction->type === 'credit_use' ? 'warning' : 'info') }}"></div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $transaction->customer->name }}</h6>
                                            <p class="mb-1 small">{{ $transaction->description }}</p>
                                            <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-{{ $transaction->type === 'payment' ? 'success' : ($transaction->type === 'credit_use' ? 'warning' : 'info') }}">
                                            {{ $transaction->formatted_amount }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="ri ri-file-list-line ri-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay transacciones recientes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Pago -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paymentForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <input type="text" id="customerName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deuda Actual</label>
                        <input type="text" id="currentDebt" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto del Pago *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <input type="text" name="description" class="form-control" placeholder="Ej: Pago en efectivo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-style')
<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--bs-border-color);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-point {
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid var(--bs-card-bg);
}

.timeline-point-success {
    background: var(--bs-success);
}

.timeline-point-warning {
    background: var(--bs-warning);
}

.timeline-point-info {
    background: var(--bs-info);
}

.timeline-event {
    background: var(--bs-card-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 0.375rem;
    padding: 0.75rem;
}
</style>
@endsection

@section('page-script')
<script>
function showPaymentModal(customerId, customerName, currentDebt) {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const form = document.getElementById('paymentForm');

    form.action = `{{ url('/') }}/{{ request()->route('tenant') }}/customers/${customerId}/add-payment`;
    document.getElementById('customerName').value = customerName;
    document.getElementById('currentDebt').value = '@price(' + currentDebt + ')';

    modal.show();
}
</script>
@endsection
