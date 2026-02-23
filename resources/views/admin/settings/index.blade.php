@extends('admin.layouts.admin')

@section('title', 'Configuración - Admin')

@section('content')
<div class="mb-4">
    <h1 class="mb-1">Configuración del Sistema</h1>
    <p class="text-muted">Personaliza la información de tu empresa y configuraciones generales</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">
                    <i class="ri ri-building-line me-2"></i>
                    <span class="d-none d-sm-inline">Información de la Empresa</span>
                    <span class="d-inline d-sm-none">Empresa</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                    <i class="ri ri-share-line me-2"></i>
                    <span class="d-none d-sm-inline">Redes Sociales</span>
                    <span class="d-inline d-sm-none">Redes</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                    <i class="ri ri-mail-line me-2"></i>
                    <span class="d-none d-sm-inline">Configuración de Emails</span>
                    <span class="d-inline d-sm-none">Emails</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="ri ri-settings-3-line me-2"></i>
                    <span class="d-none d-sm-inline">Configuración General</span>
                    <span class="d-inline d-sm-none">General</span>
                </button>
            </li>
        </ul>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="tab-content pt-4">

                <div class="tab-pane fade show active" id="company" role="tabpanel">
                    @php
                        $companySettings = $settings['company'] ?? [];
                    @endphp

                    <div class="alert alert-info mb-4">
                        <i class="ri ri-information-line me-2"></i>
                        Esta información se mostrará en el sitio web público (landing page).
                    </div>

                    <div class="row">
                        @foreach($companySettings as $setting)
                            @if($setting->key === 'company_logo')
                                <div class="col-12 mb-4">
                                    <label for="company_logo" class="form-label">
                                        <strong>Logo de la Empresa</strong>
                                    </label>
                                    @if($setting->value)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $setting->value) }}" alt="Logo" style="max-height: 100px;" class="img-thumbnail">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="company_logo" name="settings[company_logo]" accept="image/*">
                                    <small class="text-muted">JPG, PNG, SVG. Máximo 2MB. Recomendado: 200x50px</small>
                                </div>
                            @elseif($setting->key === 'company_favicon')
                                <div class="col-12 mb-4">
                                    <label for="company_favicon" class="form-label">
                                        <strong>Favicon (Icono del Navegador)</strong>
                                    </label>
                                    @if($setting->value)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $setting->value) }}" alt="Favicon" style="max-height: 32px;" class="img-thumbnail">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="company_favicon" name="settings[company_favicon]" accept="image/x-icon,image/png,image/jpg,image/svg+xml">
                                    <small class="text-muted">ICO, PNG, JPG, SVG. Máximo 1MB. Recomendado: 32x32px o 64x64px</small>
                                </div>
                            @else
                                <div class="col-md-6 mb-4">
                                    <label for="{{ $setting->key }}" class="form-label">
                                        <strong>{{ ucwords(str_replace('_', ' ', str_replace('company_', '', $setting->key))) }}</strong>
                                    </label>
                                    @if($setting->type === 'textarea')
                                        <textarea class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="3">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                    @else
                                        <input type="text" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}">
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="social" role="tabpanel">
                    @php
                        $socialSettings = $settings['social'] ?? [];
                    @endphp

                    <div class="alert alert-info mb-4">
                        <i class="ri ri-information-line me-2"></i>
                        Las redes sociales aparecerán en el footer. Solo se muestran las que tengan URL.
                    </div>

                    <div class="row">
                        @foreach($socialSettings as $setting)
                            <div class="col-md-6 mb-4">
                                <label for="{{ $setting->key }}" class="form-label">
                                    <strong>
                                        @if(str_contains($setting->key, 'facebook'))
                                            <i class="ri ri-facebook-fill text-primary me-1"></i>Facebook
                                        @elseif(str_contains($setting->key, 'instagram'))
                                            <i class="ri ri-instagram-line text-danger me-1"></i>Instagram
                                        @elseif(str_contains($setting->key, 'twitter'))
                                            <i class="ri ri-twitter-fill text-info me-1"></i>Twitter
                                        @elseif(str_contains($setting->key, 'linkedin'))
                                            <i class="ri ri-linkedin-fill text-primary me-1"></i>LinkedIn
                                        @elseif(str_contains($setting->key, 'youtube'))
                                            <i class="ri ri-youtube-fill text-danger me-1"></i>YouTube
                                        @endif
                                    </strong>
                                </label>
                                <input type="url" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}" placeholder="https://ejemplo.com/tuperfil">
                                <small class="text-muted">URL completa (opcional)</small>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="email" role="tabpanel">
                    @php
                        $emailSettings = $settings['email'] ?? [];
                    @endphp

                    <div class="alert alert-info mb-4">
                        <i class="ri ri-information-line me-2"></i>
                        Textos para los emails de bienvenida automáticos.
                    </div>

                    @foreach($emailSettings as $setting)
                        <div class="mb-4">
                            <label for="{{ $setting->key }}" class="form-label">
                                <strong>{{ ucwords(str_replace('_', ' ', str_replace('email_', '', $setting->key))) }}</strong>
                            </label>
                            @if($setting->type === 'textarea')
                                <textarea class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="4">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @else
                                <input type="text" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}">
                            @endif
                            @if($setting->key === 'email_welcome_subject')
                                <small class="text-muted">Asunto del email. Se agregará el nombre del restaurante.</small>
                            @elseif($setting->key === 'email_welcome_message')
                                <small class="text-muted">Mensaje principal después del saludo.</small>
                            @elseif($setting->key === 'email_footer_text')
                                <small class="text-muted">Texto al final antes de la firma.</small>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="tab-pane fade" id="general" role="tabpanel">
                    @php
                        $generalSettings = $settings['general'] ?? [];
                    @endphp

                    @foreach($generalSettings as $setting)
                        <div class="mb-4">
                            <label for="{{ $setting->key }}" class="form-label">
                                <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                            </label>
                            @if($setting->type === 'textarea')
                                <textarea class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="4">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @else
                                <input type="text" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}">
                            @endif
                            @if($setting->key === 'support_email')
                                <small class="text-muted">Email de soporte técnico.</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="ri ri-information-line me-1"></i>
                            Los cambios se aplicarán inmediatamente
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-label-secondary">
                            <i class="ri ri-close-line me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
