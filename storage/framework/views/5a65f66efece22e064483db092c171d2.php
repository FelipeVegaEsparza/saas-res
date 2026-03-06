<?php $__env->startSection('title', 'Detalle del Pedido'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Pedido <?php echo e($deliveryOrder->order_number); ?></h1>
            <p class="text-muted"><?php echo e($deliveryOrder->created_at->format('d/m/Y H:i')); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-secondary">
                <i class="ri ri-arrow-left-line me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Información del pedido -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Información del Pedido</h5>
                <span class="badge bg-label-<?php echo e($deliveryOrder->type === 'delivery' ? 'primary' : 'info'); ?>">
                    <i class="ri ri-<?php echo e($deliveryOrder->type === 'delivery' ? 'e-bike-2' : 'shopping-bag'); ?>-line me-1"></i>
                    <?php echo e($deliveryOrder->type_label); ?>

                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Cliente</label>
                        <p class="mb-0"><strong><?php echo e($deliveryOrder->customer_name); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Teléfono</label>
                        <p class="mb-0">
                            <a href="tel:<?php echo e($deliveryOrder->customer_phone); ?>"><?php echo e($deliveryOrder->customer_phone); ?></a>
                        </p>
                    </div>
                    <?php if($deliveryOrder->customer_email): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <p class="mb-0">
                            <a href="mailto:<?php echo e($deliveryOrder->customer_email); ?>"><?php echo e($deliveryOrder->customer_email); ?></a>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if($deliveryOrder->type === 'delivery'): ?>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted small">Dirección de Entrega</label>
                        <p class="mb-0"><?php echo e($deliveryOrder->delivery_address); ?></p>
                    </div>
                    <?php if($deliveryOrder->delivery_zone): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Zona</label>
                        <p class="mb-0"><?php echo e($deliveryOrder->delivery_zone); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if($deliveryOrder->notes): ?>
                    <div class="col-12">
                        <label class="form-label text-muted small">Notas</label>
                        <p class="mb-0"><?php echo e($deliveryOrder->notes); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-end">Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $deliveryOrder->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($item->product->name); ?></strong>
                                    <?php if($item->notes): ?>
                                        <br><small class="text-muted"><?php echo e($item->notes); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?php echo \App\Helpers\Helpers::formatPrice($item->price); ?></td>
                                <td class="text-center"><?php echo e($item->quantity); ?></td>
                                <td class="text-end"><strong><?php echo \App\Helpers\Helpers::formatPrice($item->subtotal); ?></strong></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end"><strong><?php echo \App\Helpers\Helpers::formatPrice($deliveryOrder->subtotal); ?></strong></td>
                            </tr>
                            <?php if($deliveryOrder->delivery_fee > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Envío:</strong></td>
                                <td class="text-end"><strong>$<?php echo e(number_format($deliveryOrder->delivery_fee, 2)); ?></strong></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                <td class="text-end"><h5 class="mb-0"><?php echo \App\Helpers\Helpers::formatPrice($deliveryOrder->total); ?></h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Estado del pedido -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Estado del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <span class="badge bg-<?php echo e($deliveryOrder->status_color); ?> p-3" style="font-size: 1.1rem;">
                        <?php echo e($deliveryOrder->status_label); ?>

                    </span>
                </div>

                <!-- Botón Imprimir Comanda -->
                <a href="<?php echo e(route('tenant.path.delivery.printComanda', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $deliveryOrder])); ?>"
                   class="btn btn-primary w-100 mb-3"
                   target="_blank">
                    <i class="ri ri-printer-line me-1"></i> Imprimir Comanda
                </a>

                <form action="<?php echo e(route('tenant.path.delivery.updateStatus', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $deliveryOrder])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Cambiar Estado</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" <?php echo e($deliveryOrder->status === 'pending' ? 'selected' : ''); ?>>Pendiente</option>
                            <option value="confirmed" <?php echo e($deliveryOrder->status === 'confirmed' ? 'selected' : ''); ?>>Confirmado</option>
                            <option value="preparing" <?php echo e($deliveryOrder->status === 'preparing' ? 'selected' : ''); ?>>Preparando</option>
                            <option value="ready" <?php echo e($deliveryOrder->status === 'ready' ? 'selected' : ''); ?>>Listo</option>
                            <?php if($deliveryOrder->type === 'delivery'): ?>
                            <option value="on_delivery" <?php echo e($deliveryOrder->status === 'on_delivery' ? 'selected' : ''); ?>>En Camino</option>
                            <?php endif; ?>
                            <option value="delivered" <?php echo e($deliveryOrder->status === 'delivered' ? 'selected' : ''); ?>>Entregado</option>
                            <option value="cancelled" <?php echo e($deliveryOrder->status === 'cancelled' ? 'selected' : ''); ?>>Cancelado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-refresh-line me-1"></i> Actualizar Estado
                    </button>
                </form>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Historial</h5>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Pedido Creado</h6>
                                <small class="text-muted"><?php echo e($deliveryOrder->created_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>
                    </li>
                    <?php if($deliveryOrder->confirmed_at): ?>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-info"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Confirmado</h6>
                                <small class="text-muted"><?php echo e($deliveryOrder->confirmed_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if($deliveryOrder->ready_at): ?>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Listo</h6>
                                <small class="text-muted"><?php echo e($deliveryOrder->ready_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if($deliveryOrder->delivered_at): ?>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Entregado</h6>
                                <small class="text-muted"><?php echo e($deliveryOrder->delivered_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/delivery/show.blade.php ENDPATH**/ ?>