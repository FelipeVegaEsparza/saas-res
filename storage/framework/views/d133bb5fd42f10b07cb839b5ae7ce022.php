<?php $__env->startSection('title', 'Pedidos Delivery'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Pedidos Delivery</h1>
        <p class="text-muted">Gestiona pedidos de delivery y para llevar</p>
    </div>
    <a href="<?php echo e(route('tenant.path.delivery.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
        <i class="ri ri-add-line me-1"></i> Nuevo Pedido
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Número, cliente, teléfono..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select name="type" class="form-select">
                    <option value="">Todos</option>
                    <option value="delivery" <?php echo e(request('type') === 'delivery' ? 'selected' : ''); ?>>Delivery</option>
                    <option value="takeaway" <?php echo e(request('type') === 'takeaway' ? 'selected' : ''); ?>>Para Llevar</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pendiente</option>
                    <option value="confirmed" <?php echo e(request('status') === 'confirmed' ? 'selected' : ''); ?>>Confirmado</option>
                    <option value="preparing" <?php echo e(request('status') === 'preparing' ? 'selected' : ''); ?>>Preparando</option>
                    <option value="ready" <?php echo e(request('status') === 'ready' ? 'selected' : ''); ?>>Listo</option>
                    <option value="on_delivery" <?php echo e(request('status') === 'on_delivery' ? 'selected' : ''); ?>>En Camino</option>
                    <option value="delivered" <?php echo e(request('status') === 'delivered' ? 'selected' : ''); ?>>Entregado</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="ri ri-search-line me-1"></i> Buscar
                </button>
                <a href="<?php echo e(route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-secondary">
                    <i class="ri ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de pedidos -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($order->order_number); ?></strong>
                                <br><small class="text-muted"><?php echo e($order->items->count()); ?> items</small>
                            </td>
                            <td>
                                <span class="badge bg-label-<?php echo e($order->type === 'delivery' ? 'primary' : 'info'); ?>">
                                    <i class="ri ri-<?php echo e($order->type === 'delivery' ? 'e-bike-2' : 'shopping-bag'); ?>-line me-1"></i>
                                    <?php echo e($order->type_label); ?>

                                </span>
                            </td>
                            <td><?php echo e($order->customer_name); ?></td>
                            <td><?php echo e($order->customer_phone); ?></td>
                            <td><strong><?php echo \App\Helpers\Helpers::formatPrice($order->total); ?></strong></td>
                            <td>
                                <span class="badge bg-<?php echo e($order->status_color); ?>">
                                    <?php echo e($order->status_label); ?>

                                </span>
                            </td>
                            <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('tenant.path.delivery.show', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order])); ?>"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-eye-line ri-20px"></i>
                                </a>
                                <form action="<?php echo e(route('tenant.path.delivery.destroy', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order])); ?>"
                                      method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este pedido?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-center">
                                    <i class="ri ri-e-bike-2-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay pedidos registrados</p>
                                    <a href="<?php echo e(route('tenant.path.delivery.create', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary mt-2">
                                        <i class="ri ri-add-line me-1"></i> Crear Primer Pedido
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($orders->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/delivery/index.blade.php ENDPATH**/ ?>