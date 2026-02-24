<?php $__env->startSection('title', 'Control de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Control de Stock</h1>
            <p class="text-muted">Gestiona el inventario de productos</p>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Productos con Stock</p>
                        <h3 class="mb-0"><?php echo e($stats['total_products']); ?></h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri ri-archive-line ri-24px"></i>
                        </span>
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
                        <p class="text-muted mb-1">Stock Bajo</p>
                        <h3 class="mb-0"><?php echo e($stats['low_stock']); ?></h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri ri-alert-line ri-24px"></i>
                        </span>
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
                        <p class="text-muted mb-1">Sin Stock</p>
                        <h3 class="mb-0"><?php echo e($stats['out_of_stock']); ?></h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ri ri-close-circle-line ri-24px"></i>
                        </span>
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
                        <p class="text-muted mb-1">Valor Total</p>
                        <h3 class="mb-0"><?php echo \App\Helpers\Helpers::formatPrice($stats['total_value']); ?></h3>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri ri-money-dollar-circle-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Lista -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Inventario</h5>
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('tenant.path.stock.index', ['tenant' => request()->route('tenant')])); ?>"
               class="btn btn-sm <?php echo e($filter === 'all' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                Todos
            </a>
            <a href="<?php echo e(route('tenant.path.stock.index', ['tenant' => request()->route('tenant'), 'filter' => 'low'])); ?>"
               class="btn btn-sm <?php echo e($filter === 'low' ? 'btn-warning' : 'btn-outline-warning'); ?>">
                Stock Bajo
            </a>
            <a href="<?php echo e(route('tenant.path.stock.index', ['tenant' => request()->route('tenant'), 'filter' => 'out'])); ?>"
               class="btn btn-sm <?php echo e($filter === 'out' ? 'btn-danger' : 'btn-outline-danger'); ?>">
                Sin Stock
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if($products->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock Actual</th>
                            <th class="text-center">Stock Mínimo</th>
                            <th class="text-center">Estado</th>
                            <th>Precio</th>
                            <th>Valor Total</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($product->image): ?>
                                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>"
                                                 alt="<?php echo e($product->name); ?>"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri ri-image-line"></i>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <span class="fw-medium"><?php echo e($product->name); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary"><?php echo e($product->category->name); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold"><?php echo e($product->stock_quantity); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php echo e($product->min_stock); ?>

                                </td>
                                <td class="text-center">
                                    <?php if($product->isOutOfStock()): ?>
                                        <span class="badge bg-danger">Sin Stock</span>
                                    <?php elseif($product->hasLowStock()): ?>
                                        <span class="badge bg-warning">Stock Bajo</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></td>
                                <td class="fw-medium"><?php echo \App\Helpers\Helpers::formatPrice($product->price * $product->stock_quantity); ?></td>
                                <td class="text-end">
                                    <button type="button"
                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStockModal<?php echo e($product->id); ?>">
                                        <i class="ri ri-edit-line"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal para editar stock -->
                            <div class="modal fade" id="editStockModal<?php echo e($product->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Actualizar Stock: <?php echo e($product->name); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?php echo e(route('tenant.path.stock.update', ['tenant' => request()->route('tenant'), 'id' => $product->id])); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Stock Actual</label>
                                                    <input type="number" name="stock_quantity" class="form-control"
                                                           value="<?php echo e($product->stock_quantity); ?>" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Stock Mínimo</label>
                                                    <input type="number" name="min_stock" class="form-control"
                                                           value="<?php echo e($product->min_stock); ?>" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Notas (opcional)</label>
                                                    <textarea name="notes" class="form-control" rows="2"
                                                              placeholder="Ej: Reposición de inventario"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="ri ri-archive-line ri-48px"></i>
                    </span>
                </div>
                <h5 class="mb-1">No hay productos con seguimiento de stock</h5>
                <p class="text-muted mb-4">Activa el seguimiento de stock en los productos que desees controlar</p>
                <a href="<?php echo e(route('tenant.path.products.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary">
                    <i class="ri ri-settings-line me-1"></i> Ir a Productos
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/stock/index.blade.php ENDPATH**/ ?>