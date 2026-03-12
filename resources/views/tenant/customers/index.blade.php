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
                            <th>Límite Crédito</th>
                            <th>Crédito Usado</th>
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
                                    <span class="badge bg-primary">{{ formatPrice($customer->credit_limit ?? 0) }}</span>
                                </td>
                                <td>
                                    @if(($customer->credit_used ?? 0) > 0)
                                        <span class="badge bg-warning">{{ formatPrice($customer->credit_used) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ formatPrice($customer->credit_available ?? 0) }}</span>
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
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('tenant.path.customers.show', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                                                <i class="ri ri-eye-line me-1"></i> Ver Perfil
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('tenant.path.customers.edit', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                                                <i class="ri ri-edit-line me-1"></i> Editar
                                            </a></li>
                                            @if($customer->credit_used > 0)
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="openPaymentModal({{ $customer->id }}, '{{ $customer->name }}', {{ $customer->credit_used }})">
                                                    <i class="ri ri-money-dollar-circle-line me-1"></i> Registrar Pago
                                                </a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-3">
                {{ $customers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="ri ri-user-heart-line display-4 text-muted"></i>
                <h5 class="mt-3">No hay clientes registrados</h5>
                <p class="text-muted">Comienza agregando tu primer cliente</p>
                <a href="{{ route('tenant.path.customers.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                    <i class="ri ri-add-line me-1"></i> Crear Primer Cliente
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal para Registrar Pago -->
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
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Descripción del pago..."></textarea>
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
function openPaymentModal(customerId, customerName, currentDebt) {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const form = document.getElementById('paymentForm');

    form.action = `{{ url('/') }}/{{ request()->route('tenant') }}/customers/${customerId}/add-payment`;
    document.getElementById('customerName').value = customerName;
    document.getElementById('currentDebt').value = formatPrice(currentDebt);

    modal.show();
}

function formatPrice(amount) {
    // Función simple de formateo de precios
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount || 0);
}
</script>
@endsection
