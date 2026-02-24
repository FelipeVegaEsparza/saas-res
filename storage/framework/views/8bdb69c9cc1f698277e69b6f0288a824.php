<?php $__env->startSection('title', 'Cambiar Credenciales - ' . $restaurant->name); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.restaurants.index')); ?>">Restaurantes</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.restaurants.show', $restaurant->id)); ?>"><?php echo e($restaurant->name); ?></a></li>
            <li class="breadcrumb-item active">Cambiar Credenciales</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="ri ri-lock-password-line me-2"></i>
                    Cambiar Credenciales de Acceso
                </h4>
                <p class="text-muted mb-0 mt-2">Restaurante: <strong><?php echo e($restaurant->name); ?></strong></p>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="ri ri-alert-line me-2"></i>
                    <strong>Importante:</strong> Al cambiar estas credenciales, el usuario actual del restaurante no podrá acceder con sus credenciales anteriores.
                </div>

                <form action="<?php echo e(route('admin.restaurants.updateCredentials', $restaurant->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-4">
                        <label for="admin_email" class="form-label">
                            <strong>Email de Acceso</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-control <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="admin_email"
                            name="admin_email"
                            value="<?php echo e(old('admin_email')); ?>"
                            required
                            placeholder="admin@restaurante.com"
                        >
                        <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Este será el nuevo email para iniciar sesión en el sistema</small>
                    </div>

                    <div class="mb-4">
                        <label for="admin_password" class="form-label">
                            <strong>Nueva Contraseña</strong>
                        </label>
                        <input
                            type="password"
                            class="form-control <?php $__errorArgs = ['admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="admin_password"
                            name="admin_password"
                            placeholder="Dejar en blanco para mantener la actual"
                        >
                        <?php $__errorArgs = ['admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Mínimo 8 caracteres. Dejar en blanco si no deseas cambiarla</small>
                    </div>

                    <div class="mb-4">
                        <label for="admin_password_confirmation" class="form-label">
                            <strong>Confirmar Nueva Contraseña</strong>
                        </label>
                        <input
                            type="password"
                            class="form-control"
                            id="admin_password_confirmation"
                            name="admin_password_confirmation"
                            placeholder="Confirmar contraseña"
                        >
                    </div>

                    <div class="alert alert-info">
                        <i class="ri ri-information-line me-2"></i>
                        <strong>Nota:</strong> Después de cambiar las credenciales, asegúrate de comunicarlas al administrador del restaurante.
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="<?php echo e(route('admin.restaurants.show', $restaurant->id)); ?>" class="btn btn-label-secondary">
                            <i class="ri ri-arrow-left-line me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri ri-save-line me-1"></i>Actualizar Credenciales
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/restaurants/edit-credentials.blade.php ENDPATH**/ ?>