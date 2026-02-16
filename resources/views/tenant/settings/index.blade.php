@extends('tenant.layouts.admin')

@section('title', 'Configuración')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="mb-4">
    <h1>Configuración del Restaurante</h1>
</div>

<form action="{{ route('tenant.path.settings.update', ['tenant' => request()->route('tenant')]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Información Básica -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Información Básica</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Restaurante *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $restaurant->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT</label>
                        <input type="text" class="form-control @error('rut') is-invalid @enderror"
                               id="rut" name="rut" value="{{ old('rut', $restaurant->rut) }}" placeholder="12.345.678-9">
                        @error('rut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Número de Contacto</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $restaurant->phone) }}" placeholder="+56 9 1234 5678">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                               id="address" name="address" value="{{ old('address', $restaurant->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Breve descripción de tu restaurante</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_horizontal" class="form-label">Logo Horizontal</label>
                        @if($restaurant->logo_horizontal)
                            <div class="mb-2">
                                <img src="{{ Storage::url($restaurant->logo_horizontal) }}" alt="Logo Horizontal" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo_horizontal') is-invalid @enderror"
                               id="logo_horizontal" name="logo_horizontal" accept="image/*">
                        @error('logo_horizontal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: JPG, PNG, SVG. Máx: 2MB</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_square" class="form-label">Logo Cuadrado</label>
                        @if($restaurant->logo_square)
                            <div class="mb-2">
                                <img src="{{ Storage::url($restaurant->logo_square) }}" alt="Logo Cuadrado" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo_square') is-invalid @enderror"
                               id="logo_square" name="logo_square" accept="image/*">
                        @error('logo_square')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formato: JPG, PNG, SVG. Máx: 2MB</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Redes Sociales -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Redes Sociales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="facebook" class="form-label">
                            <i class="ri ri-facebook-fill"></i> Facebook
                        </label>
                        <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                               id="facebook" name="facebook" value="{{ old('facebook', $restaurant->facebook) }}"
                               placeholder="https://facebook.com/tu-restaurante">
                        @error('facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="instagram" class="form-label">
                            <i class="ri ri-instagram-fill"></i> Instagram
                        </label>
                        <input type="url" class="form-control @error('instagram') is-invalid @enderror"
                               id="instagram" name="instagram" value="{{ old('instagram', $restaurant->instagram) }}"
                               placeholder="https://instagram.com/tu-restaurante">
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tiktok" class="form-label">
                            <i class="ri ri-tiktok-fill"></i> TikTok
                        </label>
                        <input type="url" class="form-control @error('tiktok') is-invalid @enderror"
                               id="tiktok" name="tiktok" value="{{ old('tiktok', $restaurant->tiktok) }}"
                               placeholder="https://tiktok.com/@tu-restaurante">
                        @error('tiktok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="twitter" class="form-label">
                            <i class="ri ri-twitter-x-fill"></i> X (Twitter)
                        </label>
                        <input type="url" class="form-control @error('twitter') is-invalid @enderror"
                               id="twitter" name="twitter" value="{{ old('twitter', $restaurant->twitter) }}"
                               placeholder="https://x.com/tu-restaurante">
                        @error('twitter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carta QR -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Carta QR / Menú Digital</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="menu_background_image" class="form-label">Imagen de Fondo del Menú</label>
                @if($restaurant->menu_background_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($restaurant->menu_background_image) }}" alt="Fondo Menú" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif
                <input type="file" class="form-control @error('menu_background_image') is-invalid @enderror"
                       id="menu_background_image" name="menu_background_image" accept="image/*">
                @error('menu_background_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Formato: JPG, PNG. Máx: 4MB</small>
            </div>

            <div class="mb-4">
                <label class="form-label">Esquema de Color del Menú</label>
                <div class="row g-3">
                    @foreach($colorSchemes as $key => $scheme)
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic {{ old('menu_color_scheme', $restaurant->menu_color_scheme) === $key ? 'checked' : '' }}">
                                <label class="form-check-label custom-option-content" for="color_{{ $key }}">
                                    <input class="form-check-input" type="radio" name="menu_color_scheme"
                                           id="color_{{ $key }}" value="{{ $key }}"
                                           {{ old('menu_color_scheme', $restaurant->menu_color_scheme) === $key ? 'checked' : '' }}>
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">{{ $scheme['name'] }}</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <div class="d-flex gap-2 mt-2">
                                            <div style="width: 30px; height: 30px; background-color: {{ $scheme['primary'] }}; border-radius: 4px;"></div>
                                            <div style="width: 30px; height: 30px; background-color: {{ $scheme['secondary'] }}; border-radius: 4px;"></div>
                                            <div style="width: 30px; height: 30px; background-color: {{ $scheme['accent'] }}; border-radius: 4px;"></div>
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="ri ri-qr-code-line ri-24px me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Código QR de tu Menú</h6>
                        <p class="mb-2">Descarga el código QR para imprimirlo en tus mesas</p>
                        <a href="{{ route('tenant.path.settings.download-qr', ['tenant' => request()->route('tenant')]) }}"
                           class="btn btn-sm btn-primary" target="_blank">
                            <i class="ri ri-download-line me-1"></i> Descargar QR
                        </a>
                        <a href="{{ url('/' . request()->route('tenant') . '/menu') }}"
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="ri ri-external-link-line me-1"></i> Ver Menú
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="ri ri-save-line me-1"></i> Guardar Cambios
        </button>
    </div>
</form>
@endsection
