<?php $__env->startSection('title', $companyName . ' - Sistema de Gestión para Restaurantes'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section position-relative">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="badge-trial mb-3">
                    <i class="ri ri-gift-line me-2"></i>10 DÍAS DE PRUEBA GRATIS - Sin Tarjeta de Crédito
                </div>
                <h1 class="mb-4">Aumenta tus ventas y reduce costos operativos hasta un 40%</h1>
                <p class="mb-4" style="font-size: 1.15rem; opacity: 0.95;">Sistema profesional de gestión para restaurantes. Control total de mesas, pedidos, inventario y finanzas en una sola plataforma. Implementación en 24 horas.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                    <a href="<?php echo e(route('landing.pricing')); ?>" class="btn btn-light btn-lg px-5" style="background: white; color: #667eea; font-weight: 700;">
                        Comenzar Prueba Gratis
                    </a>
                    <a href="<?php echo e(route('landing.contact')); ?>" class="btn btn-outline-light btn-lg px-5">
                        Solicitar Demo
                    </a>
                </div>
                <div class="d-flex gap-4" style="opacity: 0.85;">
                    <div>
                        <i class="ri ri-check-line me-1"></i>
                        <small>Sin permanencia</small>
                    </div>
                    <div>
                        <i class="ri ri-check-line me-1"></i>
                        <small>10 días gratis</small>
                    </div>
                    <div>
                        <i class="ri ri-check-line me-1"></i>
                        <small>Soporte incluido</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg" style="background: white; border-radius: 16px;">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 4px; height: 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2px; margin-right: 1rem;"></div>
                            <h5 class="fw-bold mb-0" style="color: #2d3748; font-size: 1.3rem;">Beneficios Inmediatos</h5>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start">
                                <div style="min-width: 28px; height: 28px; background: rgba(102, 126, 234, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                    <i class="ri ri-check-line" style="color: #667eea; font-size: 1.1rem;"></i>
                                </div>
                                <span style="color: #4a5568; line-height: 1.6;"><strong style="color: #2d3748;">Reduce errores</strong> en pedidos hasta 95%</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <div style="min-width: 28px; height: 28px; background: rgba(102, 126, 234, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                    <i class="ri ri-check-line" style="color: #667eea; font-size: 1.1rem;"></i>
                                </div>
                                <span style="color: #4a5568; line-height: 1.6;"><strong style="color: #2d3748;">Aumenta rotación</strong> de mesas en 30%</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <div style="min-width: 28px; height: 28px; background: rgba(102, 126, 234, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                    <i class="ri ri-check-line" style="color: #667eea; font-size: 1.1rem;"></i>
                                </div>
                                <span style="color: #4a5568; line-height: 1.6;"><strong style="color: #2d3748;">Control total</strong> de inventario y mermas</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <div style="min-width: 28px; height: 28px; background: rgba(102, 126, 234, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                    <i class="ri ri-check-line" style="color: #667eea; font-size: 1.1rem;"></i>
                                </div>
                                <span style="color: #4a5568; line-height: 1.6;"><strong style="color: #2d3748;">Reportes en tiempo real</strong> desde cualquier lugar</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <div style="min-width: 28px; height: 28px; background: rgba(102, 126, 234, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                    <i class="ri ri-check-line" style="color: #667eea; font-size: 1.1rem;"></i>
                                </div>
                                <span style="color: #4a5568; line-height: 1.6;"><strong style="color: #2d3748;">Pedidos online</strong> integrados sin comisiones</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Problemas que resolvemos -->
<section class="py-5" style="background: #f7fafc;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">¿Te identificas con estos problemas?</h2>
            <p class="section-subtitle">Estos son los desafíos más comunes que enfrentan los restaurantes</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="ri ri-error-warning-line"></i>
                    </div>
                    <h4>Errores en Pedidos</h4>
                    <p>Pedidos mal tomados, productos equivocados, clientes insatisfechos y pérdida de dinero.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="ri ri-time-line"></i>
                    </div>
                    <h4>Servicio Lento</h4>
                    <p>Mesas esperando, clientes impacientes, pérdida de rotación y ventas.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="ri ri-question-line"></i>
                    </div>
                    <h4>Sin Control</h4>
                    <p>No sabes cuánto vendes realmente, qué productos funcionan o dónde pierdes dinero.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="ri ri-money-dollar-circle-line"></i>
                    </div>
                    <h4>Costos Altos</h4>
                    <p>Mermas, robos, desperdicio de inventario y gastos operativos fuera de control.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <p class="lead mb-0" style="color: #2d3748;"><strong>Nuestro sistema resuelve estos problemas desde el primer día.</strong></p>
        </div>
    </div>
</section>

<!-- Funcionalidades Clave -->
<section class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">Funcionalidades probadas en cientos de restaurantes</h2>
            <p class="section-subtitle">Todo lo que necesitas en una sola plataforma</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.5rem; margin-bottom: 0;">
                            <i class="ri ri-restaurant-line"></i>
                        </div>
                        <h4 class="ms-3 mb-0">Sistema POS Completo</h4>
                    </div>
                    <p class="mb-3">Control de mesas, pedidos y pagos en tiempo real. Interfaz rápida diseñada para meseros.</p>
                    <ul class="list-unstyled small mb-0" style="color: #718096;">
                        <li class="mb-2">- Vista de mesas en tiempo real</li>
                        <li class="mb-2">- Asignación de meseros</li>
                        <li class="mb-2">- División de cuentas</li>
                        <li>- Múltiples métodos de pago</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.5rem; margin-bottom: 0;">
                            <i class="ri ri-smartphone-line"></i>
                        </div>
                        <h4 class="ms-3 mb-0">Pedidos Online</h4>
                    </div>
                    <p class="mb-3">Tu propio sistema de pedidos online sin comisiones de terceros. Aumenta ventas 24/7.</p>
                    <ul class="list-unstyled small mb-0" style="color: #718096;">
                        <li class="mb-2">- Catálogo online personalizado</li>
                        <li class="mb-2">- Pedidos para delivery y retiro</li>
                        <li class="mb-2">- Sin comisiones por pedido</li>
                        <li>- Integración con tu sistema</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.5rem; margin-bottom: 0;">
                            <i class="ri ri-line-chart-line"></i>
                        </div>
                        <h4 class="ms-3 mb-0">Reportes y Análisis</h4>
                    </div>
                    <p class="mb-3">Toma decisiones basadas en datos reales. Reportes de ventas, productos y rendimiento.</p>
                    <ul class="list-unstyled small mb-0" style="color: #718096;">
                        <li class="mb-2">- Ventas por período</li>
                        <li class="mb-2">- Productos más vendidos</li>
                        <li class="mb-2">- Rendimiento por mesero</li>
                        <li>- Análisis de rentabilidad</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.5rem; margin-bottom: 0;">
                            <i class="ri ri-archive-line"></i>
                        </div>
                        <h4 class="ms-3 mb-0">Control de Inventario</h4>
                    </div>
                    <p class="mb-3">Gestión de stock, alertas de productos bajos y control de mermas.</p>
                    <ul class="list-unstyled small mb-0" style="color: #718096;">
                        <li class="mb-2">- Stock en tiempo real</li>
                        <li class="mb-2">- Alertas de stock bajo</li>
                        <li class="mb-2">- Control de mermas</li>
                        <li>- Historial de movimientos</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.5rem; margin-bottom: 0;">
                            <i class="ri ri-qr-code-line"></i>
                        </div>
                        <h4 class="ms-3 mb-0">Menú Digital QR</h4>
                    </div>
                    <p class="mb-3">Menú digital sin contacto. Tus clientes escanean y ven el menú en su celular.</p>
                    <ul class="list-unstyled small mb-0" style="color: #718096;">
                        <li class="mb-2">- Códigos QR por mesa</li>
                        <li class="mb-2">- Actualización instantánea</li>
                        <li class="mb-2">- Fotos de productos</li>
                        <li>- Categorías organizadas</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.5rem; margin-bottom: 0;">
                            <i class="ri ri-wallet-line"></i>
                        </div>
                        <h4 class="ms-3 mb-0">Control de Caja</h4>
                    </div>
                    <p class="mb-3">Apertura, cierre y cuadre de caja. Control total de efectivo y transacciones.</p>
                    <ul class="list-unstyled small mb-0" style="color: #718096;">
                        <li class="mb-2">- Sesiones de caja</li>
                        <li class="mb-2">- Múltiples formas de pago</li>
                        <li class="mb-2">- Cuadre automático</li>
                        <li>- Historial completo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Proceso Simple -->
<section class="py-5" style="background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('https://images.unsplash.com/photo-1565123409695-7b5ef63a2efb?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Comienza en 3 pasos simples</h2>
            <p class="text-muted">Implementación rápida sin complicaciones técnicas</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="avatar avatar-xl mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="avatar-initial rounded-circle text-white" style="font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">1</span>
                    </div>
                    <h5 class="mb-3">Elige tu Plan</h5>
                    <p class="text-muted">Selecciona el plan que mejor se adapte al tamaño de tu restaurante. <strong>Todos incluyen 10 días de prueba gratis sin tarjeta de crédito.</strong></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-center">
                    <div class="avatar avatar-xl mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="avatar-initial rounded-circle text-white" style="font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">2</span>
                    </div>
                    <h5 class="mb-3">Configuración Inicial</h5>
                    <p class="text-muted">Nuestro equipo configura tu sistema en 24 horas: mesas, productos, usuarios y todo listo para operar.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-center">
                    <div class="avatar avatar-xl mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="avatar-initial rounded-circle text-white" style="font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">3</span>
                    </div>
                    <h5 class="mb-3">Comienza a Operar</h5>
                    <p class="text-muted">Capacitamos a tu equipo y comienzas a usar el sistema. Soporte continuo incluido en todos los planes.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo e(route('landing.pricing')); ?>" class="btn btn-primary btn-lg px-5">
                Comenzar Prueba Gratis de 10 Días
            </a>
            <p class="text-muted mt-3 mb-0"><small>No se requiere tarjeta de crédito</small></p>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="py-5">
    <div class="container py-5">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-5 text-white text-center">
                <div class="trial-highlight mb-4" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3);">
                    <i class="ri ri-gift-line me-2"></i>PRUEBA GRATIS POR 10 DÍAS
                </div>
                <h2 class="fw-bold mb-3">¿Listo para aumentar tus ventas?</h2>
                <p class="lead mb-4">Únete a cientos de restaurantes que ya optimizaron su operación</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-4">
                    <a href="<?php echo e(route('landing.pricing')); ?>" class="btn btn-light btn-lg px-5" style="background: white; color: #667eea; font-weight: 700;">
                        Comenzar Prueba Gratis
                    </a>
                    <a href="<?php echo e(route('landing.contact')); ?>" class="btn btn-outline-light btn-lg px-5">
                        Solicitar Demo Gratuita
                    </a>
                </div>
                <div class="mt-3">
                    <small><strong>10 días de prueba gratis</strong> • Sin tarjeta de crédito • Cancela cuando quieras</small>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('landing.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/landing/index.blade.php ENDPATH**/ ?>