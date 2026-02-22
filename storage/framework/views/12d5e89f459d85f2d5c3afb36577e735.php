<?php $__env->startSection('title', 'Categorías de Tutoriales - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Categorías de Tutoriales</h1>
            <p class="text-muted">Gestiona las categorías de tutoriales</p>
        </div>
        <a href="<?php echo e(route('admin.tutorial-categories.create')); ?>" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nueva Categoría
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Tutoriales</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($category->order); ?></td>
                        <td>
                            <strong><?php echo e($category->name); ?></strong>
                            <?php if($category->description): ?>
                                <br><small class="text-muted"><?php echo e(substr($category->description, 0, 50)); ?><?php echo e(strlen($category->description) > 50 ? '...' : ''); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><code><?php echo e($category->slug); ?></code></td>
                        <td>
                            <span class="badge bg-label-info"><?php echo e($category->tutorials_count); ?> videos</span>
                        </td>
                        <td>
                            <?php if($category->is_active): ?>
                                <span class="badge bg-label-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-label-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('admin.tutorial-categories.edit', $category->id)); ?>" class="btn btn-sm btn-primary">
                                    <i class="ri ri-edit-line"></i>
                                </a>
                                <?php if($category->tutorials_count == 0): ?>
                                    <form action="<?php echo e(route('admin.tutorial-categories.destroy', $category->id)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ri ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ri ri-folder-line ri-48px text-muted mb-3 d-block"></i>
                            <h5 class="mb-2">No hay categorías creadas</h5>
                            <p class="text-muted mb-4">Crea tu primera categoría de tutoriales</p>
                            <a href="<?php echo e(route('admin.tutorial-categories.create')); ?>" class="btn btn-primary">
                                <i class="ri ri-add-line me-1"></i>Crear Primera Categoría
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($categories->hasPages()): ?>
    <div class="mt-4">
        <?php echo e($categories->links()); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/tutorial-categories/index.blade.php ENDPATH**/ ?>