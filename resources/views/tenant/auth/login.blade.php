@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
use Illuminate\Support\Facades\Storage;
$restaurant = tenant()->restaurant();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login - ' . $restaurant->name)

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
<style>
    @if($restaurant->menu_background_image)
    body {
        background-image: url('{{ Storage::url($restaurant->menu_background_image) }}');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
    }
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(8px);
        z-index: -1;
    }

    /* Modo claro */
    .light-style body::before {
        background: rgba(255, 255, 255, 0.85);
    }

    /* Modo oscuro */
    .dark-style body::before {
        background: rgba(0, 0, 0, 0.85);
    }
    @endif

    /* Mejoras profesionales del login */
    .authentication-wrapper .card {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-header h4 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #5f6368;
    }

    .login-header p {
        font-size: 0.95rem;
        color: #80868b;
        margin-bottom: 0;
    }

    .form-floating-with-icon {
        position: relative;
    }

    .form-floating-with-icon .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #80868b;
        font-size: 1.25rem;
        z-index: 5;
        pointer-events: none;
    }

    .form-floating-with-icon .form-control {
        padding-left: 48px;
    }

    .form-floating-with-icon .form-floating-outline label {
        left: 48px;
    }

    .btn-login {
        padding: 12px 24px;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(105, 108, 255, 0.3);
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.4);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .form-check-input:checked {
        background-color: #696cff;
        border-color: #696cff;
    }

    .link-menu-public {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #696cff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .link-menu-public:hover {
        color: #5f61e6;
        gap: 8px;
    }

    .app-brand {
        margin-bottom: 1.5rem;
    }

    .app-brand img {
        max-height: 50px;
        transition: transform 0.3s ease;
    }

    .app-brand:hover img {
        transform: scale(1.05);
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    .form-control:focus,
    .form-control:focus-visible {
        border-color: #696cff;
        box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.15);
    }

    .input-group-text {
        border-left: none;
        background: transparent;
    }

    .form-password-toggle .form-control {
        border-right: none;
    }
</style>
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
        <div class="authentication-inner py-6">
            <div class="card p-md-7 p-1">
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ route('tenant.path.menu.index', ['tenant' => request()->route('tenant')]) }}" class="app-brand-link gap-2">
                        @if($restaurant->logo_horizontal)
                            <img src="{{ Storage::url($restaurant->logo_horizontal) }}" alt="{{ $restaurant->name }}" style="max-height: 60px;">
                        @else
                            <span class="app-brand-text demo text-heading fw-semibold">{{ $restaurant->name }}</span>
                        @endif
                    </a>
                </div>

                <div class="card-body mt-1">
                    <div class="login-header">
                        <h4>Bienvenido! 👋</h4>
                        <p>Inicia sesión en tu cuenta</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tenant.path.login.post', ['tenant' => request()->route('tenant')]) }}" class="mb-4">
                        @csrf

                        <div class="form-floating-with-icon mb-4">
                            <i class="ri-mail-line input-icon"></i>
                            <div class="form-floating form-floating-outline">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="correo@ejemplo.com" required autofocus>
                                <label for="email">Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-password-toggle">
                                <div class="form-floating-with-icon">
                                    <i class="ri-lock-line input-icon"></i>
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password"
                                                   placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                            <label for="password">Contraseña</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer">
                                            <i class="ri-eye-off-line ri-20px"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button class="btn btn-primary btn-login d-grid w-100" type="submit">
                                <span>Iniciar Sesión</span>
                            </button>
                        </div>
                    </form>

                    <p class="text-center mb-4">
                        <a href="{{ route('tenant.path.menu.index', ['tenant' => request()->route('tenant')]) }}" class="link-menu-public">
                            <i class="ri-restaurant-line"></i>
                            <span>Ver Menú Público</span>
                        </a>
                    </p>
                </div>
            </div>

            <img alt="mask"
                 src="{{ asset('assets/img/illustrations/auth-basic-login-mask-' . $configData['theme'] . '.png') }}"
                 class="authentication-image d-none d-lg-block"
                 data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                 data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
    </div>
</div>
@endsection
