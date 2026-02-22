@extends('admin.layouts.admin')

@section('title', 'Categorías de Tutoriales - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Categorías de Tutoriales</h1>
            <p class="text-muted">Gestiona las categorías de tutoriales</p>
        </div>
        <a href="{{ route('admin.tutorial-categories.create') }}" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nueva Categoría
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Tutoriales</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->order }}</td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            @if($category->description)
                                <br><small class="text-muted">{{ substr($category->description, 0, 50) }}{{ strlen($category->description) > 50 ? '...' : '' }}</small>
                            @endif
                        </td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge bg-label-info">{{ $category->tutorials_count }} videos</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-label-success">Activo</span>
                            @else
                                <span class="badge bg-label-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.tutorial-categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                    <i class="ri ri-edit-line"></i>
                                </a>
                                @if($category->tutorials_count == 0)
                                    <form action="{{ route('admin.tutorial-categories.destroy', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ri ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ri ri-folder-line ri-48px text-muted mb-3 d-block"></i>
                            <h5 class="mb-2">No hay categorías creadas</h5>
                            <p class="text-muted mb-4">Crea tu primera categoría de tutoriales</p>
                            <a href="{{ route('admin.tutorial-categories.create') }}" class="btn btn-primary">
                                <i class="ri ri-add-line me-1"></i>Crear Primera Categoría
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($categories->hasPages())
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
@endif
@endsection
