<?php $__env->startSection('title', 'Precios - RestaurantSaaS'); ?>

<?php $__env->startSection('content'); ?>
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
            <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-lg <?php echo e($loop->index === 1 ? 'border-primary' : ''); ?>"
                         style="<?php echo e($loop->index === 1 ? 'transform: scale(1.05);' : ''); ?>">
                        <?php if($loop->index === 1): ?>
                            <div class="position-absolute top-0 start-50 translate-middle">
                                <span class="badge bg-primary">Más Popular</span>
                            </div>
                        <?php endif; ?>

                        <div class="card-header bg-transparent border-0 pt-5 pb-0">
                            <div class="text-center">
                                <h3 class="fw-bold mb-2"><?php echo e($plan->name); ?></h3>
                                <?php if($plan->description): ?>
                                    <p class="text-muted"><?php echo e($plan->description); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-body text-center">
                            <div class="mb-4">
                                <h1 class="display-3 fw-bold mb-0">
                                    $<?php echo e(number_format($plan->price, 0)); ?>

                                </h1>
                                <span class="text-muted">/<?php echo e($plan->billing_cycle === 'monthly' ? 'mes' : 'año'); ?></span>
                            </div>

                            <?php if($plan->features && count($plan->features) > 0): ?>
                                <ul class="list-unstyled text-start mb-4">
                                    <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="mb-3">
                                            <i class="ri ri-check-line text-success me-2"></i><?php echo e($feature); ?>

                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>

                            <div class="border-top pt-4 mb-4">
                                <div class="row text-center">
                                    <?php if($plan->max_users): ?>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="ri ri-user-line ri-24px text-primary"></i>
                                            </div>
                                            <strong class="d-block"><?php echo e($plan->max_users); ?></strong>
                                            <small class="text-muted">Usuarios</small>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($plan->max_tables): ?>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="ri ri-table-line ri-24px text-primary"></i>
                                            </div>
                                            <strong class="d-block"><?php echo e($plan->max_tables); ?></strong>
                                            <small class="text-muted">Mesas</small>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($plan->max_products): ?>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <i class="ri ri-restaurant-line ri-24px text-primary"></i>
                                            </div>
                                            <strong class="d-block"><?php echo e($plan->max_products); ?></strong>
                                            <small class="text-muted">Productos</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <a href="<?php echo e(route('landing.contact')); ?>"
                               class="btn btn-<?php echo e($loop->index === 1 ? 'primary' : 'outline-primary'); ?> w-100 btn-lg">
                                Comenzar Ahora
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ri ri-price-tag-3-line ri-48px text-muted mb-3"></i>
                            <h5 class="mb-2">Planes en Construcción</h5>
                            <p class="text-muted mb-4">Estamos preparando nuestros planes. Contáctanos para más información.</p>
                            <a href="<?php echo e(route('landing.contact')); ?>" class="btn btn-primary">
                                Contactar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
            <a href="<?php echo e(route('landing.contact')); ?>" class="btn btn-primary btn-lg">
                <i class="ri ri-mail-line me-2"></i>Contactar
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('landing.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/landing/pricing.blade.php ENDPATH**/ ?>