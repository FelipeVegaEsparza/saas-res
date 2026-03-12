@extends('tenant.layouts.admin')

@section('title', 'Cliente: ' . $customer->name)

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">{{ $customer->name }}</h1>
            <p class="text-muted mb-0">Información del cliente</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tenant.path.customers.edit', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}" class="btn btn-outline-primary">
                <i class="ri ri-edit-line me-1"></i> Editar
            </a>
            <a href="{{ route('tenant.path.customers.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-secondary">
                <i class="ri ri-arrow-left-line me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Información del Cliente -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> {{ $customer->name }}</p>
                        @if($customer->email)
                            <p><strong>Email:</strong> {{ $customer->email }}</p>
                        @endif
                        @if($customer->phone)
                            <p><strong>Teléfono:</strong> {{ $customer->phone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($customer->document_type && $customer->document_number)
                            <p><strong>{{ $customer->document_type }}:</strong> {{ $customer->document_number }}</p>
                        @endif
                        @if($customer->address)
                            <p><strong>Dirección:</strong> {{ $customer->address }}</p>
                        @endif
                        <p><strong>Estado:</strong>
                            @if($customer->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($customer->notes)
                    <hr>
                    <p><strong>Notas:</strong> {{ $customer->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Información de Crédito -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estado de Crédito</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Límite de Crédito</label>
                    <div class="h4 text-primary">{{ formatPrice($customer->credit_limit) }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Crédito Usado</label>
                    <div class="h4 text-warning">{{ formatPrice($customer->credit_used) }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Crédito Disponible</label>
                    <div class="h4 text-success">{{ formatPrice($customer->credit_available) }}</div>
                </div>

                @if($customer->credit_used > 0)
                    <hr>
                    <button class="btn btn-outline-success w-100 mb-2" onclick="openPaymentModal()">
                        <i class="ri ri-money-dollar-circle-line me-1"></i> Registrar Pago
                    </button>
                @endif

                <button class="btn btn-outline-info w-100" onclick="openAdjustModal()">
                    <i class="ri ri-edit-circle-line me-1"></i> Ajustar Crédito
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Registrar Pago -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('tenant.path.customers.add-payment', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Deuda Actual</label>
                        <input type="text" class="form-control" value="{{ formatPrice($customer->credit_used) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto del Pago *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" max="{{ $customer->credit_used }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Descripción del pago..." required></textarea>
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

<!-- Modal para Ajustar Crédito -->
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('tenant.path.customers.adjust-credit', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajustar Crédito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Monto del Ajuste *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                        <div class="form-text">Positivo para reducir deuda, negativo para aumentar</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Razón del ajuste..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Realizar Ajuste</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
function openPaymentModal() {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function openAdjustModal() {
    const modal = new bootstrap.Modal(document.getElementById('adjustModal'));
    modal.show();
}
</script>
@endsection
