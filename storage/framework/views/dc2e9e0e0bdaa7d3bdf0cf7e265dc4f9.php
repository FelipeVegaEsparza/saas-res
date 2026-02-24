<?php $__env->startSection('title', 'Usuarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Usuarios</h1>
    <a href="<?php echo e(route('tenant.path.users.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nuevo Usuario
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <?php echo e(substr($user->name, 0, 1)); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <strong><?php echo e($user->name); ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <span class="badge bg-label-<?php echo e($user->role === 'owner' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info')); ?>">
                                    <?php echo e($user->role_name); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($user->active ? 'success' : 'secondary'); ?>">
                                    <?php echo e($user->active ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('tenant.path.users.edit', ['tenant' => request()->route('tenant'), 'user' => $user])); ?>"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-edit-box-line ri-20px"></i>
                                </a>
                                <form action="<?php echo e(route('tenant.path.users.destroy', ['tenant' => request()->route('tenant'), 'user' => $user])); ?>"
                                      method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este usuario?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-center">
                                    <i class="ri ri-user-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay usuarios registrados</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/users/index.blade.php ENDPATH**/ ?>