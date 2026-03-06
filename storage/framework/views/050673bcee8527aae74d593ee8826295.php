<?php $__env->startSection('title', 'Pedido - Mesa ' . $table->number); ?>

<?php $__env->startSection('content'); ?>
<!-- Header con info de la mesa -->
<div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div>
                        <h3 class="mb-0">
                            <i class="ri ri-table-line me-2"></i>Mesa <?php echo e($table->number); ?>

                        </h3>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <small class="opacity-75">Pedido:</small>
                        <strong class="ms-1"><?php echo e($order->order_number); ?></strong>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <span class="badge bg-<?php echo e($order->statusColor); ?>">
                            <?php echo e($order->statusLabel); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?php echo e(route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-light btn-sm">
                    <i class="ri ri-arrow-left-line me-1"></i> Volver a Mesas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Detalle del Pedido -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Items del Pedido</h5>
                <?php if($order->canBeEdited()): ?>
                    <a href="<?php echo e(route('tenant.path.tables.takeOrder', ['tenant' => request()->route('tenant'), 'table_id' => $table->id])); ?>"
                       class="btn btn-sm btn-primary">
                        <i class="ri ri-add-line me-1"></i> Agregar Items
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($item->product->name); ?></strong>
                                        <?php if($item->notes): ?>
                                            <br><small class="text-muted"><?php echo e($item->notes); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo \App\Helpers\Helpers::formatPrice($item->unit_price); ?></td>
                                    <td class="text-center"><?php echo e($item->quantity); ?></td>
                                    <td class="text-end"><strong><?php echo \App\Helpers\Helpers::formatPrice($item->subtotal); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><h5 class="mb-0"><?php echo \App\Helpers\Helpers::formatPrice($order->total); ?></h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <?php if($order->kitchen_notes): ?>
                    <div class="alert alert-info mt-3">
                        <strong><i class="ri ri-information-line me-2"></i>Notas para cocina:</strong>
                        <p class="mb-0 mt-1"><?php echo e($order->kitchen_notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Información y Acciones -->
    <div class="col-lg-4 mb-4">
        <!-- Estado del Pedido -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Estado del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Estado Actual</label>
                    <div>
                        <span class="badge bg-<?php echo e($order->statusColor); ?> fs-6">
                            <?php echo e($order->statusLabel); ?>

                        </span>
                    </div>
                </div>

                <?php if($order->waiter): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Mesero</label>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    <?php echo e(substr($order->waiter->name, 0, 1)); ?>

                                </span>
                            </div>
                            <span><?php echo e($order->waiter->name); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label text-muted small">Hora del Pedido</label>
                    <p class="mb-0"><?php echo e($order->created_at->format('H:i')); ?></p>
                </div>

                <?php if($order->preparing_at): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">En Preparación</label>
                        <p class="mb-0"><?php echo e($order->preparing_at->format('H:i')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($order->ready_at): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Listo</label>
                        <p class="mb-0"><?php echo e($order->ready_at->format('H:i')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($order->served_at): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Servido</label>
                        <p class="mb-0"><?php echo e($order->served_at->format('H:i')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <!-- Botón Imprimir Comanda Completa -->
                <a href="<?php echo e(route('tenant.path.tables.printComanda', ['tenant' => request()->route('tenant'), 'table_id' => $table->id])); ?>"
                   class="btn btn-outline-primary w-100 mb-3"
                   target="_blank">
                    <i class="ri ri-printer-line me-1"></i> Imprimir Comanda Completa
                </a>

                <?php if($preparationAreas->isNotEmpty()): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Imprimir por Estación</label>
                        <?php $__currentLoopData = $preparationAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('tenant.path.tables.printComandaByArea', ['tenant' => request()->route('tenant'), 'table_id' => $table->id, 'area_id' => $area->id])); ?>"
                               class="btn btn-outline-secondary w-100 mb-2"
                               target="_blank">
                                <i class="ri <?php echo e($area->icon); ?> me-1" style="color: <?php echo e($area->color); ?>;"></i> <?php echo e($area->name); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <hr class="my-3">

                <form action="<?php echo e(route('tenant.path.tables.updateOrderStatus', ['tenant' => request()->route('tenant'), 'table_id' => $table->id])); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <?php if($order->status === 'pending'): ?>
                        <button type="submit" name="status" value="preparing" class="btn btn-info w-100 mb-2">
                            <i class="ri ri-restaurant-2-line me-1"></i> Marcar En Preparación
                        </button>
                    <?php endif; ?>

                    <?php if($order->status === 'preparing'): ?>
                        <button type="submit" name="status" value="ready" class="btn btn-primary w-100 mb-2">
                            <i class="ri ri-check-line me-1"></i> Marcar Listo
                        </button>
                    <?php endif; ?>

                    <?php if($order->status === 'ready'): ?>
                        <button type="submit" name="status" value="served" class="btn btn-success w-100 mb-2">
                            <i class="ri ri-restaurant-line me-1"></i> Marcar Servido
                        </button>
                    <?php endif; ?>

                    <?php if($order->status === 'served'): ?>
                        <button type="submit" name="status" value="closed" class="btn btn-secondary w-100 mb-2">
                            <i class="ri ri-file-list-line me-1"></i> Cerrar Cuenta
                        </button>
                    <?php endif; ?>

                    <?php if($order->status === 'closed'): ?>
                        <div class="alert alert-success">
                            <i class="ri ri-check-line me-2"></i>
                            <strong>Cuenta cerrada</strong>
                            <p class="mb-0 mt-1 small">Dirígete a Caja para procesar el pago</p>
                        </div>
                        <a href="<?php echo e(route('tenant.path.cash.index', ['tenant' => request()->route('tenant')])); ?>"
                           class="btn btn-success w-100 mb-2">
                            <i class="ri ri-cash-line me-1"></i> Ir a Caja
                        </a>
                    <?php endif; ?>

                    <?php if(in_array($order->status, ['pending', 'preparing', 'ready', 'served'])): ?>
                        <button type="submit" name="status" value="cancelled" class="btn btn-outline-danger w-100"
                                onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                            <i class="ri ri-close-line me-1"></i> Cancelar Pedido
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/tables/show-order.blade.php ENDPATH**/ ?>