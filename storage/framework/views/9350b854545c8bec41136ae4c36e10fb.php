<?php $__env->startSection('title', 'Tutoriales - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Tutoriales</h1>
            <p class="text-muted">Gestiona los videos tutoriales</p>
        </div>
        <a href="<?php echo e(route('admin.tutorials.create')); ?>" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i>Nuevo Tutorial
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Video</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tutorials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutorial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($tutorial->order); ?></td>
                        <td>
                            <?php if($tutorial->youtube_id): ?>
                                <img src="https://img.youtube.com/vi/<?php echo e($tutorial->youtube_id); ?>/default.jpg"
                                     alt="<?php echo e($tutorial->title); ?>"
                                     class="rounded"
                                     style="width: 80px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 60px;">
                                    <i class="ri ri-video-line ri-24px text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($tutorial->title); ?></strong>
                            <?php if($tutorial->description): ?>
                                <br><small class="text-muted"><?php echo e(substr($tutorial->description, 0, 60)); ?><?php echo e(strlen($tutorial->description) > 60 ? '...' : ''); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-label-primary"><?php echo e($tutorial->category->name); ?></span>
                        </td>
                        <td>
                            <?php if($tutorial->is_active): ?>
                                <span class="badge bg-label-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-label-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('admin.tutorials.edit', $tutorial->id)); ?>" class="btn btn-sm btn-primary">
                                    <i class="ri ri-edit-line"></i>
                                </a>
                                <form action="<?php echo e(route('admin.tutorials.destroy', $tutorial->id)); ?>"
                                      method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este tutorial?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ri ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ri ri-video-line ri-48px text-muted mb-3 d-block"></i>
                            <h5 class="mb-2">No hay tutoriales creados</h5>
                            <p class="text-muted mb-4">Crea tu primer tutorial</p>
                            <a href="<?php echo e(route('admin.tutorials.create')); ?>" class="btn btn-primary">
                                <i class="ri ri-add-line me-1"></i>Crear Primer Tutorial
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($tutorials->hasPages()): ?>
    <div class="mt-4">
        <?php echo e($tutorials->links()); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/admin/tutorials/index.blade.php ENDPATH**/ ?>