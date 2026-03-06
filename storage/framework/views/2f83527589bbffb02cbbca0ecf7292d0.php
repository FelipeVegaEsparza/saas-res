<?php
    $minutesElapsed = $order->created_at->diffInMinutes(now());
    $timeClass = '';
    if ($minutesElapsed > 20) {
        $timeClass = 'time-danger';
    } elseif ($minutesElapsed > 10) {
        $timeClass = 'time-warning';
    }

    $currentStatus = $order->items->first()->preparation_status ?? 'pending';
?>

<div class="card kds-card <?php echo e($timeClass); ?> mb-3"
     data-order-type="<?php echo e($order->order_type); ?>"
     data-order-id="<?php echo e($order->id); ?>"
     data-current-status="<?php echo e($currentStatus); ?>">
    <div class="card-body">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="mb-1">
                    <?php if($order->order_type === 'table'): ?>
                        <i class="ri ri-table-line me-1" style="color: <?php echo e($area->color); ?>;"></i>
                        <?php echo e($order->display_name); ?>

                    <?php else: ?>
                        <i class="ri ri-e-bike-2-line me-1" style="color: <?php echo e($area->color); ?>;"></i>
                        <?php echo e($order->display_name); ?>

                    <?php endif; ?>
                </h5>
                <small class="text-muted">
                    <i class="ri ri-time-line"></i> <?php echo e($order->created_at->format('H:i')); ?>

                    <span class="ms-2 <?php echo e($minutesElapsed > 20 ? 'text-danger fw-bold' : ($minutesElapsed > 10 ? 'text-warning fw-bold' : '')); ?>">
                        (<?php echo e($minutesElapsed); ?> min)
                    </span>
                </small>
            </div>
            <div>
                <?php if($order->order_type === 'table'): ?>
                    <span class="badge bg-label-primary">Mesa</span>
                <?php else: ?>
                    <span class="badge bg-label-info">
                        <?php if($order->type === 'delivery'): ?>
                            Delivery
                        <?php else: ?>
                            Para Llevar
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info adicional -->
        <?php if($order->order_type === 'table' && $order->waiter): ?>
            <div class="mb-2">
                <small class="text-muted">
                    <i class="ri ri-user-line"></i> <?php echo e($order->waiter->name); ?>

                </small>
            </div>
        <?php endif; ?>

        <?php if($order->order_type === 'delivery'): ?>
            <div class="mb-2 customer-info">
                <small>
                    <strong><i class="ri ri-user-line"></i> <?php echo e($order->customer_name); ?></strong><br>
                    <i class="ri ri-phone-line"></i> <?php echo e($order->customer_phone); ?>

                </small>
            </div>
        <?php endif; ?>

        <!-- Items del pedido -->
        <div class="mb-3">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span><strong><?php echo e($item->quantity); ?>x</strong> <?php echo e($item->product->name); ?></span>
                </div>
                <?php if($item->notes): ?>
                    <div class="ps-3">
                        <small class="text-muted"><i class="ri ri-message-2-line"></i> <?php echo e($item->notes); ?></small>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Notas del pedido -->
        <?php if($order->order_type === 'table' && $order->kitchen_notes): ?>
            <div class="alert alert-info py-2 mb-3">
                <small><strong><i class="ri ri-information-line"></i> Notas:</strong> <?php echo e($order->kitchen_notes); ?></small>
            </div>
        <?php elseif($order->order_type === 'delivery' && $order->notes): ?>
            <div class="alert alert-info py-2 mb-3">
                <small><strong><i class="ri ri-information-line"></i> Notas:</strong> <?php echo e($order->notes); ?></small>
            </div>
        <?php endif; ?>

        <!-- Botones de acción -->
        <div class="d-grid gap-2">
            <?php if($currentStatus === 'pending'): ?>
                <button class="btn btn-warning btn-change-status"
                        data-next-status="preparing"
                        data-area-id="<?php echo e($area->id); ?>"
                        data-tenant="<?php echo e(request()->route('tenant')); ?>">
                    <i class="ri ri-restaurant-2-line me-1"></i> Iniciar Preparación
                </button>
            <?php elseif($currentStatus === 'preparing'): ?>
                <button class="btn btn-success btn-change-status"
                        data-next-status="ready"
                        data-area-id="<?php echo e($area->id); ?>"
                        data-tenant="<?php echo e(request()->route('tenant')); ?>">
                    <i class="ri ri-checkbox-circle-line me-1"></i> Marcar Listo
                </button>
            <?php elseif($currentStatus === 'ready'): ?>
                <button class="btn btn-outline-secondary btn-sm btn-change-status"
                        data-next-status="preparing"
                        data-area-id="<?php echo e($area->id); ?>"
                        data-tenant="<?php echo e(request()->route('tenant')); ?>">
                    <i class="ri ri-arrow-left-line me-1"></i> Volver a Preparando
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH F:\saasres\resources\views/tenant/kds/partials/order-card.blade.php ENDPATH**/ ?>