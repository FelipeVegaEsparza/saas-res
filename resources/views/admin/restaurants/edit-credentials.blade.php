@extends('admin.layouts.admin')

@section('title', 'Cambiar Credenciales - ' . $restaurant->name)

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Restaurantes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.show', $restaurant->id) }}">{{ $restaurant->name }}</a></li>
            <li class="breadcrumb-item active">Cambiar Credenciales</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="ri ri-lock-password-line me-2"></i>
                    Cambiar Credenciales de Acceso
                </h4>
                <p class="text-muted mb-0 mt-2">Restaurante: <strong>{{ $restaurant->name }}</strong></p>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="ri ri-alert-line me-2"></i>
                    <strong>Importante:</strong> Al cambiar estas credenciales, el usuario actual del restaurante no podrá acceder con sus credenciales anteriores.
                </div>

                <form action="{{ route('admin.restaurants.updateCredentials', $restaurant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="admin_email" class="form-label">
                            <strong>Email de Acceso</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-control @error('admin_email') is-invalid @enderror"
                            id="admin_email"
                            name="admin_email"
                            value="{{ old('admin_email') }}"
                            required
                            placeholder="admin@restaurante.com"
                        >
                        @error('admin_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Este será el nuevo email para iniciar sesión en el sistema</small>
                    </div>

                    <div class="mb-4">
                        <label for="admin_password" class="form-label">
                            <strong>Nueva Contraseña</strong>
                        </label>
                        <input
                            type="password"
                            class="form-control @error('admin_password') is-invalid @enderror"
                            id="admin_password"
                            name="admin_password"
                            placeholder="Dejar en blanco para mantener la actual"
                        >
                        @error('admin_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mínimo 8 caracteres. Dejar en blanco si no deseas cambiarla</small>
                    </div>

                    <div class="mb-4">
                        <label for="admin_password_confirmation" class="form-label">
                            <strong>Confirmar Nueva Contraseña</strong>
                        </label>
                        <input
                            type="password"
                            class="form-control"
                            id="admin_password_confirmation"
                            name="admin_password_confirmation"
                            placeholder="Confirmar contraseña"
                        >
                    </div>

                    <div class="alert alert-info">
                        <i class="ri ri-information-line me-2"></i>
                        <strong>Nota:</strong> Después de cambiar las credenciales, asegúrate de comunicarlas al administrador del restaurante.
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-label-secondary">
                            <i class="ri ri-arrow-left-line me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Actualizar Credenciales
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
