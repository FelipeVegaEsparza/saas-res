@extends('tenant.layouts.admin')

@section('title', 'Estaciones de Preparación')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Estaciones de Preparación</h1>
            <p class="text-muted">Gestiona las áreas de preparación de tu restaurante</p>
        </div>
        <a href="{{ route('tenant.path.preparation-areas.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i> Nueva Estación
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($areas->isEmpty())
            <div class="text-center py-5">
                <i class="ri ri-restaurant-2-line ri-48px text-muted mb-3"></i>
                <h5>No hay estaciones configuradas</h5>
                <p class="text-muted">Crea tu primera estación de preparación</p>
                <a href="{{ route('tenant.path.preparation-areas.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                    <i class="ri ri-add-line me-1"></i> Crear Estación
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Color</th>
                            <th>Icono</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areas as $area)
                            <tr>
                                <td>{{ $area->order }}</td>
                                <td>
                                    <strong>{{ $area->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $area->color }}; color: white;">
                                        {{ $area->color }}
                                    </span>
                                </td>
                                <td>
                                    <i class="ri {{ $area->icon }} ri-24px" style="color: {{ $area->color }};"></i>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $area->products()->count() }} productos</span>
                                </td>
                                <td>
                                    @if($area->active)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tenant.path.preparation-areas.edit', ['tenant' => request()->route('tenant'), 'preparation_area' => $area]) }}"
                                       class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                        <i class="ri ri-edit-line ri-20px"></i>
                                    </a>
                                    <form action="{{ route('tenant.path.preparation-areas.destroy', ['tenant' => request()->route('tenant'), 'preparation_area' => $area]) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta estación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                            <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
