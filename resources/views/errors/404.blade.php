@php
use App\Models\SystemSetting;
$companyName = SystemSetting::get('company_name', 'RestaurantSaaS');
$companyLogo = SystemSetting::get('company_logo', '');
$companyFavicon = SystemSetting::get('company_favicon', '');
$companyPhone = SystemSetting::get('company_phone', '+56 9 1234 5678');
$companyEmail = SystemSetting::get('company_email', 'info@restaurantsaas.com');
$companyAddress = SystemSetting::get('company_address', '');
$companyDescription = SystemSetting::get('company_description', 'Sistema completo de gestión para restaurantes.');
$socialFacebook = SystemSetting::get('social_facebook', '');
$socialInstagram = SystemSetting::get('social_instagram', '');
$socialTwitter = SystemSetting::get('social_twitter', '');
$socialLinkedin = SystemSetting::get('social_linkedin', '');
$socialYoutube = SystemSetting::get('social_youtube', '');
@endphp

@extends('landing.layouts.app')

@section('title', 'Página no encontrada - ' . $companyName)

@section('content')
<section style="min-height: 70vh; display: flex; align-items: center; background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                <div class="mb-4">
                    <span style="font-size: 8rem; font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1;">404</span>
                </div>
                <h1 class="mb-3" style="color: #2d3748; font-size: 2.5rem; font-weight: 700;">Página no encontrada</h1>
                <p class="mb-4" style="color: #4a5568; font-size: 1.1rem; line-height: 1.6;">
                    Lo sentimos, la página que buscas no existe o ha sido movida.
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-4">
                        <i class="ri ri-home-line me-2"></i>Volver al inicio
                    </a>
                    <a href="{{ route('landing.contact') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="ri ri-mail-line me-2"></i>Contactar soporte
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div style="position: relative;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 300px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-radius: 50%; filter: blur(60px);"></div>
                    <i class="ri ri-error-warning-line" style="font-size: 15rem; color: #667eea; position: relative; opacity: 0.9;"></i>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-5">
            <div class="col-12">
                <div class="text-center mb-4">
                    <h5 style="color: #2d3748; font-weight: 600;">¿Necesitas ayuda? Prueba estas opciones:</h5>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('landing.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="transition: all 0.2s;">
                        <div class="card-body text-center p-4">
                            <i class="ri ri-home-line mb-3" style="font-size: 2.5rem; color: #667eea;"></i>
                            <h6 style="color: #2d3748; font-weight: 600;">Página principal</h6>
                            <p class="mb-0 small" style="color: #718096;">Volver a la página de inicio</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('landing.tutorials') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="transition: all 0.2s;">
                        <div class="card-body text-center p-4">
                            <i class="ri ri-video-line mb-3" style="font-size: 2.5rem; color: #667eea;"></i>
                            <h6 style="color: #2d3748; font-weight: 600;">Tutoriales</h6>
                            <p class="mb-0 small" style="color: #718096;">Aprende a usar el sistema</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('landing.contact') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="transition: all 0.2s;">
                        <div class="card-body text-center p-4">
                            <i class="ri ri-customer-service-line mb-3" style="font-size: 2.5rem; color: #667eea;"></i>
                            <h6 style="color: #2d3748; font-weight: 600;">Soporte</h6>
                            <p class="mb-0 small" style="color: #718096;">Contacta con nuestro equipo</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.15) !important;
    }
</style>
@endsection
