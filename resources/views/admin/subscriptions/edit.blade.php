@extends('admin.layouts.admin')

@section('title', 'Editar Suscripción - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Editar Suscripción</h1>
            <p class="text-muted">{{ $subscription->restaurant->name }}</p>
        </div>
        <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Plan *</label>
                        <select name="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}"
                                        {{ old('plan_id', $subscription->plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - ${{ number_format($plan->price, 0) }}/{{ $plan->billing_cycle }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">El monto se actualizará automáticamente según el plan seleccionado</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $subscription->status) == 'active' ? 'selected' : '' }}>
                                Activa
                            </option>
                            <option value="cancelled" {{ old('status', $subscription->status) == 'cancelled' ? 'selected' : '' }}>
                                Cancelada
                            </option>
                            <option value="expired" {{ old('status', $subscription->status) == 'expired' ? 'selected' : '' }}>
                                Expirada
                            </option>
                            <option value="suspended" {{ old('status', $subscription->status) == 'suspended' ? 'selected' : '' }}>
                                Suspendida
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inicio *</label>
                            <input type="date" name="starts_at"
                                   class="form-control @error('starts_at') is-invalid @enderror"
                                   value="{{ old('starts_at', $subscription->starts_at->format('Y-m-d')) }}"
                                   required>
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Vencimiento *</label>
                            <input type="date" name="ends_at"
                                   class="form-control @error('ends_at') is-invalid @enderror"
                                   value="{{ old('ends_at', $subscription->ends_at->format('Y-m-d')) }}"
                                   required>
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri ri-information-line me-2"></i>
                        <strong>Nota:</strong> Al cambiar el plan, el monto se actualizará automáticamente. Las fechas deben ser coherentes con el ciclo de facturación del plan.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información Actual</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Plan Actual</label>
                    <p class="mb-0"><strong>{{ $subscription->plan->name }}</strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Monto Actual</label>
                    <p class="mb-0"><strong>${{ number_format($subscription->amount, 0) }}</strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Estado Actual</label>
                    <p class="mb-0">
                        @php
                            $statusColors = [
                                'active' => 'success',
                                'cancelled' => 'danger',
                                'expired' => 'warning',
                                'suspended' => 'secondary'
                            ];
                            $color = $statusColors[$subscription->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-label-{{ $color }}">{{ ucfirst($subscription->status) }}</span>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Período Actual</label>
                    <p class="mb-0">
                        {{ $subscription->starts_at->format('d/m/Y') }}<br>
                        <small class="text-muted">hasta</small><br>
                        {{ $subscription->ends_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Restaurante</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>{{ $subscription->restaurant->name }}</strong></p>
                <p class="text-muted small mb-0">{{ $subscription->restaurant->email }}</p>
                <a href="{{ route('admin.restaurants.show', $subscription->restaurant->id) }}"
                   class="btn btn-sm btn-outline-primary w-100 mt-3">
                    <i class="ri ri-eye-line me-1"></i>Ver Restaurante
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
