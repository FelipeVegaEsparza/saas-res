@php
use Illuminate\Support\Facades\Storage;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
$isFront = false; // Asegurar que no se use el layout front
// Forzar que hasCustomizer esté en true para cargar los scripts del theme switcher
$configData['hasCustomizer'] = true;
@endphp

@section('page-script')
<script>
// Asegurar que templateName esté disponible globalmente para el theme switcher
if (typeof templateName === 'undefined') {
    window.templateName = document.documentElement.getAttribute('data-template');
    var templateName = window.templateName;
}

// Debug: Verificar que el theme switcher funcione
console.log('Template Name:', window.templateName);
console.log('Current Theme:', document.documentElement.getAttribute('data-bs-theme'));
console.log('Helpers available:', typeof window.Helpers !== 'undefined');
</script>
@endsection

@extends('layouts/commonMaster')

@section('layoutContent')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo" style="padding: 2rem 1.5rem; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                <a href="{{ route('tenant.path.dashboard', ['tenant' => request()->route('tenant')]) }}" class="app-brand-link" style="width: 100%; display: flex; justify-content: center;">
                    @php
                        $restaurant = tenant()->restaurant();
                    @endphp
                    @if($restaurant->logo_horizontal)
                        <img src="{{ Storage::url($restaurant->logo_horizontal) }}" alt="{{ $restaurant->name }}" style="max-height: 70px; max-width: 200px; object-fit: contain;">
                    @else
                        <span class="app-brand-text demo menu-text fw-semibold" style="font-size: 1.5rem;">{{ $restaurant->name }}</span>
                    @endif
                </a>
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large" style="position: absolute; right: 1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z" fill-opacity="0.9" />
                        <path d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z" fill-opacity="0.4" />
                    </svg>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <li class="menu-item {{ request()->routeIs('tenant.path.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.dashboard', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-home-smile-line"></i>
                        <div>Dashboard</div>
                    </a>
                </li>

                @php
                    $restaurant = tenant()->restaurant();
                    $showTables = $restaurant->module_tables_enabled ?? true;
                    $showDelivery = $restaurant->module_delivery_enabled ?? true;
                @endphp

                @if($showTables || $showDelivery)
                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Punto de Venta</span>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.cash.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.cash.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-cash-line"></i>
                        <div>Caja</div>
                    </a>
                </li>

                @if($showTables)
                <li class="menu-item {{ request()->routeIs('tenant.path.tables.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.tables.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-table-line"></i>
                        <div>Mesas</div>
                    </a>
                </li>
                @endif

                @if($showDelivery)
                <li class="menu-item {{ request()->routeIs('tenant.path.delivery.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-e-bike-2-line"></i>
                        <div>Delivery</div>
                        @php
                            $pendingDeliveryCount = \App\Models\Tenant\DeliveryOrder::where('status', 'pending')->count();
                        @endphp
                        @if($pendingDeliveryCount > 0)
                            <span class="badge rounded-pill bg-danger ms-auto">{{ $pendingDeliveryCount }}</span>
                        @endif
                    </a>
                </li>
                @endif
                @endif

                @php
                    $preparationAreas = \App\Models\Tenant\PreparationArea::active()->ordered()->get();
                @endphp
                @if($preparationAreas->isNotEmpty())
                    <li class="menu-header small mt-5">
                        <span class="menu-header-text">Estaciones</span>
                    </li>

                    @foreach($preparationAreas as $area)
                        <li class="menu-item {{ request()->routeIs('tenant.path.kds.index') && request()->route('area_id') == $area->id ? 'active' : '' }}">
                            <a href="{{ route('tenant.path.kds.index', ['tenant' => request()->route('tenant'), 'area_id' => $area->id]) }}" class="menu-link">
                                <i class="menu-icon icon-base ri {{ $area->icon }}" style="color: {{ $area->color }};"></i>
                                <div>{{ $area->name }}</div>
                            </a>
                        </li>
                    @endforeach
                @endif

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Gestión de Menú</span>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.products.*') || request()->routeIs('tenant.path.categories.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon icon-base ri ri-restaurant-line"></i>
                        <div>Productos</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('tenant.path.products.*') ? 'active' : '' }}">
                            <a href="{{ route('tenant.path.products.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                                <div>Todos los Productos</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('tenant.path.categories.*') ? 'active' : '' }}">
                            <a href="{{ route('tenant.path.categories.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                                <div>Categorías</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.stock.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.stock.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-archive-line"></i>
                        <div>Stock</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Reportes</span>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.statistics.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.statistics.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-bar-chart-box-line"></i>
                        <div>Estadísticas</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Configuración</span>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.preparation-areas.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.preparation-areas.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-restaurant-2-line"></i>
                        <div>Estaciones</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.users.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.users.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-user-line"></i>
                        <div>Usuarios</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('tenant.path.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('tenant.path.settings.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link">
                        <i class="menu-icon icon-base ri ri-settings-3-line"></i>
                        <div>Ajustes</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Acciones Rápidas</span>
                </li>

                <li class="menu-item">
                    <a href="{{ route('tenant.path.menu.index', ['tenant' => request()->route('tenant')]) }}" class="menu-link" target="_blank">
                        <i class="menu-icon icon-base ri ri-external-link-line"></i>
                        <div>Ver Menú Público</div>
                    </a>
                </li>
            </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout page -->
        <div class="layout-page">
            <!-- Navbar -->
            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="container-fluid">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base ri ri-menu-line icon-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">
                        <!-- Estadísticas del Turno (Centradas) -->
                        @if(isset($shiftStats) && $shiftStats['has_active_session'])
                        <div class="flex-grow-1 d-none d-lg-flex justify-content-center">
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-flex align-items-center">
                                    <i class="ri ri-money-dollar-circle-line ri-20px me-2 text-success"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Ventas</small>
                                        <strong style="font-size: 0.9rem;">${{ number_format($shiftStats['shift_revenue'], 0) }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ri ri-e-bike-2-line ri-20px me-2 text-info"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Delivery</small>
                                        <strong style="font-size: 0.9rem;">{{ $shiftStats['shift_delivery_count'] }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ri ri-restaurant-2-line ri-20px me-2 text-warning"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Mesas</small>
                                        <strong style="font-size: 0.9rem;">{{ $shiftStats['shift_tables_served'] }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ri ri-receipt-line ri-20px me-2 text-primary"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Ticket Prom.</small>
                                        <strong style="font-size: 0.9rem;">${{ number_format($shiftStats['shift_avg_ticket'], 0) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- / Estadísticas del Turno -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Theme Switcher -->
                            <li class="nav-item dropdown me-4">
                                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-sun-line icon-md theme-icon-active me-2" data-icon="sun-line"></i>
                                    <span class="align-middle">Color</span>
                                    <span class="d-none" id="nav-theme-text">Toggle theme</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                                            <span><i class="icon-base ri ri-sun-line icon-22px me-3" data-icon="sun-line"></i>Light</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                                            <span><i class="icon-base ri ri-moon-clear-line icon-22px me-3" data-icon="moon-clear-line"></i>Dark</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                                            <span><i class="icon-base ri ri-computer-line icon-22px me-3" data-icon="computer-line"></i>System</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Theme Switcher -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('tenant.path.logout', ['tenant' => request()->route('tenant')]) }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="ri-logout-box-r-line me-2 ri-22px"></i>
                                                <span class="align-middle">Cerrar Sesión</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- / Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            <div class="text-body">
                                © {{ date('Y') }}, {{ tenant()->restaurant()->name }}
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->
                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>

<!-- Sistema de Notificaciones de Pedidos Online -->
<script>
(function() {
    let isCheckingOrders = false;
    let notifiedOrders = new Set();

    // Función para verificar nuevos pedidos
    async function checkNewOrders() {
        if (isCheckingOrders) return;

        isCheckingOrders = true;

        try {
            const response = await fetch('{{ route("tenant.path.notifications.checkNewOrders", ["tenant" => request()->route("tenant")]) }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Error al verificar pedidos');
            }

            const data = await response.json();

            if (data.has_new_orders && data.orders && data.orders.length > 0) {
                // Filtrar solo los pedidos que no hemos notificado
                const newOrders = data.orders.filter(order => !notifiedOrders.has(order.id));

                if (newOrders.length > 0) {
                    // Marcar como notificados
                    newOrders.forEach(order => notifiedOrders.add(order.id));

                    // Mostrar notificación para cada pedido nuevo
                    newOrders.forEach(order => {
                        showOrderNotification(order);
                    });

                    // Reproducir sonido de notificación
                    playNotificationSound();
                }
            }
        } catch (error) {
            console.error('Error al verificar nuevos pedidos:', error);
        } finally {
            isCheckingOrders = false;
        }
    }

    // Función para mostrar notificación de pedido
    function showOrderNotification(order) {
        const typeLabel = order.type === 'delivery' ? 'Delivery' : 'Para Llevar';
        const typeIcon = order.type === 'delivery' ? 'ri-e-bike-2-line' : 'ri-shopping-bag-3-line';

        Swal.fire({
            title: '🔔 Nuevo Pedido Online',
            html: `
                <div class="text-start">
                    <div class="mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri ${typeIcon} ri-24px me-2 text-primary"></i>
                            <h5 class="mb-0">${order.order_number}</h5>
                        </div>
                        <span class="badge bg-label-info">${typeLabel}</span>
                    </div>

                    <div class="mb-2">
                        <strong><i class="ri ri-user-line me-1"></i>Cliente:</strong> ${order.customer_name}
                    </div>
                    <div class="mb-2">
                        <strong><i class="ri ri-phone-line me-1"></i>Teléfono:</strong> ${order.customer_phone}
                    </div>
                    <div class="mb-2">
                        <strong><i class="ri ri-shopping-cart-line me-1"></i>Items:</strong> ${order.items_count} productos
                    </div>
                    <div class="mb-2">
                        <strong><i class="ri ri-time-line me-1"></i>Hora:</strong> ${order.created_at}
                    </div>
                    <div class="mt-3 p-2 bg-success bg-opacity-10 rounded">
                        <strong class="text-success"><i class="ri ri-money-dollar-circle-line me-1"></i>Total: $${formatPrice(order.total)}</strong>
                    </div>
                </div>
            `,
            icon: 'info',
            iconColor: '#696cff',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-eye-line me-1"></i> Ver Pedido',
            cancelButtonText: 'Cerrar',
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false,
            allowOutsideClick: false,
            timer: 30000,
            timerProgressBar: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir a la vista del pedido
                window.location.href = '{{ route("tenant.path.delivery.index", ["tenant" => request()->route("tenant")]) }}';
            }
        });
    }

    // Función para formatear precio
    function formatPrice(amount) {
        return Math.round(amount).toLocaleString('es-CL');
    }

    // Función para reproducir sonido de notificación
    function playNotificationSound() {
        try {
            // Crear un beep simple usando Web Audio API
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch (error) {
            console.log('No se pudo reproducir el sonido de notificación');
        }
    }

    // Iniciar polling cada 30 segundos
    setInterval(checkNewOrders, 30000);

    // Verificar inmediatamente al cargar la página
    setTimeout(checkNewOrders, 2000);
})();
</script>

@endsection
