@extends('tenant.layouts.admin')

@section('title', 'Clientes')

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Gestión de Clientes</h1>
            <p class="text-muted mb-0">Administra tu base de datos de clientes y créditos</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tenant.path.customers.credit-report', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-info">
                <i class="ri ri-file-chart-line me-1"></i> Reporte de Créditos
            </a>
            <a href="{{ route('tenant.path.customers.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                <i class="ri ri-add-line me-1"></i> Nuevo Cliente
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Nombre, teléfono, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Crédito</label>
                <select name="credit" class="form-select">
                    <option value="">Todos</option>
                    <option value="with_credit" {{ request('credit') === 'with_credit' ? 'selected' : '' }}>Con Crédito</option>
                    <option value="with_debt" {{ request('credit') === 'with_debt' ? 'selected' : '' }}>Con Deuda</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-search-line"></i>
                    </button>
                    <a href="{{ route('tenant.path.customers.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
                        <i class="ri ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Clientes -->
<div class="card">
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Crédito</th>
                            <th>Deuda</th>
                            <th>Disponible</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
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
                                    @if($customer->credit_used > 0)
                                        <span class="badge bg-warning">@price($customer->credit_used)</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">@price($customer->credit_available)</span>
                                </td>
                                <td>
                                    @if($customer->active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
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
                                                <a class="dropdown-item" href="{{ route('tenant.path.customers.edit', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                                                    <i class="ri ri-edit-line me-2"></i> Editar
                                                </a>
                                            </li>
                                            @if($customer->credit_used > 0)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item" onclick="showPaymentModal({{ $customer->id }}, '{{ $customer->name }}', {{ $customer->credit_used }})">
                                                        <i class="ri ri-money-dollar-circle-line me-2"></i> Registrar Pago
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $customers->links() }}
        @else
            <div class="text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="ri ri-user-line ri-48px"></i>
                    </span>
                </div>
                <h5 class="mb-1">No hay clientes registrados</h5>
                <p class="text-muted mb-4">Crea tu primer cliente para comenzar</p>
                <a href="{{ route('tenant.path.customers.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                    <i class="ri ri-add-line me-1"></i> Crear Primer Cliente
                </a>
            </div>
        @endif
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
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
