<?php $__env->startSection('title', 'Estadísticas'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Estadísticas de Ventas</h1>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('tenant.path.statistics.index', ['tenant' => request()->route('tenant')])); ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Período</label>
                    <select name="period" class="form-select" id="periodSelect">
                        <option value="today" <?php echo e($period === 'today' ? 'selected' : ''); ?>>Hoy</option>
                        <option value="yesterday" <?php echo e($period === 'yesterday' ? 'selected' : ''); ?>>Ayer</option>
                        <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>Esta Semana</option>
                        <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>Este Mes</option>
                        <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>Este Año</option>
                        <option value="custom" <?php echo e($period === 'custom' ? 'selected' : ''); ?>>Personalizado</option>
                    </select>
                </div>
                <div class="col-md-3" id="customDates" style="display: <?php echo e($period === 'custom' ? 'block' : 'none'); ?>;">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo e($start->format('Y-m-d')); ?>">
                </div>
                <div class="col-md-3" id="customDatesEnd" style="display: <?php echo e($period === 'custom' ? 'block' : 'none'); ?>;">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo e($end->format('Y-m-d')); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-search-line me-1"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ingresos Totales</p>
                        <h3 class="mb-0"><?php echo \App\Helpers\Helpers::formatPrice($stats['total_revenue']); ?></h3>
                        <?php if(isset($comparison['revenue_change'])): ?>
                            <small class="text-<?php echo e($comparison['revenue_change'] >= 0 ? 'success' : 'danger'); ?>">
                                <i class="ri ri-arrow-<?php echo e($comparison['revenue_change'] >= 0 ? 'up' : 'down'); ?>-line"></i>
                                <?php echo e(number_format(abs($comparison['revenue_change']), 1)); ?>%
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="avatar avatar-lg bg-label-success">
                        <i class="ri ri-money-dollar-circle-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Órdenes</p>
                        <h3 class="mb-0"><?php echo e($stats['total_orders']); ?></h3>
                        <?php if(isset($comparison['orders_change'])): ?>
                            <small class="text-<?php echo e($comparison['orders_change'] >= 0 ? 'success' : 'danger'); ?>">
                                <i class="ri ri-arrow-<?php echo e($comparison['orders_change'] >= 0 ? 'up' : 'down'); ?>-line"></i>
                                <?php echo e(number_format(abs($comparison['orders_change']), 1)); ?>%
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="avatar avatar-lg bg-label-primary">
                        <i class="ri ri-file-list-3-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ticket Promedio</p>
                        <h3 class="mb-0"><?php echo \App\Helpers\Helpers::formatPrice($stats['average_ticket']); ?></h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-info">
                        <i class="ri ri-receipt-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Items Vendidos</p>
                        <h3 class="mb-0"><?php echo e($stats['total_items_sold']); ?></h3>
                    </div>
                    <div class="avatar avatar-lg bg-label-warning">
                        <i class="ri ri-shopping-cart-line ri-26px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Desglose por Tipo -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Órdenes en Mesas</p>
                        <h4 class="mb-0"><?php echo e($stats['table_orders']); ?></h4>
                    </div>
                    <div class="avatar bg-label-primary">
                        <i class="ri ri-table-line ri-22px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Órdenes Delivery</p>
                        <h4 class="mb-0"><?php echo e($stats['delivery_orders']); ?></h4>
                    </div>
                    <div class="avatar bg-label-info">
                        <i class="ri ri-e-bike-2-line ri-22px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Ventas por Día -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ventas por Día</h5>
            </div>
            <div class="card-body">
                <?php if(count($salesByDay) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th class="text-end">Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $salesByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($day['label']); ?></td>
                                        <td class="text-end">
                                            <strong><?php echo \App\Helpers\Helpers::formatPrice($day['revenue']); ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th>Total</th>
                                    <th class="text-end"><?php echo \App\Helpers\Helpers::formatPrice(collect($salesByDay)->sum('revenue')); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Sin datos para mostrar</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Horarios Pico -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Horarios Pico</h5>
            </div>
            <div class="card-body">
                <?php if($peakHours->isNotEmpty()): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $peakHours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0"><?php echo e(str_pad($hour['hour'], 2, '0', STR_PAD_LEFT)); ?>:00</h6>
                                        <small class="text-muted"><?php echo \App\Helpers\Helpers::formatPrice($hour['revenue']); ?></small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo e($hour['orders']); ?> órdenes
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Sin datos</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Productos Más Vendidos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos Más Vendidos</h5>
            </div>
            <div class="card-body">
                <?php if($topProducts->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th class="text-end">Vendidos</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-label-primary"><?php echo e($index + 1); ?></span>
                                        </td>
                                        <td><strong><?php echo e($product->name); ?></strong></td>
                                        <td><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></td>
                                        <td class="text-end"><?php echo e($product->total_sold); ?></td>
                                        <td class="text-end">
                                            <strong><?php echo \App\Helpers\Helpers::formatPrice($product->price * $product->total_sold); ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Sin datos</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Productos Menos Vendidos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos con Menor Rotación</h5>
            </div>
            <div class="card-body">
                <?php if($lowProducts->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th class="text-end">Vendidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $lowProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($product->name); ?></strong></td>
                                        <td><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-label-warning"><?php echo e($product->total_sold); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Sin datos</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Ventas por Categoría -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ventas por Categoría</h5>
            </div>
            <div class="card-body">
                <?php if($salesByCategory->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-end">Ingresos</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalCategoryRevenue = $salesByCategory->sum('total_revenue'); ?>
                                <?php $__currentLoopData = $salesByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($category->name); ?></strong></td>
                                        <td class="text-end"><?php echo \App\Helpers\Helpers::formatPrice($category->total_revenue); ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-label-primary">
                                                <?php echo e($totalCategoryRevenue > 0 ? number_format(($category->total_revenue / $totalCategoryRevenue) * 100, 1) : 0); ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Sin datos</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Métodos de Pago -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Métodos de Pago</h5>
            </div>
            <div class="card-body">
                <?php if($paymentMethods->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th class="text-end">Transacciones</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e(ucfirst($method->payment_method)); ?></strong>
                                        </td>
                                        <td class="text-end"><?php echo e($method->count); ?></td>
                                        <td class="text-end">
                                            <strong><?php echo \App\Helpers\Helpers::formatPrice($method->total); ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Sin datos</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('periodSelect');
    const customDates = document.getElementById('customDates');
    const customDatesEnd = document.getElementById('customDatesEnd');

    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDates.style.display = 'block';
            customDatesEnd.style.display = 'block';
        } else {
            customDates.style.display = 'none';
            customDatesEnd.style.display = 'none';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/statistics/index.blade.php ENDPATH**/ ?>