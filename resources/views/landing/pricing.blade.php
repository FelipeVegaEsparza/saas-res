@extends('landing.layouts.app')

@section('title', 'Precios - RestaurantSaaS')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-4">Planes y Precios</h1>
            <p class="lead mb-0">Elige el plan que mejor se adapte a las necesidades de tu restaurante</p>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="py-5">
    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            @forelse($plans as $plan)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-lg {{ $loop->index === 1 ? 'border-primary' : '' }}"
                         style="{{ $loop->index === 1 ? 'transform: scale(1.05);' : '' }}">
                        @if($loop->index === 1)
                            <div class="position-absolute top-0 start-50 translate-middle">
                                <span class="badge bg-primary">Más Popular</span>
                            </div>
                        @endif

                        <div class="card-header bg-transparent border-0 pt-5 pb-0">
                            <div class="text-center">
                                <h3 class="fw-bold mb-2">{{ $plan->name }}</h3>
                                @if($plan->description)
                                    <p class="text-muted">{{ $plan->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="card-body text-center">
                            <div class="mb-4">
                                <h1 class="display-3 fw-bold mb-0">
                                    ${{ number_format($plan->price, 0) }}
                                </h1>
                                <span class="text-muted">/{{ $plan->billing_cycle === 'monthly' ? 'mes' : 'año' }}</span>
                            </div>

                            @if($plan->features && count($plan->features) > 0)
                                <ul class="list-unstyled text-start mb-4">
                                    @foreach($plan->features as $feature)
                                        <li class="mb-3">
                                            <i class="ri ri-check-line text-success me-2"></i>{{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="border-top pt-4 mb-4">
                                <div class="row text-center">
                                    @if($plan->max_users)
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="ri ri-user-line ri-24px text-primary"></i>
                                            </div>
                                            <strong class="d-block">{{ $plan->max_users }}</strong>
                                            <small class="text-muted">Usuarios</small>
                                        </div>
                                    @endif
                                    @if($plan->max_tables)
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="ri ri-table-line ri-24px text-primary"></i>
                                            </div>
                                            <strong class="d-block">{{ $plan->max_tables }}</strong>
                                            <small class="text-muted">Mesas</small>
                                        </div>
                                    @endif
                                    @if($plan->max_products)
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="ri ri-restaurant-line ri-24px text-primary"></i>
                                            </div>
                                            <strong class="d-block">{{ $plan->max_products }}</strong>
                                            <small class="text-muted">Productos</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('landing.contact') }}"
                               class="btn btn-{{ $loop->index === 1 ? 'primary' : 'outline-primary' }} w-100 btn-lg">
                                Comenzar Ahora
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ri ri-price-tag-3-line ri-48px text-muted mb-3"></i>
                            <h5 class="mb-2">Planes en Construcción</h5>
                            <p class="text-muted mb-4">Estamos preparando nuestros planes. Contáctanos para más información.</p>
                            <a href="{{ route('landing.contact') }}" class="btn btn-primary">
                                Contactar
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- FAQ Section -->
        <div class="row mt-5 pt-5">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold mb-3">Preguntas Frecuentes</h2>
                <p class="text-muted">Resolvemos tus dudas sobre nuestros planes</p>
            </div>

            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿Puedo cambiar de plan en cualquier momento?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sí, puedes cambiar de plan en cualquier momento. Los cambios se aplicarán en tu próximo ciclo de facturación.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿Hay período de prueba?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sí, ofrecemos 14 días de prueba gratuita en todos nuestros planes. No se requiere tarjeta de crédito.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                ¿Qué métodos de pago aceptan?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Aceptamos tarjetas de crédito, débito y transferencias bancarias. También trabajamos con MercadoPago y otros procesadores de pago locales.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                ¿Ofrecen soporte técnico?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sí, todos nuestros planes incluyen soporte técnico por email. Los planes superiores incluyen soporte prioritario y por teléfono.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                ¿Puedo cancelar mi suscripción?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sí, puedes cancelar tu suscripción en cualquier momento. No hay penalizaciones ni cargos ocultos.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-light py-5">
    <div class="container py-5">
        <div class="text-center">
            <h2 class="fw-bold mb-3">¿Tienes dudas?</h2>
            <p class="text-muted mb-4">Contáctanos y te ayudaremos a elegir el mejor plan para tu restaurante</p>
            <a href="{{ route('landing.contact') }}" class="btn btn-primary btn-lg">
                <i class="ri ri-mail-line me-2"></i>Contactar
            </a>
        </div>
    </div>
</section>
@endsection
