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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    @vite(['resources/assets/vendor/fonts/iconify/iconify.css'])
    @vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css'])

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
            padding: 120px 0 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-section p {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .navbar-landing {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 1rem 0;
        }

        .navbar-landing .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .navbar-landing .nav-link {
            font-weight: 500;
            color: #4a5568;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
        }

        .navbar-landing .nav-link:hover {
            color: #ff6b35;
        }

        .btn-primary {
            background: #ff6b35;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #e55a2b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }

        .btn-outline-light {
            border: 2px solid white;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-outline-light:hover {
            background: white;
            color: #2d3748;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #718096;
            margin-bottom: 3rem;
        }

        .feature-card {
            padding: 2rem;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c5a 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin-bottom: 1.5rem;
        }

        .feature-card h4 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #718096;
            line-height: 1.7;
        }

        .stats-section {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c5a 100%);
            color: white;
            padding: 4rem 0;
        }

        .stat-item h3 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        footer {
            background: #1a202c;
        }

        footer h6 {
            font-weight: 600;
            font-size: 1.1rem;
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
