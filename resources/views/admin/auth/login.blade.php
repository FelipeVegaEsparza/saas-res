@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login - Admin')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
        <div class="authentication-inner py-6">
            <!-- Login Card -->
            <div class="card p-md-7 p-1">
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ route('landing.index') }}" class="app-brand-link gap-2">
                        <span class="app-brand-text demo text-heading fw-semibold" style="font-size: 1.5rem;">Admin Panel</span>
                    </a>
                </div>

                <div class="card-body mt-1">
                    <h4 class="mb-1">Bienvenido 👋</h4>
                    <p class="mb-5">Inicia sesión en el panel de administración</p>

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="form-floating form-floating-outline mb-5">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" placeholder="admin@example.com"
                                   value="{{ old('email') }}" autofocus>
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                               aria-describedby="password" />
                                        <label for="password">Contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-5 d-flex justify-content-between mt-5">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-primary d-grid w-100 mb-5" type="submit">
                            Iniciar Sesión
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('landing.index') }}" class="d-flex align-items-center justify-content-center">
                            <i class="ri-arrow-left-s-line ri-20px me-1"></i>
                            Volver al sitio
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Login Card -->
        </div>
    </div>
</div>
@endsection
