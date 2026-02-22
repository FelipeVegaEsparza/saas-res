<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', $companyName . ' - Sistema de Gestión para Restaurantes')</title>
    <meta name="description" content="{{ $companyDescription }}" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/assets/vendor/fonts/iconify/iconify.css'])
    @vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css'])

    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0 60px 0;
        }

        .navbar-landing {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>

    @yield('page-style')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-landing fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('landing.index') }}">
                @if($companyLogo)
                    <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height: 40px;">
                @else
                    <span style="font-size: 1.5rem; color: #667eea;">{{ $companyName }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.index') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.features') }}">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.pricing') }}">Precios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.contact') }}">Contacto</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.login') }}">
                            <i class="ri ri-login-box-line me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div style="padding-top: 70px;">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    @if($companyLogo)
                        <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height: 50px;" class="mb-3">
                    @else
                        <h5 class="fw-bold mb-3">{{ $companyName }}</h5>
                    @endif
                    <p class="text-white-50">{{ $companyDescription }}</p>

                    @if($socialFacebook || $socialInstagram || $socialTwitter || $socialLinkedin || $socialYoutube)
                        <div class="mt-3">
                            @if($socialFacebook)
                                <a href="{{ $socialFacebook }}" target="_blank" class="text-white-50 me-3" style="font-size: 1.5rem;">
                                    <i class="ri ri-facebook-fill"></i>
                                </a>
                            @endif
                            @if($socialInstagram)
                                <a href="{{ $socialInstagram }}" target="_blank" class="text-white-50 me-3" style="font-size: 1.5rem;">
                                    <i class="ri ri-instagram-line"></i>
                                </a>
                            @endif
                            @if($socialTwitter)
                                <a href="{{ $socialTwitter }}" target="_blank" class="text-white-50 me-3" style="font-size: 1.5rem;">
                                    <i class="ri ri-twitter-fill"></i>
                                </a>
                            @endif
                            @if($socialLinkedin)
                                <a href="{{ $socialLinkedin }}" target="_blank" class="text-white-50 me-3" style="font-size: 1.5rem;">
                                    <i class="ri ri-linkedin-fill"></i>
                                </a>
                            @endif
                            @if($socialYoutube)
                                <a href="{{ $socialYoutube }}" target="_blank" class="text-white-50" style="font-size: 1.5rem;">
                                    <i class="ri ri-youtube-fill"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">Enlaces</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('landing.features') }}" class="text-white-50 text-decoration-none">Características</a></li>
                        <li><a href="{{ route('landing.pricing') }}" class="text-white-50 text-decoration-none">Precios</a></li>
                        <li><a href="{{ route('landing.contact') }}" class="text-white-50 text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <p class="text-white-50">
                        <i class="ri ri-mail-line me-2"></i>{{ $companyEmail }}<br>
                        <i class="ri ri-phone-line me-2"></i>{{ $companyPhone }}
                        @if($companyAddress)
                            <br><i class="ri ri-map-pin-line me-2"></i>{{ $companyAddress }}
                        @endif
                    </p>
                </div>
            </div>
            <hr class="border-white-50 my-4">
            <div class="text-center text-white-50">
                <p class="mb-0">© {{ date('Y') }} {{ $companyName }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    @vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js'])
    @vite(['resources/assets/js/main.js'])

    @yield('page-script')
</body>
</html>
