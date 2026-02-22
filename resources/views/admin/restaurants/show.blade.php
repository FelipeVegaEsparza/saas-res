@extends('admin.layouts.admin')

@section('title', $restaurant->name . ' - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">{{ $restaurant->name }}</h1>
            <p class="text-muted">Detalles del restaurante</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-primary">
                <i class="ri ri-edit-line me-1"></i>Editar
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                <i class="ri ri-arrow-left-line me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información General -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nombre</label>
                    <p class="mb-0"><strong>{{ $restaurant->name }}</strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <p class="mb-0">{{ $restaurant->email }}</p>
                </div>
                @if($restaurant->phone)
                <div class="mb-3">
                    <label class="text-muted small">Teléfono</label>
                    <p class="mb-0">{{ $restaurant->phone }}</p>
                </div>
                @endif
                @if($restaurant->address)
                <div class="mb-3">
                    <label class="text-muted small">Dirección</label>
                    <p class="mb-0">{{ $restaurant->address }}</p>
                </div>
                @endif
                <div class="mb-3">
                    <label class="text-muted small">Estado</label>
                    <p class="mb-0">
                        @if($restaurant->active)
                            <span class="badge bg-label-success">Activo</span>
                        @else
                            <span class="badge bg-label-danger">Inactivo</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Registro</label>
                    <p class="mb-0">{{ $restaurant->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($restaurant->tenant)
                <div class="mb-3">
                    <label class="text-muted small">Tenant ID</label>
                    <p class="mb-0"><code>{{ $restaurant->tenant->id }}</code></p>
                </div>
                <div>
                    <a href="/{{ $restaurant->tenant->id }}/dashboard" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                        <i class="ri ri-external-link-line me-1"></i>Acceder al Sistema
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Suscripción -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Suscripción</h5>
            </div>
            <div class="card-body">
                @if($restaurant->activeSubscription)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Plan</label>
                            <p class="mb-0"><strong>{{ $restaurant->activeSubscription->plan->name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Estado</label>
                            <p class="mb-0">
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'cancelled' => 'danger',
                                        'expired' => 'warning',
                                        'suspended' => 'secondary'
                                    ];
                                    $color = $statusColors[$restaurant->activeSubscription->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $color }}">{{ ucfirst($restaurant->activeSubscription->status) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Monto</label>
                            <p class="mb-0"><strong>${{ number_format($restaurant->activeSubscription->amount, 0) }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Ciclo</label>
                            <p class="mb-0">{{ ucfirst($restaurant->activeSubscription->plan->billing_cycle) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Inicio</label>
                            <p class="mb-0">{{ $restaurant->activeSubscription->starts_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Vencimiento</label>
                            <p class="mb-0">{{ $restaurant->activeSubscription->ends_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.subscriptions.show', $restaurant->activeSubscription->id) }}" class="btn btn-sm btn-primary">
                            <i class="ri ri-eye-line me-1"></i>Ver Detalles de Suscripción
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ri ri-file-list-line ri-48px text-muted mb-3"></i>
                        <p class="text-muted">Este restaurante no tiene una suscripción activa</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
