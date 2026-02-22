@extends('admin.layouts.admin')

@section('title', 'Suscripción - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Detalle de Suscripción</h1>
            <p class="text-muted">{{ $subscription->restaurant->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-primary">
                <i class="ri ri-edit-line me-1"></i>Editar
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                <i class="ri ri-arrow-left-line me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información de la Suscripción -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Suscripción</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Plan</label>
                        <p class="mb-0"><strong>{{ $subscription->plan->name }}</strong></p>
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
                                $color = $statusColors[$subscription->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-label-{{ $color }}">{{ ucfirst($subscription->status) }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Monto</label>
                        <p class="mb-0"><strong>${{ number_format($subscription->amount, 0) }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Ciclo de Facturación</label>
                        <p class="mb-0">{{ ucfirst($subscription->plan->billing_cycle) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Fecha de Inicio</label>
                        <p class="mb-0">{{ $subscription->starts_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Fecha de Vencimiento</label>
                        <p class="mb-0">
                            {{ $subscription->ends_at->format('d/m/Y H:i') }}
                            @if($subscription->status === 'active')
                                <br><small class="text-muted">({{ $subscription->ends_at->diffForHumans() }})</small>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Creada</label>
                        <p class="mb-0">{{ $subscription->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Última Actualización</label>
                        <p class="mb-0">{{ $subscription->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="mb-3">Acciones</h6>
                    <div class="d-flex gap-2">
                        @if($subscription->status === 'active')
                            <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Estás seguro de cancelar esta suscripción?')">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="ri ri-close-circle-line me-1"></i>Cancelar Suscripción
                                </button>
                            </form>
                        @elseif(in_array($subscription->status, ['expired', 'cancelled']))
                            <form action="{{ route('admin.subscriptions.renew', $subscription->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="ri ri-refresh-line me-1"></i>Renovar Suscripción
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Características del Plan -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Características del Plan</h5>
            </div>
            <div class="card-body">
                @if($subscription->plan->features && count($subscription->plan->features) > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($subscription->plan->features as $feature)
                            <li class="mb-2">
                                <i class="ri ri-check-line text-success me-2"></i>{{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No hay características definidas para este plan</p>
                @endif

                <div class="row mt-4 pt-3 border-top">
                    @if($subscription->plan->max_users)
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Usuarios máximos</small>
                            <p class="mb-0"><strong>{{ $subscription->plan->max_users }}</strong></p>
                        </div>
                    @endif
                    @if($subscription->plan->max_tables)
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Mesas máximas</small>
                            <p class="mb-0"><strong>{{ $subscription->plan->max_tables }}</strong></p>
                        </div>
                    @endif
                    @if($subscription->plan->max_products)
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Productos máximos</small>
                            <p class="mb-0"><strong>{{ $subscription->plan->max_products }}</strong></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Restaurante -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Restaurante</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nombre</label>
                    <p class="mb-0"><strong>{{ $subscription->restaurant->name }}</strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <p class="mb-0">{{ $subscription->restaurant->email }}</p>
                </div>
                @if($subscription->restaurant->phone)
                <div class="mb-3">
                    <label class="text-muted small">Teléfono</label>
                    <p class="mb-0">{{ $subscription->restaurant->phone }}</p>
                </div>
                @endif
                <div class="mb-3">
                    <label class="text-muted small">Estado</label>
                    <p class="mb-0">
                        @if($subscription->restaurant->active)
                            <span class="badge bg-label-success">Activo</span>
                        @else
                            <span class="badge bg-label-danger">Inactivo</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.restaurants.show', $subscription->restaurant->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="ri ri-eye-line me-1"></i>Ver Restaurante
                </a>
            </div>
        </div>

        @if($subscription->status === 'active' && $subscription->ends_at->diffInDays(now()) <= 7)
        <div class="card mt-3">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="ri ri-alert-line me-2"></i>
                    <strong>Atención:</strong> Esta suscripción vence en {{ $subscription->ends_at->diffInDays(now()) }} días.
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
