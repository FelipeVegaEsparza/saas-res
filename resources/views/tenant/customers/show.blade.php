@extends('tenant.layouts.admin')

@section('title', 'Cliente: ' . $customer->name)

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">{{ $customer->name }}</h1>
            <p class="text-muted mb-0">Información detallada del cliente</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tenant.path.customers.edit', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}" class="btn btn-outline-primary">
                <i class="ri ri-edit-line me-1"></i> Editar
            </a>
            <a href="{{ route('tenant.path.customers.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
                <i class="ri ri-arrow-left-line me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información del Cliente -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">Información Personal</h6>

                <div class="mb-2">
                    <small class="text-muted">Nombre:</small>
                    <div>{{ $customer->name }}</div>
                </div>

                @if($customer->phone)
                    <div class="mb-2">
                        <small class="text-muted">Teléfono:</small>
                        <div><i class="ri ri-phone-line me-1"></i> {{ $customer->phone }}</div>
                    </div>
                @endif

                @if($customer->email)
                    <div class="mb-2">
                        <small class="text-muted">Email:</small>
                        <div><i class="ri ri-mail-line me-1"></i> {{ $customer->email }}</div>
                    </div>
                @endif

                @if($customer->document_number)
                    <div class="mb-2">
                        <small class="text-muted">Documento:</small>
                        <div>{{ $customer->document_type }}: {{ $customer->document_number }}</div>
                    </div>
                @endif

                @if($customer->address)
                    <div class="mb-2">
                        <small class="text-muted">Dirección:</small>
                        <div>{{ $customer->address }}</div>
                    </div>
                @endif

                <div class="mb-2">
                    <small class="text-muted">Estado:</small>
                    <div>
                        @if($customer->active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </div>
                </div>

                @if($customer->notes)
                    <div class="mb-2">
                        <small class="text-muted">Notas:</small>
                        <div class="small">{{ $customer->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información de Crédito -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Estado de Crédito</h6>

                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <div class="h5 mb-0 text-primary">@price($customer->credit_limit)</div>
                            <small class="text-muted">Límite</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <div class="h5 mb-0 text-warning">@price($customer->credit_used)</div>
                            <small class="text-muted">Usado</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="h5 mb-0 text-success">@price($customer->credit_available)</div>
                        <small class="text-muted">Disponible</small>
                    </div>
                </div>

                @if($customer->credit_used > 0)
                    <hr>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-sm" onclick="showPaymentModal()">
                            <i class="ri ri-money-dollar-circle-line me-1"></i> Registrar Pago
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="showAdjustmentModal()">
                            <i class="ri ri-edit-line me-1"></i> Ajustar Crédito
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Historial y Transacciones -->
    <div class="col-md-8">
        <!-- Transacciones Recientes -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Transacciones Recientes</h6>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type === 'payment' ? 'success' : ($transaction->type === 'credit_use' ? 'warning' : 'info') }}">
                                                {{ $transaction->type_name }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="text-end">
                                            <span class="text-{{ $transaction->type === 'payment' || ($transaction->type === 'adjustment' && $transaction->amount > 0) ? 'success' : 'danger' }}">
                                                {{ $transaction->formatted_amount }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="ri ri-file-list-line ri-2x mb-2"></i>
                        <p class="mb-0">No hay transacciones registradas</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Órdenes Recientes -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Órdenes Recientes</h6>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0 || $recentDeliveryOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Número</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td><span class="badge bg-primary">Mesa</span></td>
                                        <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                        <td class="text-end">@price($order->total)</td>
                                    </tr>
                                @endforeach
                                @foreach($recentDeliveryOrders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td><span class="badge bg-info">{{ $order->type_label }}</span></td>
                                        <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                        <td class="text-end">@price($order->total)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="ri ri-shopping-cart-line ri-2x mb-2"></i>
                        <p class="mb-0">No hay órdenes registradas</p>
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
            <form method="POST" action="{{ route('tenant.path.customers.add-payment', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Deuda Actual</label>
                        <input type="text" class="form-control" value="@price($customer->credit_used)" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto del Pago *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" max="{{ $customer->credit_used }}" required>
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

<!-- Modal de Ajuste -->
<div class="modal fade" id="adjustmentModal" tabindex="-1">
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
                        <label class="form-label">Tipo de Ajuste</label>
                        <select class="form-select" onchange="updateAdjustmentType(this.value)">
                            <option value="positive">Reducir Deuda (Abono)</option>
                            <option value="negative">Aumentar Deuda (Cargo)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto *</label>
                        <input type="number" name="amount" id="adjustmentAmount" class="form-control" step="0.01" min="0.01" required>
                        <small class="form-text text-muted" id="adjustmentHelp">Ingrese el monto a reducir de la deuda</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <input type="text" name="description" class="form-control" placeholder="Motivo del ajuste" required>
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
function showPaymentModal() {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function showAdjustmentModal() {
    const modal = new bootstrap.Modal(document.getElementById('adjustmentModal'));
    modal.show();
}

function updateAdjustmentType(type) {
    const amountInput = document.getElementById('adjustmentAmount');
    const helpText = document.getElementById('adjustmentHelp');

    if (type === 'positive') {
        amountInput.setAttribute('min', '0.01');
        helpText.textContent = 'Ingrese el monto a reducir de la deuda';
    } else {
        amountInput.setAttribute('min', '0.01');
        helpText.textContent = 'Ingrese el monto a agregar a la deuda (será negativo)';
        // Convertir a negativo al enviar
        amountInput.addEventListener('input', function() {
            if (type === 'negative') {
                this.setAttribute('data-negative', 'true');
            }
        });
    }
}

// Convertir a negativo si es necesario antes de enviar
document.querySelector('#adjustmentModal form').addEventListener('submit', function(e) {
    const amountInput = document.getElementById('adjustmentAmount');
    const select = document.querySelector('#adjustmentModal select');

    if (select.value === 'negative') {
        amountInput.value = -Math.abs(amountInput.value);
    }
});
</script>
@endsection
