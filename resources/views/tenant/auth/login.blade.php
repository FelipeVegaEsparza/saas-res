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
                    <h4 class="mb-1">Bienvenido! 👋</h4>
                    <p class="mb-5">Inicia sesión en tu cuenta</p>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tenant.path.login.post', ['tenant' => request()->route('tenant')]) }}" class="mb-5">
                        @csrf

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="correo@ejemplo.com" required autofocus>
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <div class="form-password-toggle">
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
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-5 d-flex justify-content-between mt-5">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                        </div>

                        <div class="mb-5">
                            <button class="btn btn-primary d-grid w-100" type="submit">Iniciar Sesión</button>
                        </div>
                    </form>

                    <p class="text-center mb-5">
                        <a href="{{ route('tenant.path.menu.index', ['tenant' => request()->route('tenant')]) }}">
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
