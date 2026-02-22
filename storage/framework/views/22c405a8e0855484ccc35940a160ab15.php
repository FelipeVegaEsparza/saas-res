<?php $__env->startSection('title', 'Suscripciones - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="mb-1">Suscripciones</h1>
    <p class="text-muted">Gestiona todas las suscripciones del sistema</p>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.subscriptions.index')); ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Activas</option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Canceladas</option>
                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expiradas</option>
                        <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspendidas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="plan_id" class="form-select">
                        <option value="">Todos los planes</option>
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>" <?php echo e(request('plan_id') == $plan->id ? 'selected' : ''); ?>>
                                <?php echo e($plan->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-search-line me-1"></i>Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="ri ri-refresh-line me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri ri-check-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Activas</p>
                        <h4 class="mb-0"><?php echo e($subscriptions->where('status', 'active')->count()); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri ri-time-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Expiradas</p>
                        <h4 class="mb-0"><?php echo e($subscriptions->where('status', 'expired')->count()); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri ri-close-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Canceladas</p>
                        <h4 class="mb-0"><?php echo e($subscriptions->where('status', 'cancelled')->count()); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri ri-money-dollar-circle-line ri-24px"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0 text-muted small">Ingresos Mes</p>
                        <h4 class="mb-0">$<?php echo e(number_format($subscriptions->where('status', 'active')->sum('amount'), 0)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Suscripciones -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Restaurante</th>
                        <th>Plan</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Inicio</th>
                        <th>Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo e($subscription->restaurant->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($subscription->restaurant->email); ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-primary"><?php echo e($subscription->plan->name); ?></span>
                            </td>
                            <td><strong>$<?php echo e(number_format($subscription->amount, 0)); ?></strong></td>
                            <td>
                                <?php
                                    $statusColors = [
                                        'active' => 'success',
                                        'cancelled' => 'danger',
                                        'expired' => 'warning',
                                        'suspended' => 'secondary'
                                    ];
                                    $color = $statusColors[$subscription->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-label-<?php echo e($color); ?>"><?php echo e(ucfirst($subscription->status)); ?></span>
                            </td>
                            <td><?php echo e($subscription->starts_at->format('d/m/Y')); ?></td>
                            <td>
                                <?php echo e($subscription->ends_at->format('d/m/Y')); ?>

                                <?php if($subscription->status === 'active' && $subscription->ends_at->diffInDays(now()) <= 7): ?>
                                    <br><small class="text-warning"><i class="ri ri-alert-line"></i> Próximo a vencer</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo e(route('admin.subscriptions.show', $subscription->id)); ?>"
                                       class="btn btn-sm btn-icon btn-text-secondary" title="Ver">
                                        <i class="ri ri-eye-line"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.subscriptions.edit', $subscription->id)); ?>"
                                       class="btn btn-sm btn-icon btn-text-secondary" title="Editar">
                                        <i class="ri ri-edit-line"></i>
                                    </a>
                                    <?php if($subscription->status === 'active'): ?>
                                        <form action="<?php echo e(route('admin.subscriptions.cancel', $subscription->id)); ?>"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de cancelar esta suscripción?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-icon btn-text-danger" title="Cancelar">
                                                <i class="ri ri-close-circle-line"></i>
                                            </button>
                                        </form>
                                    <?php elseif(in_array($subscription->status, ['expired', 'cancelled'])): ?>
                                        <form action="<?php echo e(route('admin.subscriptions.renew', $subscription->id)); ?>"
                                              method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-icon btn-text-success" title="Renovar">
                                                <i class="ri ri-refresh-line"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No se encontraron suscripciones</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($subscriptions->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/subscriptions/index.blade.php ENDPATH**/ ?>