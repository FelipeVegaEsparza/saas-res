<?php $__env->startSection('title', 'Dashboard - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="mb-1">Dashboard</h1>
    <p class="text-muted">Resumen general del SaaS</p>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri ri-restaurant-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Total Restaurantes</p>
                        <h4 class="mb-0"><?php echo e($stats['total_restaurants']); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri ri-check-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Activos</p>
                        <h4 class="mb-0"><?php echo e($stats['active_restaurants']); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri ri-money-dollar-circle-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Ingresos Totales</p>
                        <h4 class="mb-0">$<?php echo e(number_format($stats['total_revenue'], 0)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri ri-user-add-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Nuevos Este Mes</p>
                        <h4 class="mb-0"><?php echo e($stats['new_this_month']); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Restaurantes Recientes -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Restaurantes Recientes</h5>
                <a href="<?php echo e(route('admin.restaurants.index')); ?>" class="btn btn-sm btn-primary">Ver Todos</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Restaurante</th>
                                <th>Plan</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent_restaurants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $restaurant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?php echo e($restaurant->name); ?></strong><br>
                                            <small class="text-muted"><?php echo e($restaurant->email); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($restaurant->activeSubscription): ?>
                                            <span class="badge bg-label-primary"><?php echo e($restaurant->activeSubscription->plan->name); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-label-secondary">Sin plan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($restaurant->active): ?>
                                            <span class="badge bg-label-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-label-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($restaurant->created_at->format('d/m/Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.restaurants.show', $restaurant->id)); ?>" class="btn btn-sm btn-icon btn-text-secondary">
                                            <i class="ri ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay restaurantes registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Suscripciones por Vencer -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Próximas a Vencer</h5>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $expiring_soon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm me-2">
                            <span class="avatar-initial rounded-circle bg-label-warning">
                                <i class="ri ri-alert-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0"><?php echo e($subscription->restaurant->name); ?></h6>
                            <small class="text-muted">Vence: <?php echo e($subscription->ends_at->format('d/m/Y')); ?></small>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center py-3">No hay suscripciones por vencer</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>