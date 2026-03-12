@extends('tenant.layouts.admin')

@section('title', 'Editar Cliente')

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Editar Cliente</h1>
            <p class="text-muted mb-0">Actualiza la información del cliente</p>
        </div>
        <a href="{{ route('tenant.path.customers.show', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('tenant.path.customers.update', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $customer->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $customer->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Límite de Crédito *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="credit_limit" class="form-control @error('credit_limit') is-invalid @enderror"
                                       value="{{ old('credit_limit', $customer->credit_limit) }}" step="0.01" min="0" required>
                            </div>
                            @error('credit_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($customer->credit_used > 0)
                                <small class="form-text text-warning">
                                    <i class="ri ri-alert-line me-1"></i>
                                    El cliente tiene una deuda de @price($customer->credit_used). El límite no puede ser menor a este monto.
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Documento</label>
                            <select name="document_type" class="form-select @error('document_type') is-invalid @enderror">
                                <option value="">Seleccionar...</option>
                                <option value="RUT" {{ old('document_type', $customer->document_type) === 'RUT' ? 'selected' : '' }}>RUT</option>
                                <option value="DNI" {{ old('document_type', $customer->document_type) === 'DNI' ? 'selected' : '' }}>DNI</option>
                                <option value="Cédula" {{ old('document_type', $customer->document_type) === 'Cédula' ? 'selected' : '' }}>Cédula</option>
                                <option value="Pasaporte" {{ old('document_type', $customer->document_type) === 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Documento</label>
                            <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                                   value="{{ old('document_number', $customer->document_number) }}">
                            @error('document_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  rows="2">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                  rows="3" placeholder="Información adicional sobre el cliente...">{{ old('notes', $customer->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="active" class="form-check-input" id="active"
                                   value="1" {{ old('active', $customer->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">
                                Cliente Activo
                            </label>
                        </div>
                        <small class="form-text text-muted">Los clientes inactivos no aparecerán en las búsquedas</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            @if($customer->orders()->count() === 0 && $customer->deliveryOrders()->count() === 0 && $customer->credit_used == 0)
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="ri ri-delete-bin-line me-1"></i> Eliminar Cliente
                                </button>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('tenant.path.customers.show', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri ri-save-line me-1"></i> Actualizar Cliente
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri ri-information-line me-2"></i>Estado Actual
                </h6>

                <div class="mb-3">
                    <small class="text-muted">Crédito Usado:</small>
                    <div class="h6 text-warning">@price($customer->credit_used)</div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Crédito Disponible:</small>
                    <div class="h6 text-success">@price($customer->credit_available)</div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Total de Órdenes:</small>
                    <div class="h6">{{ $customer->orders()->count() + $customer->deliveryOrders()->count() }}</div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Cliente desde:</small>
                    <div>{{ $customer->created_at->format('d/m/Y') }}</div>
                </div>

                @if($customer->credit_used > 0)
                    <div class="alert alert-warning">
                        <i class="ri ri-alert-line me-2"></i>
                        <strong>Atención:</strong> Este cliente tiene deuda pendiente. No se puede reducir el límite de crédito por debajo del monto adeudado.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Form para eliminar -->
<form id="deleteForm" method="POST" action="{{ route('tenant.path.customers.destroy', ['tenant' => request()->route('tenant'), 'customer' => $customer]) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('page-script')
<script>
function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar cliente?',
        html: `¿Estás seguro de eliminar a <strong>{{ $customer->name }}</strong>?<br><small class="text-muted">Esta acción no se puede deshacer</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}

// Validar límite de crédito
document.querySelector('input[name="credit_limit"]').addEventListener('input', function() {
    const currentDebt = {{ $customer->credit_used }};
    const newLimit = parseFloat(this.value) || 0;

    if (newLimit < currentDebt) {
        this.setCustomValidity('El límite no puede ser menor a la deuda actual (@price($customer->credit_used))');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection
