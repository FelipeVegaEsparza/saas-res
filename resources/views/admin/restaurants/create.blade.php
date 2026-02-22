@extends('admin.layouts.admin')

@section('title', 'Crear Restaurante - Admin')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Restaurantes</a></li>
            <li class="breadcrumb-item active">Crear Restaurante</li>
        </ol>
    </nav>
    <h1 class="mb-1">Crear Nuevo Restaurante</h1>
    <p class="text-muted">Registra un nuevo cliente en el sistema</p>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.restaurants.store') }}" method="POST">
                    @csrf

                    <h5 class="mb-3">Información del Restaurante</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Restaurante</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (Identificador único)</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                               id="slug" name="slug" value="{{ old('slug') }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Este será el identificador en la URL: /{slug}/dashboard
                        </small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Usuario Administrador</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="admin_name" class="form-label">Nombre del Administrador</label>
                            <input type="text" class="form-control @error('admin_name') is-invalid @enderror"
                                   id="admin_name" name="admin_name" value="{{ old('admin_name', 'Administrador') }}" required>
                            @error('admin_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="admin_email" class="form-label">Email del Administrador</label>
                            <input type="email" class="form-control @error('admin_email') is-invalid @enderror"
                                   id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                            @error('admin_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="admin_password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control @error('admin_password') is-invalid @enderror"
                                   id="admin_password" name="admin_password" required>
                            @error('admin_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>
                        <div class="col-md-6">
                            <label for="admin_password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control"
                                   id="admin_password_confirmation" name="admin_password_confirmation" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Suscripción</h5>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="plan_id" class="form-label">Plan</label>
                            <select class="form-select @error('plan_id') is-invalid @enderror"
                                    id="plan_id" name="plan_id" required>
                                <option value="">Seleccionar plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ${{ number_format($plan->price, 0) }}/{{ $plan->billing_cycle === 'monthly' ? 'mes' : 'año' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="trial_days" class="form-label">Días de Prueba</label>
                            <input type="number" class="form-control @error('trial_days') is-invalid @enderror"
                                   id="trial_days" name="trial_days" value="{{ old('trial_days', 0) }}" min="0" max="90">
                            @error('trial_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">0 = sin prueba</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Crear Restaurante
                        </button>
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-label-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ri ri-information-line me-2"></i>Información
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        El slug debe ser único y sin espacios
                    </li>
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        Se creará automáticamente el tenant
                    </li>
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        Las migraciones se ejecutarán automáticamente
                    </li>
                    <li class="mb-2">
                        <i class="ri ri-check-line text-success me-2"></i>
                        La suscripción se activará inmediatamente
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ri ri-terminal-line me-2"></i>Proceso Automático
                </h5>
                <p class="text-muted small mb-2">El sistema realizará automáticamente:</p>
                <ol class="small mb-0 ps-3">
                    <li class="mb-2">Crear el restaurante y tenant</li>
                    <li class="mb-2">Ejecutar migraciones de la base de datos</li>
                    <li class="mb-2">Insertar datos de demostración</li>
                    <li>Activar la suscripción</li>
                </ol>
                <div class="alert alert-info mt-3 mb-0 small">
                    <i class="ri ri-information-line me-1"></i>
                    <strong>Nota:</strong> Configura las credenciales del usuario administrador del restaurante en el formulario.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function(e) {
        const slug = e.target.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });
});
</script>
@endpush
@endsection
