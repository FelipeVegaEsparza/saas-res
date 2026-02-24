<?php
use Illuminate\Support\Facades\Storage;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
$isFront = false;
$configData['hasCustomizer'] = true;
?>



<?php $__env->startSection('layoutContent'); ?>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo" style="padding: 2rem 1.5rem; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="app-brand-link" style="width: 100%; display: flex; justify-content: center; flex-direction: column; align-items: center;">
                    <span class="app-brand-text demo menu-text fw-bold" style="font-size: 1.5rem;">Admin Panel</span>
                    <small class="text-muted">Gestión del SaaS</small>
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
                <li class="menu-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-dashboard-line"></i>
                        <div>Dashboard</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Gestión</span>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.restaurants.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.restaurants.index')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-restaurant-line"></i>
                        <div>Restaurantes</div>
                    </a>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.subscriptions.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-file-list-line"></i>
                        <div>Suscripciones</div>
                    </a>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.plans.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.plans.index')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-price-tag-3-line"></i>
                        <div>Planes</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Contenido</span>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.tutorial-categories.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.tutorial-categories.index')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-folder-line"></i>
                        <div>Categorías</div>
                    </a>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.tutorials.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.tutorials.index')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-video-line"></i>
                        <div>Tutoriales</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Configuración</span>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="menu-link">
                        <i class="menu-icon icon-base ri ri-settings-3-line"></i>
                        <div>Configuración</div>
                    </a>
                </li>

                <li class="menu-header small mt-5">
                    <span class="menu-header-text">Sistema</span>
                </li>

                <li class="menu-item">
                    <a href="<?php echo e(route('landing.index')); ?>" class="menu-link" target="_blank">
                        <i class="menu-icon icon-base ri ri-external-link-line"></i>
                        <div>Ver Sitio Público</div>
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
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><?php echo e(substr(auth()->guard('admin')->user()->name, 0, 1)); ?></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <span class="avatar-initial rounded-circle bg-label-primary"><?php echo e(substr(auth()->guard('admin')->user()->name, 0, 1)); ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?php echo e(auth()->guard('admin')->user()->name); ?></h6>
                                                    <small class="text-muted"><?php echo e(auth()->guard('admin')->user()->email); ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                                            <?php echo csrf_field(); ?>
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
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('info')): ?>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <?php echo session('info'); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>
                <!-- / Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            <div class="text-body">
                                © <?php echo e(date('Y')); ?>, Admin Panel
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php echo $__env->yieldPushContent('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/commonMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/layouts/admin.blade.php ENDPATH**/ ?>