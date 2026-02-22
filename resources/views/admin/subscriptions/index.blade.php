@extends('admin.layouts.admin')

@section('title', 'Suscripciones - Admin')

@section('content')
<div class="mb-4">
    <h1 class="mb-1">Suscripciones</h1>
    <p class="text-muted">Gestiona todas las suscripciones del sistema</p>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activas</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiradas</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendidas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="plan_id" class="form-select">
                        <option value="">Todos los planes</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-search-line me-1"></i>Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="ri ri-refresh-line me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri ri-check-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Activas</p>
                        <h4 class="mb-0">{{ $subscriptions->where('status', 'active')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri ri-time-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Expiradas</p>
                        <h4 class="mb-0">{{ $subscriptions->where('status', 'expired')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri ri-close-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Canceladas</p>
                        <h4 class="mb-0">{{ $subscriptions->where('status', 'cancelled')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri ri-money-dollar-circle-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Ingresos Mes</p>
                        <h4 class="mb-0">${{ number_format($subscriptions->where('status', 'active')->sum('amount'), 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Suscripciones -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Restaurante</th>
                        <th>Plan</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Inicio</th>
                        <th>Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $subscription->restaurant->name }}</strong><br>
                                    <small class="text-muted">{{ $subscription->restaurant->email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">{{ $subscription->plan->name }}</span>
                            </td>
                            <td><strong>${{ number_format($subscription->amount, 0) }}</strong></td>
                            <td>
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
                            </td>
                            <td>{{ $subscription->starts_at->format('d/m/Y') }}</td>
                            <td>
                                {{ $subscription->ends_at->format('d/m/Y') }}
                                @if($subscription->status === 'active' && $subscription->ends_at->diffInDays(now()) <= 7)
                                    <br><small class="text-warning"><i class="ri ri-alert-line"></i> Próximo a vencer</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                       class="btn btn-sm btn-icon btn-text-secondary" title="Ver">
                                        <i class="ri ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}"
                                       class="btn btn-sm btn-icon btn-text-secondary" title="Editar">
                                        <i class="ri ri-edit-line"></i>
                                    </a>
                                    @if($subscription->status === 'active')
                                        <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de cancelar esta suscripción?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-text-danger" title="Cancelar">
                                                <i class="ri ri-close-circle-line"></i>
                                            </button>
                                        </form>
                                    @elseif(in_array($subscription->status, ['expired', 'cancelled']))
                                        <form action="{{ route('admin.subscriptions.renew', $subscription->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-text-success" title="Renovar">
                                                <i class="ri ri-refresh-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No se encontraron suscripciones</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection
