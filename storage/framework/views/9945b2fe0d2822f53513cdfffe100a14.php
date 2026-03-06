<?php $__env->startSection('title', 'KDS - ' . $area->name . ' - Mesas'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 50px; height: 50px; background: <?php echo e($area->color); ?>; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="ri <?php echo e($area->icon); ?> ri-24px text-white"></i>
            </div>
            <div>
                <h1 class="mb-0"><?php echo e($area->name); ?> - Mesas</h1>
                <p class="text-muted mb-0"><?php echo e($orders->count()); ?> pedidos activos</p>
            </div>
        </div>
        <div>
            <button onclick="location.reload()" class="btn btn-outline-primary">
                <i class="ri ri-refresh-line me-1"></i> Actualizar
            </button>
        </div>
    </div>
</div>

<?php if($orders->isEmpty()): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri ri-checkbox-circle-line ri-48px text-success mb-3"></i>
            <h5>No hay pedidos pendientes</h5>
            <p class="text-muted">Todos los pedidos de <?php echo e($area->name); ?> están completados</p>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100" style="border-left: 4px solid <?php echo e($area->color); ?>;">
                    <div class="card-header" style="background: linear-gradient(135deg, <?php echo e($area->color); ?>15 0%, <?php echo e($area->color); ?>05 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Mesa <?php echo e($order->table->number); ?></h5>
                                <small class="text-muted"><?php echo e($order->created_at->format('H:i')); ?> - <?php echo e($order->created_at->diffForHumans()); ?></small>
                            </div>
                            <span class="badge bg-label-<?php echo e($order->statusColor); ?>">
                                <?php echo e($order->statusLabel); ?>

                            </span>
                        </div>
                        <?php if($order->waiter): ?>
                            <small class="text-muted">
                                <i class="ri ri-user-line"></i> <?php echo e($order->waiter->name); ?>

                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3 p-2 rounded" style="background: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <strong><?php echo e($item->quantity); ?>x <?php echo e($item->product->name); ?></strong>
                                        <?php if($item->notes): ?>
                                            <br><small class="text-muted"><i class="ri ri-message-2-line"></i> <?php echo e($item->notes); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ms-2">
                                        <?php
                                            $statusColors = [
                                                'pending' => 'secondary',
                                                'preparing' => 'warning',
                                                'ready' => 'success',
                                                'delivered' => 'info'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pendiente',
                                                'preparing' => 'Preparando',
                                                'ready' => 'Listo',
                                                'delivered' => 'Entregado'
                                            ];
                                            $currentStatus = $item->preparation_status ?? 'pending';
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$currentStatus]); ?> item-status" data-item-id="<?php echo e($item->id); ?>">
                                            <?php echo e($statusLabels[$currentStatus]); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm w-100" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-status"
                                            data-order-id="<?php echo e($order->id); ?>"
                                            data-item-id="<?php echo e($item->id); ?>"
                                            data-status="pending"
                                            <?php echo e($currentStatus === 'pending' ? 'disabled' : ''); ?>>
                                        Pendiente
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-status"
                                            data-order-id="<?php echo e($order->id); ?>"
                                            data-item-id="<?php echo e($item->id); ?>"
                                            data-status="preparing"
                                            <?php echo e($currentStatus === 'preparing' ? 'disabled' : ''); ?>>
                                        Preparando
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-status"
                                            data-order-id="<?php echo e($order->id); ?>"
                                            data-item-id="<?php echo e($item->id); ?>"
                                            data-status="ready"
                                            <?php echo e($currentStatus === 'ready' ? 'disabled' : ''); ?>>
                                        Listo
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($order->kitchen_notes): ?>
                            <div class="alert alert-info mt-3 mb-0">
                                <small><strong><i class="ri ri-information-line"></i> Notas:</strong><br><?php echo e($order->kitchen_notes); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar estado de item
    document.querySelectorAll('.btn-status').forEach(button => {
        button.addEventListener('click', async function() {
            const orderId = this.dataset.orderId;
            const itemId = this.dataset.itemId;
            const status = this.dataset.status;

            try {
                const response = await fetch(`<?php echo e(route('tenant.path.kds.tables.updateItem', ['tenant' => request()->route('tenant'), 'order_id' => ':orderId', 'item_id' => ':itemId'])); ?>`.replace(':orderId', orderId).replace(':itemId', itemId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error al actualizar el estado');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al actualizar el estado');
            }
        });
    });

    // Auto-refresh cada 30 segundos
    setTimeout(() => {
        location.reload();
    }, 30000);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/kds/tables.blade.php ENDPATH**/ ?>