@extends('landing.layouts.app')

@section('title', $companyName . ' - Sistema de Gestión para Restaurantes')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="mb-4">Aumenta tus ventas y reduce costos operativos hasta un 40%</h1>
                <p class="mb-4" style="font-size: 1.15rem; opacity: 0.95;">Sistema profesional de gestión para restaurantes. Control total de mesas, pedidos, inventario y finanzas en una sola plataforma. Implementación en 24 horas.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                    <a href="{{ route('landing.pricing') }}" class="btn btn-primary btn-lg px-5">
                        Ver Planes y Precios
                    </a>
                    <a href="{{ route('landing.contact') }}" class="btn btn-outline-light btn-lg px-5">
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
                        <small>14 días gratis</small>
                    </div>
                    <div>
                        <i class="ri ri-check-line me-1"></i>
                        <small>Soporte incluido</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg" style="background: rgba(255,255,255,0.98);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #2d3748;">Beneficios Inmediatos</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="ri ri-check-line me-2 mt-1" style="color: #ff6b35; font-size: 1.3rem;"></i>
                                <span style="color: #4a5568;"><strong style="color: #2d3748;">Reduce errores</strong> en pedidos hasta 95%</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="ri ri-check-line me-2 mt-1" style="color: #ff6b35; font-size: 1.3rem;"></i>
                                <span style="color: #4a5568;"><strong style="color: #2d3748;">Aumenta rotación</strong> de mesas en 30%</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="ri ri-check-line me-2 mt-1" style="color: #ff6b35; font-size: 1.3rem;"></i>
                                <span style="color: #4a5568;"><strong style="color: #2d3748;">Control total</strong> de inventario y mermas</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="ri ri-check-line me-2 mt-1" style="color: #ff6b35; font-size: 1.3rem;"></i>
                                <span style="color: #4a5568;"><strong style="color: #2d3748;">Reportes en tiempo real</strong> desde cualquier lugar</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <i class="ri ri-check-line me-2 mt-1" style="color: #ff6b35; font-size: 1.3rem;"></i>
                                <span style="color: #4a5568;"><strong style="color: #2d3748;">Pedidos online</strong> integrados sin comisiones</span>
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
            <h2 class="fw-bold mb-3">Todo lo que necesitas para gestionar tu restaurante</h2>
            <p class="text-muted">Funcionalidades probadas en cientos de restaurantes</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri ri-table-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-2">Sistema POS Completo</h5>
                                <p class="text-muted mb-0">Control de mesas, pedidos y pagos en tiempo real. Interfaz rápida diseñada para meseros.</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">• Vista de mesas en tiempo real</li>
                            <li class="mb-2">• Asignación de meseros</li>
                            <li class="mb-2">• División de cuentas</li>
                            <li>• Múltiples métodos de pago</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri ri-smartphone-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-2">Pedidos Online</h5>
                                <p class="text-muted mb-0">Tu propio sistema de pedidos online sin comisiones de terceros. Aumenta ventas 24/7.</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">• Catálogo online personalizado</li>
                            <li class="mb-2">• Pedidos para delivery y retiro</li>
                            <li class="mb-2">• Sin comisiones por pedido</li>
                            <li>• Integración con tu sistema</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri ri-bar-chart-box-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-2">Reportes y Análisis</h5>
                                <p class="text-muted mb-0">Toma decisiones basadas en datos reales. Reportes de ventas, productos y rendimiento.</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">• Ventas por período</li>
                            <li class="mb-2">• Productos más vendidos</li>
                            <li class="mb-2">• Rendimiento por mesero</li>
                            <li>• Análisis de rentabilidad</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri ri-archive-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-2">Control de Inventario</h5>
                                <p class="text-muted mb-0">Gestión de stock, alertas de productos bajos y control de mermas.</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">• Stock en tiempo real</li>
                            <li class="mb-2">• Alertas de stock bajo</li>
                            <li class="mb-2">• Control de mermas</li>
                            <li>• Historial de movimientos</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ri ri-qr-code-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-2">Menú Digital QR</h5>
                                <p class="text-muted mb-0">Menú digital sin contacto. Tus clientes escanean y ven el menú en su celular.</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">• Códigos QR por mesa</li>
                            <li class="mb-2">• Actualización instantánea</li>
                            <li class="mb-2">• Fotos de productos</li>
                            <li>• Categorías organizadas</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="ri ri-cash-line ri-24px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-2">Control de Caja</h5>
                                <p class="text-muted mb-0">Apertura, cierre y cuadre de caja. Control total de efectivo y transacciones.</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">• Sesiones de caja</li>
                            <li class="mb-2">• Múltiples formas de pago</li>
                            <li class="mb-2">• Cuadre automático</li>
                            <li>• Historial completo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Proceso Simple -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Comienza en 3 pasos simples</h2>
            <p class="text-muted">Implementación rápida sin complicaciones técnicas</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="avatar avatar-xl mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="avatar-initial rounded-circle bg-primary text-white" style="font-size: 2rem;">1</span>
                    </div>
                    <h5 class="mb-3">Elige tu Plan</h5>
                    <p class="text-muted">Selecciona el plan que mejor se adapte al tamaño de tu restaurante. Todos incluyen 14 días de prueba gratis.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-center">
                    <div class="avatar avatar-xl mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="avatar-initial rounded-circle bg-primary text-white" style="font-size: 2rem;">2</span>
                    </div>
                    <h5 class="mb-3">Configuración Inicial</h5>
                    <p class="text-muted">Nuestro equipo configura tu sistema en 24 horas: mesas, productos, usuarios y todo listo para operar.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-center">
                    <div class="avatar avatar-xl mx-auto mb-3" style="width: 80px; height: 80px;">
                        <span class="avatar-initial rounded-circle bg-primary text-white" style="font-size: 2rem;">3</span>
                    </div>
                    <h5 class="mb-3">Comienza a Operar</h5>
                    <p class="text-muted">Capacitamos a tu equipo y comienzas a usar el sistema. Soporte continuo incluido en todos los planes.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('landing.pricing') }}" class="btn btn-primary btn-lg px-5">
                Ver Planes y Comenzar
            </a>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="py-5">
    <div class="container py-5">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-5 text-white text-center">
                <h2 class="fw-bold mb-3">¿Listo para aumentar tus ventas?</h2>
                <p class="lead mb-4">Únete a cientos de restaurantes que ya optimizaron su operación</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('landing.pricing') }}" class="btn btn-light btn-lg px-4">
                        Ver Planes y Precios
                    </a>
                    <a href="{{ route('landing.contact') }}" class="btn btn-outline-light btn-lg px-4">
                        Solicitar Demo Gratuita
                    </a>
                </div>
                <div class="mt-4">
                    <small>14 días de prueba gratis • Sin tarjeta de crédito • Cancela cuando quieras</small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
