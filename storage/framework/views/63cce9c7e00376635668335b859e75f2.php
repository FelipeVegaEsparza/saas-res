<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
?>



<?php $__env->startSection('title', $restaurant->name . ' - Pedidos Online'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
    .mobile-cart-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.15);
        padding: 1rem 0;
        z-index: 1030;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    /* Agregar padding al body cuando la barra está visible */
    body.has-mobile-cart {
        padding-bottom: 80px;
    }

    .product-card {
        transition: transform 0.2s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <?php if($restaurant->logo_horizontal): ?>
            <img src="<?php echo e(Storage::url($restaurant->logo_horizontal)); ?>"
                 alt="<?php echo e($restaurant->name); ?>"
                 style="max-height: 80px; margin-bottom: 1rem;">
            <p class="text-muted">Realiza tu pedido online</p>
        <?php else: ?>
            <h1 class="mb-2"><?php echo e($restaurant->name); ?></h1>
            <p class="text-muted">Realiza tu pedido online</p>
        <?php endif; ?>
    </div>

    <!-- Tipo de Pedido -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Tipo de Pedido</h5>
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="orderType" id="typeDelivery" value="delivery" checked>
                <label class="btn btn-outline-primary" for="typeDelivery">
                    <i class="ri ri-e-bike-2-line me-2"></i>Delivery
                </label>

                <input type="radio" class="btn-check" name="orderType" id="typePickup" value="pickup">
                <label class="btn btn-outline-primary" for="typePickup">
                    <i class="ri ri-shopping-bag-line me-2"></i>Para Llevar
                </label>
            </div>
        </div>
    </div>

    <!-- Menú de Productos -->
    <div class="row">
        <div class="col-lg-8">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($category->activeProducts->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e($category->name); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php $__currentLoopData = $category->activeProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="card h-100 product-card" data-product-id="<?php echo e($product->id); ?>"
                                     data-product-name="<?php echo e($product->name); ?>"
                                     data-product-price="<?php echo e($product->price); ?>"
                                     data-product-image="<?php echo e($product->image ? Storage::url($product->image) : ''); ?>">
                                    <?php if($product->image): ?>
                                    <img src="<?php echo e(Storage::url($product->image)); ?>"
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover;"
                                         alt="<?php echo e($product->name); ?>">
                                    <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="ri ri-image-line ri-48px text-muted"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title mb-2"><?php echo e($product->name); ?></h6>
                                        <?php if($product->description): ?>
                                        <p class="card-text small text-muted mb-3"><?php echo e(Str::limit($product->description, 80)); ?></p>
                                        <?php endif; ?>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0 text-primary"><?php echo \App\Helpers\Helpers::formatPrice($product->price); ?></h5>
                                            </div>
                                            <button class="btn btn-primary w-100 add-to-cart">
                                                <i class="ri ri-shopping-cart-line me-2"></i>Agregar al Pedido
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Carrito -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri ri-shopping-cart-line me-2"></i>Tu Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItems">
                        <p class="text-muted text-center py-4">No hay productos en el carrito</p>
                    </div>

                    <div id="cartSummary" style="display: none;">
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="subtotalAmount">$0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="deliveryFeeRow" style="display: none;">
                            <span>Envío:</span>
                            <strong id="deliveryFeeAmount"><?php echo \App\Helpers\Helpers::formatPrice($restaurant->delivery_fee ?? 0); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary" id="totalAmount">$0</strong>
                        </div>

                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            Continuar con el Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barra flotante inferior para móviles -->
<div class="mobile-cart-bar d-lg-none" id="mobileCartBar" style="display: none !important;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="flex-grow-1 me-3">
                <div class="d-flex align-items-center">
                    <div class="badge bg-primary rounded-circle me-2" id="mobileCartCount" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                        0
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Total</small>
                        <strong id="mobileCartTotal">$0</strong>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="ri ri-shopping-cart-line me-1"></i>Ver Carrito
            </button>
        </div>
    </div>
</div>

<!-- Modal del Carrito (para móviles) -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri ri-shopping-cart-line me-2"></i>Tu Pedido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="mobileCartItems">
                    <p class="text-muted text-center py-4">No hay productos en el carrito</p>
                </div>
            </div>
            <div class="modal-footer flex-column align-items-stretch" id="mobileCartSummary" style="display: none !important;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong id="mobileSubtotalAmount">$0</strong>
                </div>
                <div class="d-flex justify-content-between mb-2" id="mobileDeliveryFeeRow" style="display: none;">
                    <span>Envío:</span>
                    <strong id="mobileDeliveryFeeAmount"><?php echo \App\Helpers\Helpers::formatPrice($restaurant->delivery_fee ?? 0); ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="text-primary" id="mobileTotalAmount">$0</strong>
                </div>
                <button class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    Continuar con el Pedido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Completar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="checkoutForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" name="customer_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono *</label>
                        <input type="tel" class="form-control" name="customer_phone" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (opcional)</label>
                        <input type="email" class="form-control" name="customer_email">
                    </div>

                    <div class="mb-3" id="addressField">
                        <label class="form-label">Dirección de Entrega *</label>
                        <textarea class="form-control" name="delivery_address" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas (opcional)</label>
                        <textarea class="form-control" name="notes" rows="2"
                                  placeholder="Ej: Sin cebolla, extra picante, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-check-line me-1"></i>Confirmar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded-circle bg-label-success">
                        <i class="ri ri-check-line ri-48px"></i>
                    </span>
                </div>
                <h4 class="mb-2">¡Pedido Recibido!</h4>
                <p class="text-muted mb-4">Tu pedido ha sido recibido correctamente. Te contactaremos pronto.</p>
                <p class="mb-4">Número de pedido: <strong id="orderNumber"></strong></p>
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    Hacer Otro Pedido
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('page-script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
let cart = [];
const deliveryFee = <?php echo e($restaurant->delivery_fee ?? 0); ?>;
const currencySymbol = '<?php echo e($restaurant->currency_symbol); ?>';

// Función para formatear precio
function formatPrice(amount) {
    return currencySymbol + Math.round(amount).toLocaleString('es-CL');
}

// Agregar al carrito
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const card = this.closest('.product-card');
        const productId = card.dataset.productId;
        const productName = card.dataset.productName;
        const productPrice = parseFloat(card.dataset.productPrice);
        const productImage = card.dataset.productImage;

        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            });
        }

        updateCart();
    });
});

// Actualizar carrito
function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const cartSummary = document.getElementById('cartSummary');
    const mobileCartBar = document.getElementById('mobileCartBar');
    const mobileCartItems = document.getElementById('mobileCartItems');
    const mobileCartSummary = document.getElementById('mobileCartSummary');

    if (cart.length === 0) {
        cartItemsDiv.innerHTML = '<p class="text-muted text-center py-4">No hay productos en el carrito</p>';
        cartSummary.style.display = 'none';
        mobileCartBar.style.display = 'none';
        mobileCartItems.innerHTML = '<p class="text-muted text-center py-4">No hay productos en el carrito</p>';
        mobileCartSummary.style.display = 'none';
        document.body.classList.remove('has-mobile-cart');
        return;
    }

    let html = '<div class="list-group list-group-flush">';
    let subtotal = 0;

    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        html += `
            <div class="list-group-item px-0">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="flex-grow-1">
                        <h6 class="mb-0">${item.name}</h6>
                        <small class="text-muted">${formatPrice(item.price)}</small>
                    </div>
                    <button class="btn btn-sm btn-icon btn-text-danger remove-item" data-product-id="${item.id}">
                        <i class="ri ri-close-line"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary decrease-qty" data-product-id="${item.id}">-</button>
                        <button class="btn btn-outline-secondary" disabled>${item.quantity}</button>
                        <button class="btn btn-outline-secondary increase-qty" data-product-id="${item.id}">+</button>
                    </div>
                    <strong>${formatPrice(itemTotal)}</strong>
                </div>
            </div>
        `;
    });

    html += '</div>';
    cartItemsDiv.innerHTML = html;
    mobileCartItems.innerHTML = html;
    cartSummary.style.display = 'block';
    mobileCartSummary.style.display = 'flex';

    // Actualizar totales
    const orderType = document.querySelector('input[name="orderType"]:checked').value;
    const delivery = orderType === 'delivery' ? deliveryFee : 0;
    const total = subtotal + delivery;

    // Actualizar carrito desktop
    document.getElementById('subtotalAmount').textContent = formatPrice(subtotal);
    document.getElementById('deliveryFeeAmount').textContent = formatPrice(delivery);
    document.getElementById('totalAmount').textContent = formatPrice(total);
    document.getElementById('deliveryFeeRow').style.display = orderType === 'delivery' ? 'flex' : 'none';

    // Actualizar carrito móvil
    document.getElementById('mobileSubtotalAmount').textContent = formatPrice(subtotal);
    document.getElementById('mobileDeliveryFeeAmount').textContent = formatPrice(delivery);
    document.getElementById('mobileTotalAmount').textContent = formatPrice(total);
    document.getElementById('mobileDeliveryFeeRow').style.display = orderType === 'delivery' ? 'flex' : 'none';

    // Actualizar barra flotante móvil
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('mobileCartCount').textContent = totalItems;
    document.getElementById('mobileCartTotal').textContent = formatPrice(total);
    mobileCartBar.style.display = 'block';
    document.body.classList.add('has-mobile-cart');
}

// Event delegation para botones del carrito
document.addEventListener('click', function(e) {
    // Aumentar cantidad
    if (e.target.closest('.increase-qty')) {
        const productId = e.target.closest('.increase-qty').dataset.productId;
        const item = cart.find(i => i.id === productId);
        if (item) {
            item.quantity++;
            updateCart();
        }
    }

    // Disminuir cantidad
    if (e.target.closest('.decrease-qty')) {
        const productId = e.target.closest('.decrease-qty').dataset.productId;
        const item = cart.find(i => i.id === productId);
        if (item && item.quantity > 1) {
            item.quantity--;
            updateCart();
        }
    }

    // Eliminar item
    if (e.target.closest('.remove-item')) {
        const productId = e.target.closest('.remove-item').dataset.productId;
        cart = cart.filter(i => i.id !== productId);
        updateCart();
    }
});

// Cambio de tipo de pedido
document.querySelectorAll('input[name="orderType"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const addressField = document.getElementById('addressField');
        const addressInput = addressField.querySelector('textarea');

        if (this.value === 'delivery') {
            addressField.style.display = 'block';
            addressInput.required = true;
        } else {
            addressField.style.display = 'none';
            addressInput.required = false;
        }

        updateCart();
    });
});

// Enviar pedido
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const orderType = document.querySelector('input[name="orderType"]:checked').value;

    const data = {
        type: orderType,
        customer_name: formData.get('customer_name'),
        customer_phone: formData.get('customer_phone'),
        customer_email: formData.get('customer_email'),
        delivery_address: formData.get('delivery_address'),
        notes: formData.get('notes'),
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            price: item.price
        }))
    };

    try {
        const response = await fetch('<?php echo e(route("tenant.path.online.store", ["tenant" => request()->route("tenant")])); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            // Cerrar modal de checkout
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();

            // Mostrar modal de éxito
            document.getElementById('orderNumber').textContent = result.order.order_number;
            new bootstrap.Modal(document.getElementById('successModal')).show();

            // Limpiar carrito
            cart = [];
            updateCart();
            this.reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar el pedido. Por favor intenta nuevamente.',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al procesar el pedido. Por favor intenta nuevamente.',
            confirmButtonText: 'Aceptar'
        });
    }
});

}); // End DOMContentLoaded
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/online/index.blade.php ENDPATH**/ ?>