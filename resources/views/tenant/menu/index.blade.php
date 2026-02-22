@extends('tenant.layouts.app')

@section('title', $restaurant->name . ' - Menú Digital')

@section('page-style')
@php
use Illuminate\Support\Facades\Storage;

$colorScheme = match($restaurant->menu_color_scheme ?? 'classic') {
    'modern' => [
        'primary' => '#3498db',
        'secondary' => '#2ecc71',
        'accent' => '#9b59b6',
        'background' => '#ffffff',
        'text' => '#34495e',
    ],
    'elegant' => [
        'primary' => '#1a1a1a',
        'secondary' => '#d4af37',
        'accent' => '#8b7355',
        'background' => '#f5f5f5',
        'text' => '#1a1a1a',
    ],
    'fresh' => [
        'primary' => '#27ae60',
        'secondary' => '#16a085',
        'accent' => '#f1c40f',
        'background' => '#e8f8f5',
        'text' => '#2c3e50',
    ],
    'warm' => [
        'primary' => '#e67e22',
        'secondary' => '#d35400',
        'accent' => '#c0392b',
        'background' => '#fef5e7',
        'text' => '#5d4037',
    ],
    default => [
        'primary' => '#2c3e50',
        'secondary' => '#e74c3c',
        'accent' => '#f39c12',
        'background' => '#ecf0f1',
        'text' => '#2c3e50',
    ],
};
@endphp
<style>
    :root {
        --primary-color: {{ $colorScheme['primary'] }};
        --secondary-color: {{ $colorScheme['secondary'] }};
        --accent-color: {{ $colorScheme['accent'] }};
        --background-color: {{ $colorScheme['background'] }};
        --text-color: {{ $colorScheme['text'] }};
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
        @if($restaurant->menu_background_image)
        background-image: url('{{ Storage::url($restaurant->menu_background_image) }}');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        @endif
    }

    .menu-header {
        background: var(--primary-color);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .restaurant-info {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 0.75rem;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .category-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .category-title {
        color: var(--primary-color);
        font-weight: 600;
        margin: 0 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid var(--secondary-color);
    }

    .price-tag {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .badge-tag {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-right: 0.25rem;
        background-color: var(--accent-color);
        color: white;
    }

    .social-links a {
        color: var(--text-color);
        font-size: 1.5rem;
        margin: 0 0.5rem;
        transition: color 0.2s;
    }

    .social-links a:hover {
        color: var(--secondary-color);
    }

    footer {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
</style>
@endsection

@section('content')
<div class="menu-header">
    <div class="container">
        <div class="text-center">
            @if($restaurant->logo_horizontal)
                <img src="{{ Storage::url($restaurant->logo_horizontal) }}" alt="{{ $restaurant->name }}" style="max-height: 80px; margin-bottom: 1rem;">
            @else
                <h1 class="mb-2">{{ $restaurant->name }}</h1>
            @endif
            <p class="mb-0">Menú Digital</p>
            @if($table)
                <div class="mt-3">
                    <span class="badge bg-light text-dark">
                        <i class="ri ri-restaurant-2-line"></i> Mesa {{ $table->number }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- Información del Restaurante -->
    @if($restaurant->description || $restaurant->address || $restaurant->phone)
    <div class="restaurant-info">
        <div class="row align-items-center">
            <div class="col-md-8">
                @if($restaurant->description)
                    <p class="mb-2">{{ $restaurant->description }}</p>
                @endif
                @if($restaurant->address)
                    <p class="mb-1">
                        <i class="ri ri-map-pin-line text-primary"></i>
                        <strong>Dirección:</strong> {{ $restaurant->address }}
                    </p>
                @endif
                @if($restaurant->phone)
                    <p class="mb-0">
                        <i class="ri ri-phone-line text-primary"></i>
                        <strong>Teléfono:</strong>
                        <a href="tel:{{ $restaurant->phone }}" class="text-decoration-none">{{ $restaurant->phone }}</a>
                    </p>
                @endif
            </div>
            @if($restaurant->facebook || $restaurant->instagram || $restaurant->tiktok || $restaurant->twitter)
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="social-links">
                    <strong class="d-block mb-2">Síguenos:</strong>
                    @if($restaurant->facebook)
                        <a href="{{ $restaurant->facebook }}" target="_blank" title="Facebook">
                            <i class="ri ri-facebook-circle-fill"></i>
                        </a>
                    @endif
                    @if($restaurant->instagram)
                        <a href="{{ $restaurant->instagram }}" target="_blank" title="Instagram">
                            <i class="ri ri-instagram-fill"></i>
                        </a>
                    @endif
                    @if($restaurant->tiktok)
                        <a href="{{ $restaurant->tiktok }}" target="_blank" title="TikTok">
                            <i class="ri ri-tiktok-fill"></i>
                        </a>
                    @endif
                    @if($restaurant->twitter)
                        <a href="{{ $restaurant->twitter }}" target="_blank" title="X (Twitter)">
                            <i class="ri ri-twitter-x-fill"></i>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($categories->isEmpty())
        <div class="category-section text-center py-5">
            <i class="ri ri-restaurant-line" style="font-size: 4rem; color: var(--secondary-color);"></i>
            <h3 class="mt-3">Menú en construcción</h3>
            <p class="text-muted">Pronto tendremos productos disponibles</p>
        </div>
    @else
        @foreach($categories as $category)
            <div class="category-section">
                <h2 class="category-title">
                    {{ $category->name }}
                </h2>

                @if($category->description)
                    <p class="text-muted mb-4">{{ $category->description }}</p>
                @endif

                <div class="row g-4">
                    @foreach($category->activeProducts as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card product-card h-100">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" class="product-image" alt="{{ $product->name }}">
                                @else
                                    <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="ri ri-restaurant-2-line" style="font-size: 4rem; color: var(--secondary-color);"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $product->name }}</h5>
                                        @if($product->featured)
                                            <span class="badge" style="background-color: var(--accent-color);">
                                                <i class="ri ri-star-fill"></i>
                                            </span>
                                        @endif
                                    </div>

                                    @if($product->description)
                                        <p class="card-text text-muted small mb-3">
                                            {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                                        </p>
                                    @endif

                                    @if($product->tags)
                                        <div class="mb-3">
                                            @foreach($product->tags as $tag)
                                                <span class="badge badge-tag">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="price-tag">@price($product->price)</span>

                                        @if($product->preparation_time)
                                            <small class="text-muted">
                                                <i class="ri ri-time-line"></i> {{ $product->preparation_time }} min
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Footer -->
<footer class="bg-white border-top py-4 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-muted mb-2 mb-md-0">
                    <strong>{{ $restaurant->name }}</strong>
                </p>
                @if($restaurant->address)
                    <p class="text-muted small mb-0">{{ $restaurant->address }}</p>
                @endif
            </div>
            <div class="col-md-6 text-center text-md-end">
                @if($restaurant->phone)
                    <p class="text-muted mb-2">
                        <i class="ri ri-phone-line"></i> {{ $restaurant->phone }}
                    </p>
                @endif
                @if($restaurant->facebook || $restaurant->instagram || $restaurant->tiktok || $restaurant->twitter)
                <div class="social-links">
                    @if($restaurant->facebook)
                        <a href="{{ $restaurant->facebook }}" target="_blank"><i class="ri ri-facebook-circle-fill"></i></a>
                    @endif
                    @if($restaurant->instagram)
                        <a href="{{ $restaurant->instagram }}" target="_blank"><i class="ri ri-instagram-fill"></i></a>
                    @endif
                    @if($restaurant->tiktok)
                        <a href="{{ $restaurant->tiktok }}" target="_blank"><i class="ri ri-tiktok-fill"></i></a>
                    @endif
                    @if($restaurant->twitter)
                        <a href="{{ $restaurant->twitter }}" target="_blank"><i class="ri ri-twitter-x-fill"></i></a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</footer>
@endsection
