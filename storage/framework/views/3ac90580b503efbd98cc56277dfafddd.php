<?php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
use Illuminate\Support\Facades\Storage;
$restaurant = tenant()->restaurant();
?>



<?php $__env->startSection('title', 'Login - ' . $restaurant->name); ?>

<?php $__env->startSection('page-style'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/scss/pages/page-auth.scss']); ?>
<style>
    <?php if($restaurant->menu_background_image): ?>
    body {
        background-image: url('<?php echo e(Storage::url($restaurant->menu_background_image)); ?>');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
    }
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(8px);
        z-index: -1;
    }

    /* Modo claro */
    .light-style body::before {
        background: rgba(255, 255, 255, 0.85);
    }

    /* Modo oscuro */
    .dark-style body::before {
        background: rgba(0, 0, 0, 0.85);
    }
    <?php endif; ?>
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
        <div class="authentication-inner py-6">
            <div class="card p-md-7 p-1">
                <div class="app-brand justify-content-center mt-5">
                    <a href="<?php echo e(route('tenant.path.menu.index', ['tenant' => request()->route('tenant')])); ?>" class="app-brand-link gap-2">
                        <?php if($restaurant->logo_horizontal): ?>
                            <img src="<?php echo e(Storage::url($restaurant->logo_horizontal)); ?>" alt="<?php echo e($restaurant->name); ?>" style="max-height: 60px;">
                        <?php else: ?>
                            <span class="app-brand-text demo text-heading fw-semibold"><?php echo e($restaurant->name); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="card-body mt-1">
                    <h4 class="mb-1">Bienvenido! 👋</h4>
                    <p class="mb-5">Inicia sesión en tu cuenta</p>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <?php echo e($errors->first()); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('tenant.path.login.post', ['tenant' => request()->route('tenant')])); ?>" class="mb-5">
                        <?php echo csrf_field(); ?>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="email" name="email" value="<?php echo e(old('email')); ?>"
                                   placeholder="correo@ejemplo.com" required autofocus>
                            <label for="email">Email</label>
                            <?php $__errorArgs = ['email'];
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

                        <div class="mb-5">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="password" name="password"
                                               placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                        <label for="password">Contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer">
                                        <i class="ri-eye-off-line ri-20px"></i>
                                    </span>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-5 d-flex justify-content-between mt-5">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                        </div>

                        <div class="mb-5">
                            <button class="btn btn-primary d-grid w-100" type="submit">Iniciar Sesión</button>
                        </div>
                    </form>

                    <p class="text-center mb-5">
                        <a href="<?php echo e(route('tenant.path.menu.index', ['tenant' => request()->route('tenant')])); ?>">
                            <span>Ver Menú Público</span>
                        </a>
                    </p>
                </div>
            </div>

            <img alt="mask"
                 src="<?php echo e(asset('assets/img/illustrations/auth-basic-login-mask-' . $configData['theme'] . '.png')); ?>"
                 class="authentication-image d-none d-lg-block"
                 data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                 data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/blankLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/auth/login.blade.php ENDPATH**/ ?>