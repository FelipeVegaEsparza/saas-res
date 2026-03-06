<?php
use Illuminate\Support\Facades\Storage;
?>



<?php $__env->startSection('title', $restaurant->name . ' - Pedidos No Disponibles'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="avatar avatar-xl mx-auto mb-4">
                <span class="avatar-initial rounded-circle bg-label-warning">
                    <i class="ri ri-error-warning-line ri-48px"></i>
                </span>
            </div>

            <?php if($restaurant->logo_horizontal): ?>
                <img src="<?php echo e(Storage::url($restaurant->logo_horizontal)); ?>"
                     alt="<?php echo e($restaurant->name); ?>"
                     style="max-height: 80px; margin-bottom: 1rem;">
            <?php endif; ?>

            <h2 class="mb-3">Pedidos Online No Disponibles</h2>
            <p class="text-muted mb-4">
                Lo sentimos, actualmente no estamos aceptando pedidos online.
                Por favor contáctanos directamente o visítanos en nuestro local.
            </p>

            <?php if($restaurant->phone): ?>
            <div class="mb-3">
                <a href="tel:<?php echo e($restaurant->phone); ?>" class="btn btn-primary">
                    <i class="ri ri-phone-line me-2"></i>Llamar: <?php echo e($restaurant->phone); ?>

                </a>
            </div>
            <?php endif; ?>

            <a href="<?php echo e(route('tenant.path.menu.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-primary">
                <i class="ri ri-restaurant-line me-2"></i>Ver Menú
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/online/unavailable.blade.php ENDPATH**/ ?>