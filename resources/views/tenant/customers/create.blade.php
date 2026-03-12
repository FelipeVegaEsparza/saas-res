@extends('tenant.layouts.admin')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Nuevo Cliente</h1>
            <p class="text-muted mb-0">Registra un nuevo cliente en tu base de datos</p>
        </div>
        <a href="{{ route('tenant.path.customers.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('tenant.path.customers.store', ['tenant' => request()->route('tenant')]) }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Límite de Crédito *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="credit_limit" class="form-control @error('credit_limit') is-invalid @enderror"
                                       value="{{ old('credit_limit', 0) }}" step="0.01" min="0" required>
                            </div>
                            @error('credit_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Documento</label>
                            <select name="document_type" class="form-select @error('document_type') is-invalid @enderror">
                                <option value="">Seleccionar...</option>
                                <option value="RUT" {{ old('document_type') === 'RUT' ? 'selected' : '' }}>RUT</option>
                                <option value="DNI" {{ old('document_type') === 'DNI' ? 'selected' : '' }}>DNI</option>
                                <option value="Cédula" {{ old('document_type') === 'Cédula' ? 'selected' : '' }}>Cédula</option>
                                <option value="Pasaporte" {{ old('document_type') === 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Documento</label>
                            <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                                   value="{{ old('document_number') }}">
                            @error('document_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                  rows="3" placeholder="Información adicional sobre el cliente...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tenant.path.customers.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i> Crear Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri ri-information-line me-2"></i>Información sobre Créditos
                </h6>
                <div class="small text-muted">
                    <p><strong>Límite de Crédito:</strong> Monto máximo que el cliente puede deber.</p>
                    <p><strong>Crédito Directo:</strong> Permite al cliente consumir sin pagar inmediatamente.</p>
                    <p><strong>Gestión:</strong> Podrás registrar pagos y ajustes desde el perfil del cliente.</p>
                    <p class="mb-0"><strong>Nota:</strong> Un límite de $0 significa que el cliente no tendrá crédito disponible.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
