<?php $__env->startSection('title', 'Estaciones de Preparación'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Estaciones de Preparación</h1>
            <p class="text-muted">Gestiona las áreas de preparación de tu restaurante</p>
        </div>
        <a href="<?php echo e(route('tenant.path.preparation-areas.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i> Nueva Estación
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if($areas->isEmpty()): ?>
            <div class="text-center py-5">
                <i class="ri ri-restaurant-2-line ri-48px text-muted mb-3"></i>
                <h5>No hay estaciones configuradas</h5>
                <p class="text-muted">Crea tu primera estación de preparación</p>
                <a href="<?php echo e(route('tenant.path.preparation-areas.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
                    <i class="ri ri-add-line me-1"></i> Crear Estación
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Color</th>
                            <th>Icono</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($area->order); ?></td>
                                <td>
                                    <strong><?php echo e($area->name); ?></strong>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: <?php echo e($area->color); ?>; color: white;">
                                        <?php echo e($area->color); ?>

                                    </span>
                                </td>
                                <td>
                                    <i class="ri <?php echo e($area->icon); ?> ri-24px" style="color: <?php echo e($area->color); ?>;"></i>
                                </td>
                                <td>
                                    <span class="badge bg-label-info"><?php echo e($area->products()->count()); ?> productos</span>
                                </td>
                                <td>
                                    <?php if($area->active): ?>
                                        <span class="badge bg-success">Activa</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactiva</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('tenant.path.preparation-areas.edit', ['tenant' => request()->route('tenant'), 'preparation_area' => $area])); ?>"
                                       class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                        <i class="ri ri-edit-line ri-20px"></i>
                                    </a>
                                    <form action="<?php echo e(route('tenant.path.preparation-areas.destroy', ['tenant' => request()->route('tenant'), 'preparation_area' => $area])); ?>"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta estación?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                            <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/preparation-areas/index.blade.php ENDPATH**/ ?>