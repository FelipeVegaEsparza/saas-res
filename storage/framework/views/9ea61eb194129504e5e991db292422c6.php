<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-1">Dashboard</h1>
            <p class="text-muted"><?php echo e($restaurant->name); ?></p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri ri-file-list-3-line ri-24px"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Órdenes Hoy</p>
                            <h4 class="mb-0"><?php echo e($stats['orders_today']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri ri-money-dollar-circle-line ri-24px"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Ingresos Hoy</p>
                            <h4 class="mb-0"><?php echo \App\Helpers\Helpers::formatPrice($stats['revenue_today']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri ri-e-bike-2-line ri-24px"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Delivery Hoy</p>
                            <h4 class="mb-0"><?php echo e($stats['delivery_orders_today']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri ri-time-line ri-24px"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Pedidos Pendientes</p>
                            <h4 class="mb-0"><?php echo e($stats['delivery_pending']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Órdenes Recientes -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Órdenes Recientes</h5>
                </div>
                <div class="card-body">
                    <?php if($recentOrders->isEmpty()): ?>
                        <p class="text-muted text-center py-4">No hay órdenes recientes</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Mesa</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><strong><?php echo e($order->order_number); ?></strong></td>
                                            <td><?php echo e($order->table->number ?? 'N/A'); ?></td>
                                            <td><?php echo e($order->items->count()); ?> items</td>
                                            <td><?php echo \App\Helpers\Helpers::formatPrice($order->total); ?></td>
                                            <td>
                                                <?php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'preparing' => 'info',
                                                        'ready' => 'primary',
                                                        'delivered' => 'success',
                                                        'cancelled' => 'danger',
                                                    ];
                                                    $color = $statusColors[$order->status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo e($color); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                                            </td>
                                            <td><?php echo e($order->created_at->format('H:i')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Productos (7 días)</h5>
                </div>
                <div class="card-body">
                    <?php if($topProducts->isEmpty()): ?>
                        <p class="text-muted text-center py-4">Sin datos</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0"><?php echo e($product->name); ?></h6>
                                            <small class="text-muted"><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            <?php echo e($product->order_items_count); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Acceso Rápido -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Acceso Rápido</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-primary">
                            <i class="ri-e-bike-2-line"></i> Pedidos Delivery
                        </a>
                        <a href="<?php echo e(route('tenant.path.menu.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-primary" target="_blank">
                            <i class="ri-restaurant-line"></i> Ver Menú Público
                        </a>
                        <a href="<?php echo e(route('tenant.path.qr.print-all', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-secondary" target="_blank">
                            <i class="ri-qr-code-line"></i> Imprimir QR Mesas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Delivery Recientes -->
    <?php if($recentDeliveryOrders->isNotEmpty()): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pedidos Delivery Recientes</h5>
                    <a href="<?php echo e(route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-sm btn-primary">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Hora</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentDeliveryOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($order->order_number); ?></strong></td>
                                        <td>
                                            <span class="badge bg-label-<?php echo e($order->type === 'delivery' ? 'primary' : 'info'); ?>">
                                                <?php echo e($order->type_label); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($order->customer_name); ?></td>
                                        <td><strong><?php echo \App\Helpers\Helpers::formatPrice($order->total); ?></strong></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($order->status_color); ?>">
                                                <?php echo e($order->status_label); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($order->created_at->format('H:i')); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('tenant.path.delivery.show', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order])); ?>"
                                               class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                                <i class="ri-eye-line ri-20px"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/dashboard/index.blade.php ENDPATH**/ ?>