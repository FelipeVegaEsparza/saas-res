@extends('tenant.layouts.admin')
@section('title', 'Categorías')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Categorías</h1>
    <a href="{{ route('tenant.path.categories.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nueva Categoría
    </a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Productos</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->order }}</td>
                            <td><span class="badge bg-{{ $category->active ? 'success' : 'secondary' }}">{{ $category->active ? 'Activa' : 'Inactiva' }}</span></td>
                            <td>
                                <a href="{{ route('tenant.path.categories.edit', ['tenant' => request()->route('tenant'), 'category' => $category]) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-edit-box-line ri-20px"></i>
                                </a>
                                <form action="{{ route('tenant.path.categories.destroy', ['tenant' => request()->route('tenant'), 'category' => $category]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" onclick="return confirm('¿Eliminar?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-center">
                                    <i class="ri ri-list-check ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay categorías registradas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
