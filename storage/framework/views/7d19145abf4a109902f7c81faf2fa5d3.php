<?php $__env->startSection('title', 'Productos'); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Storage;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Productos</h1>
    <a href="<?php echo e(route('tenant.path.products.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nuevo Producto
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(Storage::url($product->image)); ?>" alt="<?php echo e($product->name); ?>"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="ri ri-image-line text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($product->name); ?></strong>
                                <?php if($product->description): ?>
                                    <br><small class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($product->description, 50)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($product->category->name); ?></td>
                            <td><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($product->available ? 'success' : 'secondary'); ?>">
                                    <?php echo e($product->available ? 'Disponible' : 'No disponible'); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($product->featured): ?>
                                    <i class="ri ri-star-fill text-warning"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('tenant.path.products.edit', ['tenant' => request()->route('tenant'), 'product' => $product])); ?>" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-edit-box-line ri-20px"></i>
                                </a>
                                <form action="<?php echo e(route('tenant.path.products.destroy', ['tenant' => request()->route('tenant'), 'product' => $product])); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-center">
                                    <i class="ri ri-restaurant-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay productos registrados</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/products/index.blade.php ENDPATH**/ ?>