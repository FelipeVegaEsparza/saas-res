@extends('admin.layouts.admin')

@section('title', 'Planes - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Planes</h1>
            <p class="text-muted">Gestiona los planes de suscripción</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nuevo Plan
        </a>
    </div>
</div>

<div class="row">
    @forelse($plans as $plan)
        <div class="col-lg-4 mb-4">
            <div class="card h-100 {{ !$plan->active ? 'border-secondary' : '' }}">
                <div class="card-header {{ !$plan->active ? 'bg-light' : '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $plan->name }}</h5>
                        @if($plan->active)
                            <span class="badge bg-label-success">Activo</span>
                        @else
                            <span class="badge bg-label-secondary">Inactivo</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($plan->description)
                        <p class="text-muted mb-3">{{ $plan->description }}</p>
                    @endif

                    <div class="mb-4">
                        <h2 class="mb-0">
                            ${{ number_format($plan->price, 0) }}
                            <small class="text-muted fs-6">/{{ $plan->billing_cycle === 'monthly' ? 'mes' : 'año' }}</small>
                        </h2>
                    </div>

                    @if($plan->features && count($plan->features) > 0)
                        <div class="mb-4">
                            <h6 class="mb-3">Características:</h6>
                            <ul class="list-unstyled">
                                @foreach($plan->features as $feature)
                                    <li class="mb-2">
                                        <i class="ri ri-check-line text-success me-2"></i>{{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="border-top pt-3 mb-3">
                        <div class="row text-center">
                            @if($plan->max_users)
                                <div class="col-4">
                                    <small class="text-muted d-block">Usuarios</small>
                                    <strong>{{ $plan->max_users }}</strong>
                                </div>
                            @endif
                            @if($plan->max_tables)
                                <div class="col-4">
                                    <small class="text-muted d-block">Mesas</small>
                                    <strong>{{ $plan->max_tables }}</strong>
                                </div>
                            @endif
                            @if($plan->max_products)
                                <div class="col-4">
                                    <small class="text-muted d-block">Productos</small>
                                    <strong>{{ $plan->max_products }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Suscripciones activas:</small>
                        <strong class="ms-2">{{ $plan->subscriptions_count }}</strong>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="ri ri-edit-line me-1"></i>Editar
                        </a>
                        @if($plan->subscriptions_count == 0)
                            <form action="{{ route('admin.plans.destroy', $plan->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este plan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri ri-price-tag-3-line ri-48px text-muted mb-3"></i>
                    <h5 class="mb-2">No hay planes creados</h5>
                    <p class="text-muted mb-4">Crea tu primer plan de suscripción</p>
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                        <i class="ri ri-add-line me-1"></i>Crear Primer Plan
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
