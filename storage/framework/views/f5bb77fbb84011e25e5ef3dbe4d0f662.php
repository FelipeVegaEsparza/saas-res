<?php $__env->startSection('title', 'Tomar Pedido - Mesa ' . $table->number); ?>

<?php $__env->startSection('page-style'); ?>
<style>
    .products-grid {
        height: calc(100vh - 350px);
        overflow-y: auto;
    }
    .order-container {
        height: calc(100vh - 180px);
        display: flex;
        flex-direction: column;
    }
    .order-items {
        flex: 1;
        overflow-y: auto;
        max-height: calc(100vh - 450px);
    }
    .product-card {
        cursor: pointer;
        transition: all 0.2s;
        height: 140px;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .order-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
</style>
<?php $__env->stopSection(); ?>

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
                        <small class="opacity-75">Capacidad:</small>
                        <strong class="ms-1"><?php echo e($table->capacity); ?> personas</strong>
                    </div>
                    <?php if($table->location): ?>
                        <div class="vr"></div>
                        <div>
                            <small class="opacity-75">Ubicación:</small>
                            <strong class="ms-1"><?php echo e($table->location); ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if($order): ?>
                        <div class="vr"></div>
                        <div>
                            <span class="badge bg-warning">
                                <i class="ri ri-restaurant-line me-1"></i>Pedido en curso
                            </span>
                        </div>
                    <?php endif; ?>
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

<div class="row g-3">
    <!-- Productos -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Menú</h5>
                <input type="text" id="searchProduct" class="form-control form-control-sm" placeholder="Buscar producto..." style="width: 250px;">
            </div>
            <div class="card-body">
                <!-- Categorías -->
                <div class="mb-3 overflow-auto">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary category-btn active" data-category="all">
                            <i class="ri ri-apps-line me-1"></i> Todos
                        </button>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" class="btn btn-sm btn-outline-primary category-btn" data-category="<?php echo e($category->id); ?>">
                                <?php echo e($category->name); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Grid de Productos -->
                <div class="products-grid">
                    <div class="row g-3" id="productsContainer">
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-sm-6 product-item" data-category="<?php echo e($product->category_id); ?>" data-name="<?php echo e(strtolower($product->name)); ?>">
                                <div class="card product-card" onclick="addToOrder(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', <?php echo e($product->price); ?>)">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($product->name); ?></h6>
                                            <small class="text-muted"><?php echo e($product->category->name); ?></small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="h5 mb-0 text-primary"><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></span>
                                            <button class="btn btn-sm btn-primary">
                                                <i class="ri ri-add-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedido -->
    <div class="col-lg-4">
        <div class="card order-container">
            <div class="card-header">
                <h5 class="mb-0">Pedido Actual</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <!-- Items existentes del pedido -->
                <?php if($order && $order->items->count() > 0): ?>
                    <div class="alert alert-info mb-3">
                        <i class="ri ri-information-line me-2"></i>
                        <strong>Pedido en curso:</strong> Puedes agregar más items
                    </div>
                <?php endif; ?>

                <div class="order-items mb-3" id="orderItems">
                    <?php if($order && $order->items->count() > 0): ?>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="order-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo e($item->product->name); ?></h6>
                                        <small class="text-muted"><?php echo \App\Helpers\Helpers::formatPrice($item->price); ?> c/u</small>
                                    </div>
                                    <span class="badge bg-label-secondary">Ya enviado</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Cantidad: <?php echo e($item->quantity); ?></span>
                                    <strong><?php echo \App\Helpers\Helpers::formatPrice($item->subtotal); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <hr>
                    <?php endif; ?>

                    <div id="newItems">
                        <?php if(!$order || $order->items->count() === 0): ?>
                            <div class="text-center text-muted py-5">
                                <i class="ri ri-restaurant-line ri-3x mb-2"></i>
                                <p>Selecciona productos del menú</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Totales -->
                <div class="border-top pt-3 mt-auto">
                    <?php if($order): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal anterior:</span>
                            <strong><?php echo \App\Helpers\Helpers::formatPrice($order->subtotal); ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Nuevos items:</span>
                        <strong id="newSubtotal">$0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0" id="total"><?php echo \App\Helpers\Helpers::formatPrice($order ? $order->total : 0); ?></h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Notas para cocina</label>
                        <textarea id="kitchenNotes" class="form-control" rows="2" placeholder="Ej: Sin cebolla, término medio..."></textarea>
                    </div>

                    <button class="btn btn-success w-100 mb-2" onclick="sendOrder()" id="btnSend" disabled>
                        <i class="ri ri-send-plane-line me-1"></i> Enviar a Cocina
                    </button>
                    <button class="btn btn-outline-danger w-100" onclick="clearNewItems()">
                        <i class="ri ri-delete-bin-line me-1"></i> Limpiar Nuevos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
let newItems = [];
const existingTotal = <?php echo e($order ? $order->total : 0); ?>;

// Función para formatear precios sin decimales y con separadores de miles
function formatPrice(amount) {
    return '$' + Math.round(amount).toLocaleString('es-CL');
}

// Filtrar por categoría
document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const category = this.dataset.category;
        document.querySelectorAll('.product-item').forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Buscar producto
document.getElementById('searchProduct').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        const name = item.dataset.name;
        if (name.includes(search)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Agregar al pedido
function addToOrder(id, name, price) {
    const existingItem = newItems.find(item => item.id === id);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        newItems.push({
            id: id,
            name: name,
            price: parseFloat(price),
            quantity: 1
        });
    }

    updateOrder();
}

// Actualizar pedido
function updateOrder() {
    const container = document.getElementById('newItems');

    if (newItems.length === 0) {
        if (!<?php echo e($order ? 'true' : 'false'); ?>) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="ri ri-restaurant-line ri-3x mb-2"></i>
                    <p>Selecciona productos del menú</p>
                </div>
            `;
        } else {
            container.innerHTML = '';
        }
        document.getElementById('btnSend').disabled = true;
    } else {
        let html = '';
        newItems.forEach((item, index) => {
            html += `
                <div class="order-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <small class="text-muted">${formatPrice(item.price)} c/u</small>
                        </div>
                        <button class="btn btn-sm btn-icon btn-text-danger" onclick="removeFromOrder(${index})">
                            <i class="ri ri-close-line"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary" onclick="decreaseQuantity(${index})">
                                <i class="ri ri-subtract-line"></i>
                            </button>
                            <button class="btn btn-outline-secondary" disabled>${item.quantity}</button>
                            <button class="btn btn-outline-secondary" onclick="increaseQuantity(${index})">
                                <i class="ri ri-add-line"></i>
                            </button>
                        </div>
                        <strong>${formatPrice(item.price * item.quantity)}</strong>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
        document.getElementById('btnSend').disabled = false;
    }

    updateTotals();
}

// Aumentar cantidad
function increaseQuantity(index) {
    newItems[index].quantity++;
    updateOrder();
}

// Disminuir cantidad
function decreaseQuantity(index) {
    if (newItems[index].quantity > 1) {
        newItems[index].quantity--;
        updateOrder();
    }
}

// Remover del pedido
function removeFromOrder(index) {
    newItems.splice(index, 1);
    updateOrder();
}

// Limpiar nuevos items
function clearNewItems() {
    if (newItems.length > 0) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Se eliminarán todos los nuevos items del pedido',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                newItems = [];
                updateOrder();
            }
        });
    }
}

// Actualizar totales
function updateTotals() {
    const newSubtotal = newItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = existingTotal + newSubtotal;

    document.getElementById('newSubtotal').textContent = formatPrice(newSubtotal);
    document.getElementById('total').textContent = formatPrice(total);
}

// Enviar pedido
async function sendOrder() {
    if (newItems.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Agrega al menos un producto al pedido',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
        return;
    }

    const kitchenNotes = document.getElementById('kitchenNotes').value;

    const data = {
        items: newItems.map(item => ({
            product_id: item.id,
            quantity: item.quantity
        })),
        kitchen_notes: kitchenNotes
    };

    try {
        const response = await fetch('<?php echo e(route("tenant.path.tables.storeOrder", ["tenant" => request()->route("tenant"), "table_id" => $table->id])); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Pedido enviado a cocina exitosamente',
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
            }).then(() => {
                window.location.href = '<?php echo e(route("tenant.path.tables.index", ["tenant" => request()->route("tenant")])); ?>';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'Error desconocido',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        }
    } catch (error) {
        console.error('Error completo:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al enviar el pedido: ' + error.message,
            customClass: {
                confirmButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/tables/take-order.blade.php ENDPATH**/ ?>