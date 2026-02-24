<?php $__env->startSection('title', 'Suscripción - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Detalle de Suscripción</h1>
            <p class="text-muted"><?php echo e($subscription->restaurant->name); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.subscriptions.edit', $subscription->id)); ?>" class="btn btn-primary">
                <i class="ri ri-edit-line me-1"></i>Editar
            </a>
            <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline-secondary">
                <i class="ri ri-arrow-left-line me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información de la Suscripción -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Suscripción</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Plan</label>
                        <p class="mb-0"><strong><?php echo e($subscription->plan->name); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Estado</label>
                        <p class="mb-0">
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
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Monto</label>
                        <p class="mb-0"><strong>$<?php echo e(number_format($subscription->amount, 0)); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Ciclo de Facturación</label>
                        <p class="mb-0"><?php echo e(ucfirst($subscription->plan->billing_cycle)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Fecha de Inicio</label>
                        <p class="mb-0"><?php echo e($subscription->starts_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Fecha de Vencimiento</label>
                        <p class="mb-0">
                            <?php echo e($subscription->ends_at->format('d/m/Y H:i')); ?>

                            <?php if($subscription->status === 'active'): ?>
                                <br><small class="text-muted">(<?php echo e($subscription->ends_at->diffForHumans()); ?>)</small>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Creada</label>
                        <p class="mb-0"><?php echo e($subscription->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Última Actualización</label>
                        <p class="mb-0"><?php echo e($subscription->updated_at->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="mb-3">Acciones</h6>
                    <div class="d-flex gap-2">
                        <?php if($subscription->status === 'active'): ?>
                            <form action="<?php echo e(route('admin.subscriptions.cancel', $subscription->id)); ?>"
                                  method="POST"
                                  onsubmit="return confirm('¿Estás seguro de cancelar esta suscripción?')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger">
                                    <i class="ri ri-close-circle-line me-1"></i>Cancelar Suscripción
                                </button>
                            </form>
                        <?php elseif(in_array($subscription->status, ['expired', 'cancelled'])): ?>
                            <form action="<?php echo e(route('admin.subscriptions.renew', $subscription->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success">
                                    <i class="ri ri-refresh-line me-1"></i>Renovar Suscripción
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Características del Plan -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Características del Plan</h5>
            </div>
            <div class="card-body">
                <?php if($subscription->plan->features && count($subscription->plan->features) > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $subscription->plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <i class="ri ri-check-line text-success me-2"></i><?php echo e($feature); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">No hay características definidas para este plan</p>
                <?php endif; ?>

                <div class="row mt-4 pt-3 border-top">
                    <?php if($subscription->plan->max_users): ?>
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Usuarios máximos</small>
                            <p class="mb-0"><strong><?php echo e($subscription->plan->max_users); ?></strong></p>
                        </div>
                    <?php endif; ?>
                    <?php if($subscription->plan->max_tables): ?>
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Mesas máximas</small>
                            <p class="mb-0"><strong><?php echo e($subscription->plan->max_tables); ?></strong></p>
                        </div>
                    <?php endif; ?>
                    <?php if($subscription->plan->max_products): ?>
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Productos máximos</small>
                            <p class="mb-0"><strong><?php echo e($subscription->plan->max_products); ?></strong></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Restaurante -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Restaurante</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nombre</label>
                    <p class="mb-0"><strong><?php echo e($subscription->restaurant->name); ?></strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <p class="mb-0"><?php echo e($subscription->restaurant->email); ?></p>
                </div>
                <?php if($subscription->restaurant->phone): ?>
                <div class="mb-3">
                    <label class="text-muted small">Teléfono</label>
                    <p class="mb-0"><?php echo e($subscription->restaurant->phone); ?></p>
                </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="text-muted small">Estado</label>
                    <p class="mb-0">
                        <?php if($subscription->restaurant->active): ?>
                            <span class="badge bg-label-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-label-danger">Inactivo</span>
                        <?php endif; ?>
                    </p>
                </div>
                <a href="<?php echo e(route('admin.restaurants.show', $subscription->restaurant->id)); ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="ri ri-eye-line me-1"></i>Ver Restaurante
                </a>
            </div>
        </div>

        <?php if($subscription->status === 'active' && $subscription->ends_at->diffInDays(now()) <= 7): ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="ri ri-alert-line me-2"></i>
                    <strong>Atención:</strong> Esta suscripción vence en <?php echo e($subscription->ends_at->diffInDays(now())); ?> días.
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/subscriptions/show.blade.php ENDPATH**/ ?>