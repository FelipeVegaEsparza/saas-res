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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: {{ $colorScheme['primary'] }};
        --secondary-color: {{ $colorScheme['secondary'] }};
        --accent-color: {{ $colorScheme['accent'] }};
        --background-color: {{ $colorScheme['background'] }};
        --text-color: {{ $colorScheme['text'] }};
        --shadow-light: 0 2px 10px rgba(0,0,0,0.08);
        --shadow-medium: 0 4px 20px rgba(0,0,0,0.12);
        --shadow-heavy: 0 8px 30px rgba(0,0,0,0.16);
        --border-radius: 16px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, var(--background-color) 0%, rgba(255,255,255,0.9) 100%);
        color: var(--text-color);
        line-height: 1.6;
        @if($restaurant->menu_background_image)
        background-image:
            linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%),
            url('{{ Storage::url($restaurant->menu_background_image) }}');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        @endif
        min-height: 100vh;
    }

    .menu-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-medium);
        position: relative;
        overflow: hidden;
    }

    .menu-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .menu-header .container {
        position: relative;
        z-index: 1;
    }

    .menu-header h1 {
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .menu-header .badge {
        background: rgba(255,255,255,0.2) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }

    .restaurant-info {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(255,255,255,0.8);
        position: relative;
        overflow: hidden;
    }

    .restaurant-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
    }

    .restaurant-info p {
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .restaurant-info i {
        width: 20px;
        margin-right: 0.5rem;
    }

    .category-section {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 2.5rem;
        margin-bottom: 3rem;
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(255,255,255,0.8);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .category-section:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .category-title {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 2rem;
        margin: 0 0 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid var(--secondary-color);
        position: relative;
        display: inline-block;
    }

    .category-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 50%;
        height: 3px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(255,255,255,0.9);
        height: 100%;
        position: relative;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }

    .product-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-heavy);
    }

    .product-card:hover::before {
        opacity: 1;
    }

    .product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-image-placeholder {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, var(--background-color) 0%, #f8f9fa 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .product-card:hover .product-image-placeholder {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    }

    .product-card:hover .product-image-placeholder i {
        color: white !important;
        transform: scale(1.1);
    }

    .card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.25rem;
        color: var(--text-color);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .card-text {
        font-size: 0.9rem;
        line-height: 1.5;
        color: rgba(108, 117, 125, 1);
    }

    .price-tag {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--secondary-color);
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .badge-tag {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        margin-right: 0.5rem;
        margin-bottom: 0.25rem;
        background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
        color: white;
        border: none;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .featured-badge {
        background: linear-gradient(135deg, #ffd700, #ffed4e) !important;
        color: #8b5a00 !important;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
    }

    .social-links {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .social-links a {
        color: var(--text-color);
        font-size: 1.75rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255,255,255,0.8);
        box-shadow: var(--shadow-light);
    }

    .social-links a:hover {
        color: white;
        background: var(--secondary-color);
        transform: translateY(-2px) scale(1.1);
        box-shadow: var(--shadow-medium);
    }

    .time-badge {
        background: rgba(255,255,255,0.9);
        color: var(--text-color);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid rgba(0,0,0,0.1);
    }

    footer {
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(20px);
        border-top: 1px solid rgba(0,0,0,0.1);
        margin-top: 4rem;
    }

    .empty-menu {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(255,255,255,0.8);
    }

    .empty-menu i {
        font-size: 5rem;
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
        opacity: 0.8;
    }

    .empty-menu h3 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .menu-header {
            padding: 2rem 0;
        }

        .menu-header h1 {
            font-size: 2rem;
        }

        .category-title {
            font-size: 1.75rem;
        }

        .restaurant-info,
        .category-section {
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .product-card:hover {
            transform: translateY(-4px) scale(1.01);
        }

        .social-links {
            justify-content: center;
        }
    }

    /* Animation for page load */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .category-section {
        animation: fadeInUp 0.6s ease-out;
    }

    .category-section:nth-child(even) {
        animation-delay: 0.1s;
    }

    .category-section:nth-child(odd) {
        animation-delay: 0.2s;
    }
</style>
@endsection
@section('content')
<div class="menu-header">
    <div class="container">
        <div class="text-center">
            @if($restaurant->logo_horizontal)
                <img src="{{ Storage::url($restaurant->logo_horizontal) }}" alt="{{ $restaurant->name }}" style="max-height: 100px; margin-bottom: 1.5rem; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
            @else
                <h1 class="mb-3">{{ $restaurant->name }}</h1>
            @endif
            <p class="mb-0 fs-5 opacity-90">Menú Digital</p>
            @if($table)
                <div class="mt-4">
                    <span class="badge">
                        <i class="ri ri-restaurant-2-line me-2"></i>Mesa {{ $table->number }}
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
            <div class="col-lg-8">
                @if($restaurant->description)
                    <div class="mb-3">
                        <h5 class="text-primary mb-2">
                            <i class="ri ri-information-line me-2"></i>Acerca de nosotros
                        </h5>
                        <p class="mb-0 fs-6">{{ $restaurant->description }}</p>
                    </div>
                @endif

                <div class="row">
                    @if($restaurant->address)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="ri ri-map-pin-line text-primary me-2 mt-1"></i>
                                <div>
                                    <strong class="d-block">Dirección</strong>
                                    <span class="text-muted">{{ $restaurant->address }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($restaurant->phone)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="ri ri-phone-line text-primary me-2 mt-1"></i>
                                <div>
                                    <strong class="d-block">Teléfono</strong>
                                    <a href="tel:{{ $restaurant->phone }}" class="text-decoration-none text-muted">{{ $restaurant->phone }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($restaurant->facebook || $restaurant->instagram || $restaurant->tiktok || $restaurant->twitter)
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="text-center text-lg-end">
                    <h6 class="text-primary mb-3">
                        <i class="ri ri-share-line me-2"></i>Síguenos
                    </h6>
                    <div class="social-links justify-content-center justify-content-lg-end">
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
            </div>
            @endif
        </div>
    </div>
    @endif
    @if($categories->isEmpty())
        <div class="empty-menu">
            <i class="ri ri-restaurant-line"></i>
            <h3>Menú en construcción</h3>
            <p class="text-muted mb-0">Pronto tendremos productos disponibles para ti</p>
        </div>
    @else
        @foreach($categories as $category)
            <div class="category-section">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="category-title mb-0">{{ $category->name }}</h2>
                        @if($category->description)
                            <p class="text-muted mt-2 mb-0">{{ $category->description }}</p>
                        @endif
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark">
                            {{ $category->activeProducts->count() }} {{ $category->activeProducts->count() === 1 ? 'producto' : 'productos' }}
                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($category->activeProducts as $product)
                        <div class="col-md-6 col-xl-4">
                            <div class="card product-card h-100">
                                @if($product->image)
                                    <div class="position-relative overflow-hidden">
                                        <img src="{{ Storage::url($product->image) }}" class="product-image" alt="{{ $product->name }}">
                                        @if($product->featured)
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge featured-badge">
                                                    <i class="ri ri-star-fill me-1"></i>Destacado
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="product-image-placeholder position-relative">
                                        <i class="ri ri-restaurant-2-line" style="font-size: 4rem; color: var(--secondary-color); transition: all 0.3s ease;"></i>
                                        @if($product->featured)
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge featured-badge">
                                                    <i class="ri ri-star-fill me-1"></i>Destacado
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title">{{ $product->name }}</h5>

                                        @if($product->description)
                                            <p class="card-text mb-3">
                                                {{ \Illuminate\Support\Str::limit($product->description, 120) }}
                                            </p>
                                        @endif

                                        @if($product->tags)
                                            <div class="mb-3">
                                                @foreach($product->tags as $tag)
                                                    <span class="badge-tag">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div>
                                            <span class="price-tag">@price($product->price)</span>
                                        </div>

                                        @if($product->preparation_time)
                                            <div>
                                                <span class="time-badge">
                                                    <i class="ri ri-time-line me-1"></i>{{ $product->preparation_time }} min
                                                </span>
                                            </div>
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
<footer class="py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <h6 class="text-primary mb-2">{{ $restaurant->name }}</h6>
                @if($restaurant->address)
                    <p class="text-muted small mb-0">
                        <i class="ri ri-map-pin-line me-1"></i>{{ $restaurant->address }}
                    </p>
                @endif
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                @if($restaurant->phone)
                    <p class="text-muted mb-2">
                        <i class="ri ri-phone-line me-1"></i>
                        <a href="tel:{{ $restaurant->phone }}" class="text-decoration-none text-muted">{{ $restaurant->phone }}</a>
                    </p>
                @endif
                @if($restaurant->facebook || $restaurant->instagram || $restaurant->tiktok || $restaurant->twitter)
                <div class="social-links justify-content-center justify-content-md-end">
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
                @endif
            </div>
        </div>
    </div>
</footer>
@endsection
