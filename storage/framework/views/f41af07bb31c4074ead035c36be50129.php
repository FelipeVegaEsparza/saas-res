<?php $__env->startSection('title', 'Tutoriales - ' . $companyName); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section position-relative" style="padding: 80px 0 60px 0;">
    <div class="container position-relative" style="z-index: 1;">
        <div class="text-center">
            <h1 class="mb-3">Tutoriales</h1>
            <p class="mb-0" style="font-size: 1.15rem; opacity: 0.95;">
                Aprende a usar todas las funcionalidades de nuestro sistema
            </p>
        </div>
    </div>
</section>

<!-- Tutoriales Section -->
<section class="py-5">
    <div class="container py-4">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-5">
                <div class="mb-4">
                    <h2 class="section-title mb-2"><?php echo e($category->name); ?></h2>
                    <?php if($category->description): ?>
                        <p class="text-muted"><?php echo e($category->description); ?></p>
                    <?php endif; ?>
                </div>

                <?php if($category->activeTutorials->count() > 0): ?>
                    <div class="row g-4">
                        <?php $__currentLoopData = $category->activeTutorials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutorial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <?php if($tutorial->youtube_id): ?>
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/<?php echo e($tutorial->youtube_id); ?>"
                                                    allowfullscreen
                                                    class="card-img-top"></iframe>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2"><?php echo e($tutorial->title); ?></h5>
                                        <?php if($tutorial->description): ?>
                                            <p class="card-text text-muted"><?php echo e($tutorial->description); ?></p>
                                        <?php endif; ?>
                                        <a href="<?php echo e($tutorial->youtube_url); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ri ri-external-link-line me-1"></i>Ver en YouTube
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay tutoriales disponibles en esta categoría.
                    </div>
                <?php endif; ?>
            </div>

            <?php if(!$loop->last): ?>
                <hr class="my-5">
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5">
                <i class="ri ri-video-line" style="font-size: 4rem; color: #cbd5e0;"></i>
                <h3 class="mt-4 mb-2">Próximamente</h3>
                <p class="text-muted">Estamos preparando tutoriales para ayudarte a usar el sistema.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: #f7fafc;">
    <div class="container py-4">
        <div class="text-center">
            <h2 class="section-title mb-3">¿Necesitas ayuda personalizada?</h2>
            <p class="section-subtitle mb-4">Nuestro equipo está disponible para ayudarte</p>
            <a href="<?php echo e(route('landing.contact')); ?>" class="btn btn-primary btn-lg px-5">
                <i class="ri ri-mail-line me-2"></i>Contáctanos
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('landing.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/landing/tutorials.blade.php ENDPATH**/ ?>