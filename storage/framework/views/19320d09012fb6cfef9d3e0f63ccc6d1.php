<?php $__env->startSection('title', 'Editar Suscripción - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Editar Suscripción</h1>
            <p class="text-muted"><?php echo e($subscription->restaurant->name); ?></p>
        </div>
        <a href="<?php echo e(route('admin.subscriptions.show', $subscription->id)); ?>" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('admin.subscriptions.update', $subscription->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label class="form-label">Plan *</label>
                        <select name="plan_id" class="form-select <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($plan->id); ?>"
                                        <?php echo e(old('plan_id', $subscription->plan_id) == $plan->id ? 'selected' : ''); ?>>
                                    <?php echo e($plan->name); ?> - $<?php echo e(number_format($plan->price, 0)); ?>/<?php echo e($plan->billing_cycle); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">El monto se actualizará automáticamente según el plan seleccionado</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="active" <?php echo e(old('status', $subscription->status) == 'active' ? 'selected' : ''); ?>>
                                Activa
                            </option>
                            <option value="cancelled" <?php echo e(old('status', $subscription->status) == 'cancelled' ? 'selected' : ''); ?>>
                                Cancelada
                            </option>
                            <option value="expired" <?php echo e(old('status', $subscription->status) == 'expired' ? 'selected' : ''); ?>>
                                Expirada
                            </option>
                            <option value="suspended" <?php echo e(old('status', $subscription->status) == 'suspended' ? 'selected' : ''); ?>>
                                Suspendida
                            </option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inicio *</label>
                            <input type="date" name="starts_at"
                                   class="form-control <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('starts_at', $subscription->starts_at->format('Y-m-d'))); ?>"
                                   required>
                            <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Vencimiento *</label>
                            <input type="date" name="ends_at"
                                   class="form-control <?php $__errorArgs = ['ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('ends_at', $subscription->ends_at->format('Y-m-d'))); ?>"
                                   required>
                            <?php $__errorArgs = ['ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri ri-information-line me-2"></i>
                        <strong>Nota:</strong> Al cambiar el plan, el monto se actualizará automáticamente. Las fechas deben ser coherentes con el ciclo de facturación del plan.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Guardar Cambios
                        </button>
                        <a href="<?php echo e(route('admin.subscriptions.show', $subscription->id)); ?>" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información Actual</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Plan Actual</label>
                    <p class="mb-0"><strong><?php echo e($subscription->plan->name); ?></strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Monto Actual</label>
                    <p class="mb-0"><strong>$<?php echo e(number_format($subscription->amount, 0)); ?></strong></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Estado Actual</label>
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
                <div class="mb-3">
                    <label class="text-muted small">Período Actual</label>
                    <p class="mb-0">
                        <?php echo e($subscription->starts_at->format('d/m/Y')); ?><br>
                        <small class="text-muted">hasta</small><br>
                        <?php echo e($subscription->ends_at->format('d/m/Y')); ?>

                    </p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Restaurante</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong><?php echo e($subscription->restaurant->name); ?></strong></p>
                <p class="text-muted small mb-0"><?php echo e($subscription->restaurant->email); ?></p>
                <a href="<?php echo e(route('admin.restaurants.show', $subscription->restaurant->id)); ?>"
                   class="btn btn-sm btn-outline-primary w-100 mt-3">
                    <i class="ri ri-eye-line me-1"></i>Ver Restaurante
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/subscriptions/edit.blade.php ENDPATH**/ ?>