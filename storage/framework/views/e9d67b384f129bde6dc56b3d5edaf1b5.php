<?php $__env->startSection('title', 'Planes - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Planes</h1>
            <p class="text-muted">Gestiona los planes de suscripción</p>
        </div>
        <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nuevo Plan
        </a>
    </div>
</div>

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 <?php echo e(!$plan->active ? 'border-secondary' : ''); ?>">
                <div class="card-header <?php echo e(!$plan->active ? 'bg-light' : ''); ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo e($plan->name); ?></h5>
                        <?php if($plan->active): ?>
                            <span class="badge bg-label-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-label-secondary">Inactivo</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($plan->description): ?>
                        <p class="text-muted mb-3"><?php echo e($plan->description); ?></p>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h2 class="mb-0">
                            $<?php echo e(number_format($plan->price, 0)); ?>

                            <small class="text-muted fs-6">/<?php echo e($plan->billing_cycle === 'monthly' ? 'mes' : 'año'); ?></small>
                        </h2>
                    </div>

                    <?php if($plan->features && count($plan->features) > 0): ?>
                        <div class="mb-4">
                            <h6 class="mb-3">Características:</h6>
                            <ul class="list-unstyled">
                                <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="mb-2">
                                        <i class="ri ri-check-line text-success me-2"></i><?php echo e($feature); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="border-top pt-3 mb-3">
                        <div class="row text-center">
                            <?php if($plan->max_users): ?>
                                <div class="col-4">
                                    <small class="text-muted d-block">Usuarios</small>
                                    <strong><?php echo e($plan->max_users); ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if($plan->max_tables): ?>
                                <div class="col-4">
                                    <small class="text-muted d-block">Mesas</small>
                                    <strong><?php echo e($plan->max_tables); ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if($plan->max_products): ?>
                                <div class="col-4">
                                    <small class="text-muted d-block">Productos</small>
                                    <strong><?php echo e($plan->max_products); ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Suscripciones activas:</small>
                        <strong class="ms-2"><?php echo e($plan->subscriptions_count); ?></strong>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.plans.edit', $plan->id)); ?>" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="ri ri-edit-line me-1"></i>Editar
                        </a>
                        <?php if($plan->subscriptions_count == 0): ?>
                            <form action="<?php echo e(route('admin.plans.destroy', $plan->id)); ?>"
                                  method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este plan?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri ri-price-tag-3-line ri-48px text-muted mb-3"></i>
                    <h5 class="mb-2">No hay planes creados</h5>
                    <p class="text-muted mb-4">Crea tu primer plan de suscripción</p>
                    <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
                        <i class="ri ri-add-line me-1"></i>Crear Primer Plan
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/plans/index.blade.php ENDPATH**/ ?>