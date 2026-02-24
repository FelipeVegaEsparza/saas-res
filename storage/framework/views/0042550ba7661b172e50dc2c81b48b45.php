<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', $companyName . ' - Sistema de Gestión para Restaurantes'); ?></title>
    <meta name="description" content="<?php echo e($companyDescription); ?>" />

    <?php if($companyFavicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . $companyFavicon)); ?>" />
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/img/favicon/favicon.ico')); ?>" />
    <?php endif; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/fonts/iconify/iconify.css']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css']); ?>

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%),
                        url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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

        .badge-trial {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .navbar-landing {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 1.2rem 0;
        }

        .navbar-landing .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .navbar-landing .nav-link {
            font-weight: 500;
            color: #4a5568;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            position: relative;
        }

        .navbar-landing .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }

        .navbar-landing .nav-link:hover {
            color: #667eea;
        }

        .navbar-landing .nav-link:hover::after {
            width: 60%;
        }

        .navbar-landing .nav-link i {
            font-size: 1.1rem;
        }

        .navbar-divider {
            width: 1px;
            height: 30px;
            background: linear-gradient(to bottom, transparent, #e2e8f0, transparent);
            margin: 0 1rem;
        }

        .badge-new {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-weight: 600;
            margin-left: 0.3rem;
            vertical-align: middle;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.65rem 1.8rem;
            font-weight: 600;
            border-radius: 8px;
            transition: opacity 0.2s;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.55rem 1.8rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
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
            color: #667eea;
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
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.15);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .trial-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        footer {
            background: #2d3748;
            color: #e2e8f0;
        }

        footer h6 {
            font-weight: 600;
            font-size: 1rem;
            color: white;
            margin-bottom: 1.2rem;
        }

        footer a {
            color: #cbd5e0;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        footer a:hover {
            color: #667eea;
        }

        footer .social-links a {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            transition: all 0.2s;
        }

        footer .social-links a:hover {
            background: #667eea;
            color: white;
        }

        footer .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
        }
    </style>

    <?php echo $__env->yieldContent('page-style'); ?>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-landing fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('landing.index')); ?>">
                <?php if($companyLogo): ?>
                    <img src="<?php echo e(asset('storage/' . $companyLogo)); ?>" alt="<?php echo e($companyName); ?>" style="max-height: 55px;">
                <?php else: ?>
                    <span style="font-size: 1.5rem; color: #667eea;"><?php echo e($companyName); ?></span>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('landing.index')); ?>">
                            <i class="ri ri-home-line me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('landing.features')); ?>">
                            <i class="ri ri-star-line me-1"></i>Funciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('landing.pricing')); ?>">
                            <i class="ri ri-price-tag-3-line me-1"></i>Precios
                            <span class="badge-new">10 días gratis</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('landing.contact')); ?>">
                            <i class="ri ri-mail-line me-1"></i>Contacto
                        </a>
                    </li>
                </ul>
                <div class="d-none d-lg-flex align-items-center">
                    <div class="navbar-divider"></div>
                </div>
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('landing.tutorials')); ?>">
                            <i class="ri ri-video-line me-1"></i>Tutoriales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.login')); ?>">
                            <i class="ri ri-login-box-line me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div style="padding-top: 70px;">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Footer -->
    <footer class="text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <?php if($companyLogo): ?>
                        <img src="<?php echo e(asset('storage/' . $companyLogo)); ?>" alt="<?php echo e($companyName); ?>" style="max-height: 60px;" class="mb-3">
                    <?php else: ?>
                        <h5 class="fw-bold mb-3" style="color: white;"><?php echo e($companyName); ?></h5>
                    <?php endif; ?>
                    <p style="color: #cbd5e0; line-height: 1.6;"><?php echo e($companyDescription); ?></p>

                    <?php if($socialFacebook || $socialInstagram || $socialTwitter || $socialLinkedin || $socialYoutube): ?>
                        <div class="social-links mt-4">
                            <?php if($socialFacebook): ?>
                                <a href="<?php echo e($socialFacebook); ?>" target="_blank" class="me-2">
                                    <i class="ri ri-facebook-fill"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($socialInstagram): ?>
                                <a href="<?php echo e($socialInstagram); ?>" target="_blank" class="me-2">
                                    <i class="ri ri-instagram-line"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($socialTwitter): ?>
                                <a href="<?php echo e($socialTwitter); ?>" target="_blank" class="me-2">
                                    <i class="ri ri-twitter-fill"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($socialLinkedin): ?>
                                <a href="<?php echo e($socialLinkedin); ?>" target="_blank" class="me-2">
                                    <i class="ri ri-linkedin-fill"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($socialYoutube): ?>
                                <a href="<?php echo e($socialYoutube); ?>" target="_blank">
                                    <i class="ri ri-youtube-fill"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Producto</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('landing.features')); ?>">Funciones</a></li>
                        <li><a href="<?php echo e(route('landing.pricing')); ?>">Precios</a></li>
                        <li><a href="<?php echo e(route('landing.tutorials')); ?>">Tutoriales</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('landing.contact')); ?>">Contacto</a></li>
                        <li><a href="<?php echo e(route('landing.tutorials')); ?>">Ayuda</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h6>Contacto</h6>
                    <ul class="list-unstyled" style="color: #cbd5e0;">
                        <li class="mb-2">
                            <i class="ri ri-mail-line me-2"></i><?php echo e($companyEmail); ?>

                        </li>
                        <li class="mb-2">
                            <i class="ri ri-phone-line me-2"></i><?php echo e($companyPhone); ?>

                        </li>
                        <?php if($companyAddress): ?>
                            <li class="mb-2">
                                <i class="ri ri-map-pin-line me-2"></i><?php echo e($companyAddress); ?>

                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center" style="color: #a0aec0;">
                <p class="mb-0">© <?php echo e(date('Y')); ?> <?php echo e($companyName); ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/js/main.js']); ?>

    <?php echo $__env->yieldContent('page-script'); ?>
</body>
</html>
<?php /**PATH F:\saasres\resources\views/landing/layouts/app.blade.php ENDPATH**/ ?>