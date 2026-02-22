@extends('landing.layouts.app')

@section('title', 'Contacto - ' . $companyName)

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-4">Contáctanos</h1>
            <p class="lead mb-0">Estamos aquí para ayudarte. Envíanos un mensaje y te responderemos pronto</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container py-5">
        <div class="row">
            <!-- Formulario de Contacto -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="fw-bold mb-4">Envíanos un Mensaje</h3>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri ri-check-line me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('landing.contact.submit') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" name="name"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Tu nombre"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="tu@email.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" name="phone"
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="{{ $companyPhone }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Mensaje *</label>
                                <textarea name="message"
                                          class="form-control form-control-lg @error('message') is-invalid @enderror"
                                          rows="6"
                                          placeholder="Cuéntanos cómo podemos ayudarte..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="ri ri-send-plane-line me-2"></i>Enviar Mensaje
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Información de Contacto</h5>

                        <div class="d-flex mb-4">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri ri-mail-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">{{ $companyEmail }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri ri-phone-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1">Teléfono</h6>
                                <p class="text-muted mb-0">{{ $companyPhone }}</p>
                            </div>
                        </div>

                        @if($companyAddress)
                        <div class="d-flex mb-4">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri ri-map-pin-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1">Dirección</h6>
                                <p class="text-muted mb-0">{{ $companyAddress }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri ri-time-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1">Horario</h6>
                                <p class="text-muted mb-0">
                                    Lunes a Viernes<br>
                                    9:00 AM - 6:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($socialFacebook || $socialInstagram || $socialTwitter || $socialLinkedin || $socialYoutube)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Síguenos</h5>
                        <div class="d-flex gap-3">
                            @if($socialFacebook)
                                <a href="{{ $socialFacebook }}" target="_blank" class="btn btn-icon btn-label-primary">
                                    <i class="ri ri-facebook-fill ri-24px"></i>
                                </a>
                            @endif
                            @if($socialInstagram)
                                <a href="{{ $socialInstagram }}" target="_blank" class="btn btn-icon btn-label-danger">
                                    <i class="ri ri-instagram-line ri-24px"></i>
                                </a>
                            @endif
                            @if($socialTwitter)
                                <a href="{{ $socialTwitter }}" target="_blank" class="btn btn-icon btn-label-info">
                                    <i class="ri ri-twitter-fill ri-24px"></i>
                                </a>
                            @endif
                            @if($socialLinkedin)
                                <a href="{{ $socialLinkedin }}" target="_blank" class="btn btn-icon btn-label-primary">
                                    <i class="ri ri-linkedin-fill ri-24px"></i>
                                </a>
                            @endif
                            @if($socialYoutube)
                                <a href="{{ $socialYoutube }}" target="_blank" class="btn btn-icon btn-label-danger">
                                    <i class="ri ri-youtube-fill ri-24px"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Preguntas Frecuentes -->
        <div class="row mt-5 pt-5">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold mb-3">Preguntas Frecuentes</h2>
                <p class="text-muted">Quizás encuentres la respuesta a tu pregunta aquí</p>
            </div>

            <div class="col-lg-10 mx-auto">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="avatar avatar-lg mb-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ri ri-question-line ri-24px"></i>
                                    </span>
                                </div>
                                <h5 class="mb-3">¿Cómo empiezo?</h5>
                                <p class="text-muted mb-0">Contáctanos y te guiaremos en el proceso de registro. Puedes comenzar con una prueba gratuita de 14 días.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="avatar avatar-lg mb-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ri ri-shield-check-line ri-24px"></i>
                                    </span>
                                </div>
                                <h5 class="mb-3">¿Es seguro?</h5>
                                <p class="text-muted mb-0">Sí, utilizamos encriptación SSL y cumplimos con todos los estándares de seguridad para proteger tus datos.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="avatar avatar-lg mb-3">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="ri ri-customer-service-line ri-24px"></i>
                                    </span>
                                </div>
                                <h5 class="mb-3">¿Ofrecen capacitación?</h5>
                                <p class="text-muted mb-0">Sí, incluimos capacitación inicial para tu equipo y material de apoyo para que aproveches al máximo el sistema.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="avatar avatar-lg mb-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ri ri-settings-3-line ri-24px"></i>
                                    </span>
                                </div>
                                <h5 class="mb-3">¿Puedo personalizar el sistema?</h5>
                                <p class="text-muted mb-0">Sí, puedes personalizar logo, colores, moneda y muchas otras opciones según las necesidades de tu restaurante.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
