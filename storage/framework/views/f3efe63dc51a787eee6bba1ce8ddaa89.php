<?php $__env->startSection('title', 'Categorías'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Categorías</h1>
    <a href="<?php echo e(route('tenant.path.categories.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nueva Categoría
    </a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Productos</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($category->name); ?></strong></td>
                            <td><?php echo e($category->products_count); ?></td>
                            <td><?php echo e($category->order); ?></td>
                            <td><span class="badge bg-<?php echo e($category->active ? 'success' : 'secondary'); ?>"><?php echo e($category->active ? 'Activa' : 'Inactiva'); ?></span></td>
                            <td>
                                <a href="<?php echo e(route('tenant.path.categories.edit', ['tenant' => request()->route('tenant'), 'category' => $category])); ?>" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-edit-box-line ri-20px"></i>
                                </a>
                                <form action="<?php echo e(route('tenant.path.categories.destroy', ['tenant' => request()->route('tenant'), 'category' => $category])); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" onclick="return confirm('¿Eliminar?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-center">
                                    <i class="ri ri-list-check ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay categorías registradas</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($categories->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/categories/index.blade.php ENDPATH**/ ?>