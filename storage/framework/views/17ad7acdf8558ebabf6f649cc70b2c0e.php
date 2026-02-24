<?php $__env->startSection('title', 'Nuevo Pedido Delivery'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="mb-1">Nuevo Pedido</h1>
    <p class="text-muted">Crea un pedido de delivery o para llevar</p>
</div>

<form action="<?php echo e(route('tenant.path.delivery.store', ['tenant' => request()->route('tenant')])); ?>" method="POST" id="deliveryForm">
    <?php echo csrf_field(); ?>

    <div class="row">
        <!-- Información del pedido -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tipo de Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="typeDelivery">
                                    <input class="form-check-input" type="radio" name="type" value="delivery" id="typeDelivery" checked>
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0"><i class="ri ri-e-bike-2-line me-2"></i>Delivery</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Entrega a domicilio</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="typeTakeaway">
                                    <input class="form-check-input" type="radio" name="type" value="takeaway" id="typeTakeaway">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0"><i class="ri ri-shopping-bag-line me-2"></i>Para Llevar</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Retiro en local</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" name="customer_name" class="form-control <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('customer_name')); ?>" required>
                            <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono *</label>
                            <input type="tel" name="customer_phone" class="form-control <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('customer_phone')); ?>" required>
                            <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email (opcional)</label>
                            <input type="email" name="customer_email" class="form-control <?php $__errorArgs = ['customer_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('customer_email')); ?>">
                            <?php $__errorArgs = ['customer_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4" id="deliveryInfo">
                <div class="card-header">
                    <h5 class="mb-0">Información de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Dirección de Entrega *</label>
                            <textarea name="delivery_address" class="form-control <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      rows="2"><?php echo e(old('delivery_address')); ?></textarea>
                            <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Zona</label>
                            <input type="text" name="delivery_zone" class="form-control" value="<?php echo e(old('delivery_zone')); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Costo de Envío</label>
                            <input type="number" name="delivery_fee" class="form-control" value="<?php echo e(old('delivery_fee', 0)); ?>"
                                   step="0.01" min="0" id="deliveryFee">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Productos</h5>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addProduct()">
                        <i class="ri ri-add-line me-1"></i> Agregar Producto
                    </button>
                </div>
                <div class="card-body">
                    <div id="productsContainer">
                        <!-- Los productos se agregarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-header">
                    <h5 class="mb-0">Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="subtotalDisplay">$0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2" id="deliveryFeeRow">
                        <span>Envío:</span>
                        <strong id="deliveryFeeDisplay">$0.00</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0" id="totalDisplay">$0.00</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas del Pedido</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Instrucciones especiales..."><?php echo e(old('notes')); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-check-line me-1"></i> Crear Pedido
                    </button>
                    <a href="<?php echo e(route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-secondary w-100 mt-2">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
let productIndex = 0;
const products = <?php echo json_encode($products, 15, 512) ?>;

// Mostrar/ocultar información de delivery
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const deliveryInfo = document.getElementById('deliveryInfo');
        const deliveryFeeRow = document.getElementById('deliveryFeeRow');
        const deliveryFeeInput = document.getElementById('deliveryFee');

        if (this.value === 'delivery') {
            deliveryInfo.style.display = 'block';
            deliveryFeeRow.style.display = 'flex';
            deliveryInfo.querySelectorAll('input, textarea').forEach(el => {
                if (el.name === 'delivery_address') el.required = true;
            });
        } else {
            deliveryInfo.style.display = 'none';
            deliveryFeeRow.style.display = 'none';
            deliveryFeeInput.value = 0;
            deliveryInfo.querySelectorAll('input, textarea').forEach(el => {
                el.required = false;
            });
            calculateTotal();
        }
    });
});

// Agregar producto
function addProduct() {
    const container = document.getElementById('productsContainer');
    const productHtml = `
        <div class="product-item border rounded p-3 mb-3" data-index="${productIndex}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="mb-0">Producto ${productIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-icon btn-text-danger" onclick="removeProduct(${productIndex})">
                    <i class="ri ri-close-line"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Producto *</label>
                    <select name="items[${productIndex}][product_id]" class="form-select product-select" required onchange="updatePrice(${productIndex})">
                        <option value="">Seleccionar...</option>
                        ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} - $${parseFloat(p.price).toFixed(2)}</option>`).join('')}
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Cantidad *</label>
                    <input type="number" name="items[${productIndex}][quantity]" class="form-control quantity-input"
                           value="1" min="1" required onchange="calculateTotal()">
                </div>
                <div class="col-6">
                    <label class="form-label">Precio</label>
                    <input type="text" class="form-control price-display" readonly value="$0.00">
                </div>
                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <input type="text" name="items[${productIndex}][notes]" class="form-control" placeholder="Sin cebolla, extra queso...">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', productHtml);
    productIndex++;
}

// Remover producto
function removeProduct(index) {
    document.querySelector(`[data-index="${index}"]`).remove();
    calculateTotal();
}

// Actualizar precio al seleccionar producto
function updatePrice(index) {
    const item = document.querySelector(`[data-index="${index}"]`);
    const select = item.querySelector('.product-select');
    const priceDisplay = item.querySelector('.price-display');
    const selectedOption = select.options[select.selectedIndex];

    if (selectedOption.value) {
        const price = parseFloat(selectedOption.dataset.price);
        priceDisplay.value = `$${price.toFixed(2)}`;
    } else {
        priceDisplay.value = '$0.00';
    }

    calculateTotal();
}

// Calcular total
function calculateTotal() {
    let subtotal = 0;

    document.querySelectorAll('.product-item').forEach(item => {
        const select = item.querySelector('.product-select');
        const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            subtotal += price * quantity;
        }
    });

    const deliveryFee = parseFloat(document.getElementById('deliveryFee').value) || 0;
    const total = subtotal + deliveryFee;

    document.getElementById('subtotalDisplay').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('deliveryFeeDisplay').textContent = `$${deliveryFee.toFixed(2)}`;
    document.getElementById('totalDisplay').textContent = `$${total.toFixed(2)}`;
}

// Escuchar cambios en el costo de envío
document.getElementById('deliveryFee').addEventListener('input', calculateTotal);

// Agregar primer producto al cargar
addProduct();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/delivery/create.blade.php ENDPATH**/ ?>