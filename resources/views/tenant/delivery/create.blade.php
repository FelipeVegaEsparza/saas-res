@extends('tenant.layouts.admin')

@section('title', 'Nuevo Pedido Delivery')

@section('page-style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 customization */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 10px;
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <h1 class="mb-1">Nuevo Pedido</h1>
    <p class="text-muted">Crea un pedido de delivery o para llevar</p>
</div>

<form action="{{ route('tenant.path.delivery.store', ['tenant' => request()->route('tenant')]) }}" method="POST" id="deliveryForm">
    @csrf

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
                        <div class="col-12">
                            <label class="form-label">Cliente (Opcional)</label>
                            <select id="customerSelect" class="form-select" name="customer_id">
                                <option value="">Cliente nuevo</option>
                            </select>
                            <small class="form-text text-muted">Selecciona un cliente existente o deja vacío para crear uno nuevo</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" name="customer_name" id="customerName" class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono *</label>
                            <input type="tel" name="customer_phone" id="customerPhone" class="form-control @error('customer_phone') is-invalid @enderror"
                                   value="{{ old('customer_phone') }}" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email (opcional)</label>
                            <input type="email" name="customer_email" id="customerEmail" class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email') }}">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror"
                                      rows="2">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Zona</label>
                            <input type="text" name="delivery_zone" class="form-control" value="{{ old('delivery_zone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Costo de Envío</label>
                            <input type="number" name="delivery_fee" class="form-control" value="{{ old('delivery_fee', 0) }}"
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
                        <textarea name="notes" class="form-control" rows="3" placeholder="Instrucciones especiales...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri ri-check-line me-1"></i> Crear Pedido
                    </button>
                    <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary w-100 mt-2">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let productIndex = 0;
const products = @json($products);

// Inicializar selector de clientes
document.addEventListener('DOMContentLoaded', function() {
    initCustomerSelect();
});

function initCustomerSelect() {
    const customerSelect = document.getElementById('customerSelect');
    if (!customerSelect) return;

    // Convertir a select2 para búsqueda
    $(customerSelect).select2({
        placeholder: 'Buscar cliente existente...',
        allowClear: true,
        ajax: {
            url: '{{ route("tenant.path.customers.search", ["tenant" => request()->route("tenant")]) }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results.map(function(customer) {
                        return {
                            id: customer.id,
                            text: customer.text,
                            name: customer.name,
                            phone: customer.phone,
                            email: customer.email || '',
                            credit_available: customer.credit_available,
                            credit_limit: customer.credit_limit
                        };
                    })
                };
            },
            cache: true
        },
        templateResult: function(customer) {
            if (customer.loading) return customer.text;

            if (!customer.name) return $('<span>' + customer.text + '</span>');

            var $container = $(
                '<div class="d-flex justify-content-between align-items-center">' +
                    '<div>' +
                        '<div class="fw-medium">' + customer.name + '</div>' +
                        '<small class="text-muted">' + (customer.phone || 'Sin teléfono') + '</small>' +
                    '</div>' +
                    '<div class="text-end">' +
                        '<small class="text-success">Crédito: $' + Math.round(customer.credit_available).toLocaleString('es-CL') + '</small>' +
                    '</div>' +
                '</div>'
            );

            return $container;
        },
        templateSelection: function(customer) {
            if (!customer.name) return customer.text;
            return customer.name + (customer.phone ? ' (' + customer.phone + ')' : '');
        }
    });

    // Manejar selección de cliente
    $(customerSelect).on('select2:select', function (e) {
        const customer = e.params.data;
        if (customer.name) {
            document.getElementById('customerName').value = customer.name;
            document.getElementById('customerPhone').value = customer.phone || '';
            document.getElementById('customerEmail').value = customer.email || '';

            // Hacer campos readonly cuando se selecciona un cliente
            document.getElementById('customerName').readOnly = true;
            document.getElementById('customerPhone').readOnly = true;
            document.getElementById('customerEmail').readOnly = true;
        }
    });

    // Manejar deselección de cliente
    $(customerSelect).on('select2:clear', function (e) {
        document.getElementById('customerName').value = '';
        document.getElementById('customerPhone').value = '';
        document.getElementById('customerEmail').value = '';

        // Hacer campos editables cuando se deselecciona
        document.getElementById('customerName').readOnly = false;
        document.getElementById('customerPhone').readOnly = false;
        document.getElementById('customerEmail').readOnly = false;
    });
}

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
@endsection
