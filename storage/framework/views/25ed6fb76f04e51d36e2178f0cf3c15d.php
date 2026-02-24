<?php $__env->startSection('title', $restaurant->name . ' - Menú Digital'); ?>

<?php $__env->startSection('page-style'); ?>
<?php
use Illuminate\Support\Facades\Storage;

$colorScheme = match($restaurant->menu_color_scheme ?? 'classic') {
    'modern' => [
        'primary' => '#3498db',
        'secondary' => '#2ecc71',
        'accent' => '#9b59b6',
        'background' => '#ffffff',
        'text' => '#34495e',
    ],
    'elegant' => [
        'primary' => '#1a1a1a',
        'secondary' => '#d4af37',
        'accent' => '#8b7355',
        'background' => '#f5f5f5',
        'text' => '#1a1a1a',
    ],
    'fresh' => [
        'primary' => '#27ae60',
        'secondary' => '#16a085',
        'accent' => '#f1c40f',
        'background' => '#e8f8f5',
        'text' => '#2c3e50',
    ],
    'warm' => [
        'primary' => '#e67e22',
        'secondary' => '#d35400',
        'accent' => '#c0392b',
        'background' => '#fef5e7',
        'text' => '#5d4037',
    ],
    default => [
        'primary' => '#2c3e50',
        'secondary' => '#e74c3c',
        'accent' => '#f39c12',
        'background' => '#ecf0f1',
        'text' => '#2c3e50',
    ],
};
?>
<style>
    :root {
        --primary-color: <?php echo e($colorScheme['primary']); ?>;
        --secondary-color: <?php echo e($colorScheme['secondary']); ?>;
        --accent-color: <?php echo e($colorScheme['accent']); ?>;
        --background-color: <?php echo e($colorScheme['background']); ?>;
        --text-color: <?php echo e($colorScheme['text']); ?>;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
        <?php if($restaurant->menu_background_image): ?>
        background-image: url('<?php echo e(Storage::url($restaurant->menu_background_image)); ?>');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        <?php endif; ?>
    }

    .menu-header {
        background: var(--primary-color);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .restaurant-info {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 0.75rem;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .category-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .category-title {
        color: var(--primary-color);
        font-weight: 600;
        margin: 0 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid var(--secondary-color);
    }

    .price-tag {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .badge-tag {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-right: 0.25rem;
        background-color: var(--accent-color);
        color: white;
    }

    .social-links a {
        color: var(--text-color);
        font-size: 1.5rem;
        margin: 0 0.5rem;
        transition: color 0.2s;
    }

    .social-links a:hover {
        color: var(--secondary-color);
    }

    footer {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="menu-header">
    <div class="container">
        <div class="text-center">
            <?php if($restaurant->logo_horizontal): ?>
                <img src="<?php echo e(Storage::url($restaurant->logo_horizontal)); ?>" alt="<?php echo e($restaurant->name); ?>" style="max-height: 80px; margin-bottom: 1rem;">
            <?php else: ?>
                <h1 class="mb-2"><?php echo e($restaurant->name); ?></h1>
            <?php endif; ?>
            <p class="mb-0">Menú Digital</p>
            <?php if($table): ?>
                <div class="mt-3">
                    <span class="badge bg-light text-dark">
                        <i class="ri ri-restaurant-2-line"></i> Mesa <?php echo e($table->number); ?>

                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- Información del Restaurante -->
    <?php if($restaurant->description || $restaurant->address || $restaurant->phone): ?>
    <div class="restaurant-info">
        <div class="row align-items-center">
            <div class="col-md-8">
                <?php if($restaurant->description): ?>
                    <p class="mb-2"><?php echo e($restaurant->description); ?></p>
                <?php endif; ?>
                <?php if($restaurant->address): ?>
                    <p class="mb-1">
                        <i class="ri ri-map-pin-line text-primary"></i>
                        <strong>Dirección:</strong> <?php echo e($restaurant->address); ?>

                    </p>
                <?php endif; ?>
                <?php if($restaurant->phone): ?>
                    <p class="mb-0">
                        <i class="ri ri-phone-line text-primary"></i>
                        <strong>Teléfono:</strong>
                        <a href="tel:<?php echo e($restaurant->phone); ?>" class="text-decoration-none"><?php echo e($restaurant->phone); ?></a>
                    </p>
                <?php endif; ?>
            </div>
            <?php if($restaurant->facebook || $restaurant->instagram || $restaurant->tiktok || $restaurant->twitter): ?>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="social-links">
                    <strong class="d-block mb-2">Síguenos:</strong>
                    <?php if($restaurant->facebook): ?>
                        <a href="<?php echo e($restaurant->facebook); ?>" target="_blank" title="Facebook">
                            <i class="ri ri-facebook-circle-fill"></i>
                        </a>
                    <?php endif; ?>
                    <?php if($restaurant->instagram): ?>
                        <a href="<?php echo e($restaurant->instagram); ?>" target="_blank" title="Instagram">
                            <i class="ri ri-instagram-fill"></i>
                        </a>
                    <?php endif; ?>
                    <?php if($restaurant->tiktok): ?>
                        <a href="<?php echo e($restaurant->tiktok); ?>" target="_blank" title="TikTok">
                            <i class="ri ri-tiktok-fill"></i>
                        </a>
                    <?php endif; ?>
                    <?php if($restaurant->twitter): ?>
                        <a href="<?php echo e($restaurant->twitter); ?>" target="_blank" title="X (Twitter)">
                            <i class="ri ri-twitter-x-fill"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($categories->isEmpty()): ?>
        <div class="category-section text-center py-5">
            <i class="ri ri-restaurant-line" style="font-size: 4rem; color: var(--secondary-color);"></i>
            <h3 class="mt-3">Menú en construcción</h3>
            <p class="text-muted">Pronto tendremos productos disponibles</p>
        </div>
    <?php else: ?>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="category-section">
                <h2 class="category-title">
                    <?php echo e($category->name); ?>

                </h2>

                <?php if($category->description): ?>
                    <p class="text-muted mb-4"><?php echo e($category->description); ?></p>
                <?php endif; ?>

                <div class="row g-4">
                    <?php $__currentLoopData = $category->activeProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card product-card h-100">
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(Storage::url($product->image)); ?>" class="product-image" alt="<?php echo e($product->name); ?>">
                                <?php else: ?>
                                    <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="ri ri-restaurant-2-line" style="font-size: 4rem; color: var(--secondary-color);"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?php echo e($product->name); ?></h5>
                                        <?php if($product->featured): ?>
                                            <span class="badge" style="background-color: var(--accent-color);">
                                                <i class="ri ri-star-fill"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($product->description): ?>
                                        <p class="card-text text-muted small mb-3">
                                            <?php echo e(\Illuminate\Support\Str::limit($product->description, 100)); ?>

                                        </p>
                                    <?php endif; ?>

                                    <?php if($product->tags): ?>
                                        <div class="mb-3">
                                            <?php $__currentLoopData = $product->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-tag"><?php echo e($tag); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="price-tag"><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></span>

                                        <?php if($product->preparation_time): ?>
                                            <small class="text-muted">
                                                <i class="ri ri-time-line"></i> <?php echo e($product->preparation_time); ?> min
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-white border-top py-4 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-muted mb-2 mb-md-0">
                    <strong><?php echo e($restaurant->name); ?></strong>
                </p>
                <?php if($restaurant->address): ?>
                    <p class="text-muted small mb-0"><?php echo e($restaurant->address); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <?php if($restaurant->phone): ?>
                    <p class="text-muted mb-2">
                        <i class="ri ri-phone-line"></i> <?php echo e($restaurant->phone); ?>

                    </p>
                <?php endif; ?>
                <?php if($restaurant->facebook || $restaurant->instagram || $restaurant->tiktok || $restaurant->twitter): ?>
                <div class="social-links">
                    <?php if($restaurant->facebook): ?>
                        <a href="<?php echo e($restaurant->facebook); ?>" target="_blank"><i class="ri ri-facebook-circle-fill"></i></a>
                    <?php endif; ?>
                    <?php if($restaurant->instagram): ?>
                        <a href="<?php echo e($restaurant->instagram); ?>" target="_blank"><i class="ri ri-instagram-fill"></i></a>
                    <?php endif; ?>
                    <?php if($restaurant->tiktok): ?>
                        <a href="<?php echo e($restaurant->tiktok); ?>" target="_blank"><i class="ri ri-tiktok-fill"></i></a>
                    <?php endif; ?>
                    <?php if($restaurant->twitter): ?>
                        <a href="<?php echo e($restaurant->twitter); ?>" target="_blank"><i class="ri ri-twitter-x-fill"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/menu/index.blade.php ENDPATH**/ ?>