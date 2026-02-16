@extends('tenant.layouts.admin')

@section('title', 'Punto de Venta')

@section('page-style')
<style>
    .pos-container {
        height: calc(100vh - 120px);
        overflow: hidden;
    }
    .products-grid {
        height: calc(100vh - 250px);
        overflow-y: auto;
    }
    .cart-container {
        height: calc(100vh - 120px);
        display: flex;
        flex-direction: column;
    }
    .cart-items {
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
    .cart-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .category-btn {
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="pos-container">
    <div class="row g-0 h-100">
        <!-- Productos -->
        <div class="col-lg-8 pe-lg-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Productos</h5>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchProduct" class="form-control form-control-sm" placeholder="Buscar producto..." style="width: 200px;">
                    </div>
                </div>
                <div class="card-body">
                    <!-- Categorías -->
                    <div class="mb-3 overflow-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary category-btn active" data-category="all">
                                Todos
                            </button>
                            @foreach($categories as $category)
                                <button type="button" class="btn btn-sm btn-outline-primary category-btn" data-category="{{ $category->id }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Grid de Productos -->
                    <div class="products-grid">
                        <div class="row g-3" id="productsContainer">
                            @foreach($products as $product)
                                <div class="col-md-4 col-sm-6 product-item" data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}">
                                    <div class="card product-card" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h6 class="mb-1">{{ $product->name }}</h6>
                                                <small class="text-muted">{{ $product->category->name }}</small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="ri ri-add-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrito -->
        <div class="col-lg-4">
            <div class="card cart-container">
                <div class="card-header">
                    <h5 class="mb-0">Carrito</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="cart-items mb-3" id="cartItems">
                        <div class="text-center text-muted py-5">
                            <i class="ri ri-shopping-cart-line ri-3x mb-2"></i>
                            <p>Carrito vacío</p>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="border-top pt-3 mt-auto">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="subtotal">$0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0" id="total">$0.00</h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Método de Pago</label>
                            <select id="paymentMethod" class="form-select">
                                <option value="cash">Efectivo</option>
                                <option value="card">Tarjeta</option>
                                <option value="transfer">Transferencia</option>
                            </select>
                        </div>

                        <div class="mb-3" id="cashPaymentSection">
                            <label class="form-label small">Monto Recibido</label>
                            <input type="number" id="amountPaid" class="form-control" step="0.01" min="0">
                            <small class="text-muted">Cambio: <span id="change">$0.00</span></small>
                        </div>

                        <button class="btn btn-success w-100 mb-2" onclick="processPayment()" id="btnPay" disabled>
                            <i class="ri ri-check-line me-1"></i> Cobrar
                        </button>
                        <button class="btn btn-outline-danger w-100" onclick="clearCart()">
                            <i class="ri ri-delete-bin-line me-1"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
let cart = [];

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

// Agregar al carrito
function addToCart(id, name, price) {
    const existingItem = cart.find(item => item.id === id);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: id,
            name: name,
            price: parseFloat(price),
            quantity: 1
        });
    }

    updateCart();
}

// Actualizar carrito
function updateCart() {
    const cartContainer = document.getElementById('cartItems');

    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="ri ri-shopping-cart-line ri-3x mb-2"></i>
                <p>Carrito vacío</p>
            </div>
        `;
        document.getElementById('btnPay').disabled = true;
    } else {
        let html = '';
        cart.forEach((item, index) => {
            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <small class="text-muted">$${item.price.toFixed(2)} c/u</small>
                        </div>
                        <button class="btn btn-sm btn-icon btn-text-danger" onclick="removeFromCart(${index})">
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
                        <strong>$${(item.price * item.quantity).toFixed(2)}</strong>
                    </div>
                </div>
            `;
        });
        cartContainer.innerHTML = html;
        document.getElementById('btnPay').disabled = false;
    }

    updateTotals();
}

// Aumentar cantidad
function increaseQuantity(index) {
    cart[index].quantity++;
    updateCart();
}

// Disminuir cantidad
function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
        updateCart();
    }
}

// Remover del carrito
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Limpiar carrito
function clearCart() {
    if (confirm('¿Estás seguro de limpiar el carrito?')) {
        cart = [];
        updateCart();
    }
}

// Actualizar totales
function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '$' + subtotal.toFixed(2);

    calculateChange();
}

// Calcular cambio
document.getElementById('amountPaid').addEventListener('input', calculateChange);

function calculateChange() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change = amountPaid - total;

    document.getElementById('change').textContent = '$' + Math.max(0, change).toFixed(2);
}

// Mostrar/ocultar sección de efectivo
document.getElementById('paymentMethod').addEventListener('change', function() {
    const cashSection = document.getElementById('cashPaymentSection');
    if (this.value === 'cash') {
        cashSection.style.display = 'block';
    } else {
        cashSection.style.display = 'none';
        document.getElementById('amountPaid').value = '';
    }
});

// Procesar pago
async function processPayment() {
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }

    const paymentMethod = document.getElementById('paymentMethod').value;
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let amountPaid = total;

    if (paymentMethod === 'cash') {
        amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
        if (amountPaid < total) {
            alert('El monto recibido es menor al total');
            return;
        }
    }

    const data = {
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            price: item.price
        })),
        payment_method: paymentMethod,
        amount_paid: amountPaid
    };

    try {
        const response = await fetch('{{ route("tenant.path.cash.payment", ["tenant" => request()->route("tenant")]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert('Venta registrada exitosamente');
            cart = [];
            updateCart();
            document.getElementById('amountPaid').value = '';
        } else {
            alert('Error: ' + (result.error || 'Error desconocido'));
        }
    } catch (error) {
        alert('Error al procesar el pago: ' + error.message);
    }
}
</script>
@endsection
