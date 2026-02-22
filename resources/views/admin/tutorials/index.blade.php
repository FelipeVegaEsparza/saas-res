@extends('admin.layouts.admin')

@section('title', 'Tutoriales - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Tutoriales</h1>
            <p class="text-muted">Gestiona los videos tutoriales</p>
        </div>
        <a href="{{ route('admin.tutorials.create') }}" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nuevo Tutorial
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Video</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tutorials as $tutorial)
                    <tr>
                        <td>{{ $tutorial->order }}</td>
                        <td>
                            @if($tutorial->youtube_id)
                                <img src="https://img.youtube.com/vi/{{ $tutorial->youtube_id }}/default.jpg"
                                     alt="{{ $tutorial->title }}"
                                     class="rounded"
                                     style="width: 80px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 60px;">
                                    <i class="ri ri-video-line ri-24px text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $tutorial->title }}</strong>
                            @if($tutorial->description)
                                <br><small class="text-muted">{{ substr($tutorial->description, 0, 60) }}{{ strlen($tutorial->description) > 60 ? '...' : '' }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-label-primary">{{ $tutorial->category->name }}</span>
                        </td>
                        <td>
                            @if($tutorial->is_active)
                                <span class="badge bg-label-success">Activo</span>
                            @else
                                <span class="badge bg-label-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.tutorials.edit', $tutorial->id) }}" class="btn btn-sm btn-primary">
                                    <i class="ri ri-edit-line"></i>
                                </a>
                                <form action="{{ route('admin.tutorials.destroy', $tutorial->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este tutorial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ri ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ri ri-video-line ri-48px text-muted mb-3 d-block"></i>
                            <h5 class="mb-2">No hay tutoriales creados</h5>
                            <p class="text-muted mb-4">Crea tu primer tutorial</p>
                            <a href="{{ route('admin.tutorials.create') }}" class="btn btn-primary">
                                <i class="ri ri-add-line me-1"></i>Crear Primer Tutorial
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($tutorials->hasPages())
    <div class="mt-4">
        {{ $tutorials->links() }}
    </div>
@endif
@endsection
