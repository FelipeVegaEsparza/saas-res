@extends('landing.layouts.app')

@section('title', 'Tutoriales - ' . $companyName)

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative" style="padding: 80px 0 60px 0;">
    <div class="container position-relative" style="z-index: 1;">
        <div class="text-center">
            <h1 class="mb-3">Tutoriales</h1>
            <p class="mb-0" style="font-size: 1.15rem; opacity: 0.95;">
                Aprende a usar todas las funcionalidades de nuestro sistema
            </p>
        </div>
    </div>
</section>

<!-- Tutoriales Section -->
<section class="py-5">
    <div class="container py-4">
        @forelse($categories as $category)
            <div class="mb-5">
                <div class="mb-4">
                    <h2 class="section-title mb-2">{{ $category->name }}</h2>
                    @if($category->description)
                        <p class="text-muted">{{ $category->description }}</p>
                    @endif
                </div>

                @if($category->activeTutorials->count() > 0)
                    <div class="row g-4">
                        @foreach($category->activeTutorials as $tutorial)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    @if($tutorial->youtube_id)
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $tutorial->youtube_id }}"
                                                    allowfullscreen
                                                    class="card-img-top"></iframe>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">{{ $tutorial->title }}</h5>
                                        @if($tutorial->description)
                                            <p class="card-text text-muted">{{ $tutorial->description }}</p>
                                        @endif
                                        <a href="{{ $tutorial->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ri ri-external-link-line me-1"></i>Ver en YouTube
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No hay tutoriales disponibles en esta categoría.
                    </div>
                @endif
            </div>

            @if(!$loop->last)
                <hr class="my-5">
            @endif
        @empty
            <div class="text-center py-5">
                <i class="ri ri-video-line" style="font-size: 4rem; color: #cbd5e0;"></i>
                <h3 class="mt-4 mb-2">Próximamente</h3>
                <p class="text-muted">Estamos preparando tutoriales para ayudarte a usar el sistema.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: #f7fafc;">
    <div class="container py-4">
        <div class="text-center">
            <h2 class="section-title mb-3">¿Necesitas ayuda personalizada?</h2>
            <p class="section-subtitle mb-4">Nuestro equipo está disponible para ayudarte</p>
            <a href="{{ route('landing.contact') }}" class="btn btn-primary btn-lg px-5">
                <i class="ri ri-mail-line me-2"></i>Contáctanos
            </a>
        </div>
    </div>
</section>
@endsection
