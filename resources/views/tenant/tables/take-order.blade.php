@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('tenant.layouts.admin')

@section('title', 'Tomar Pedido - Mesa ' . $table->number)

@section('page-style')
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
        height: 200px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .product-image {
        width: 100%;
        height: 100px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .product-placeholder {
        width: 100%;
        height: 100px;
        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .product-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 10px;
    }
    .order-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<!-- Header con info de la mesa -->
<div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div>
                        <h3 class="mb-0">
                            <i class="ri ri-table-line me-2"></i>Mesa {{ $table->number }}
                        </h3>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <small class="opacity-75">Capacidad:</small>
                        <strong class="ms-1">{{ $table->capacity }} personas</strong>
                    </div>
                    @if($table->location)
                        <div class="vr"></div>
                        <div>
                            <small class="opacity-75">Ubicación:</small>
                            <strong class="ms-1">{{ $table->location }}</strong>
                        </div>
                    @endif
                    @if($order)
                        <div class="vr"></div>
                        <div>
                            <span class="badge bg-warning">
                                <i class="ri ri-restaurant-line me-1"></i>Pedido en curso
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('tenant.path.tables.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-light btn-sm">
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
                                <div class="card product-card" onclick="addToOrder({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                                    @else
                                        <div class="product-placeholder">
                                            <i class="ri ri-restaurant-line ri-2x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="product-info">
                                        <div class="mb-2">
                                            <h6 class="mb-1" style="font-size: 0.95rem; line-height: 1.3;">{{ $product->name }}</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $product->category->name }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <span class="h5 mb-0 text-primary" style="font-size: 1.1rem;">@price($product->price)</span>
                                            <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); addToOrder({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
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

    <!-- Pedido -->
    <div class="col-lg-4">
        <div class="card order-container">
            <div class="card-header">
                <h5 class="mb-0">Pedido Actual</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <!-- Items existentes del pedido -->
                @if($order && $order->items->count() > 0)
                    <div class="alert alert-info mb-3">
                        <i class="ri ri-information-line me-2"></i>
                        <strong>Pedido en curso:</strong> Puedes agregar más items
                    </div>
                @endif

                <div class="order-items mb-3" id="orderItems">
                    @if($order && $order->items->count() > 0)
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <small class="text-muted">@price($item->price) c/u</small>
                                    </div>
                                    <span class="badge bg-label-secondary">Ya enviado</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Cantidad: {{ $item->quantity }}</span>
                                    <strong>@price($item->subtotal)</strong>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                    @endif

                    <div id="newItems">
                        @if(!$order || $order->items->count() === 0)
                            <div class="text-center text-muted py-5">
                                <i class="ri ri-restaurant-line ri-3x mb-2"></i>
                                <p>Selecciona productos del menú</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Totales -->
                <div class="border-top pt-3 mt-auto">
                    @if($order)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal anterior:</span>
                            <strong>@price($order->subtotal)</strong>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Nuevos items:</span>
                        <strong id="newSubtotal">$0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0" id="total">@price($order ? $order->total : 0)</h5>
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
@endsection

@section('page-script')
<script>
let newItems = [];
const existingTotal = {{ $order ? $order->total : 0 }};

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
        if (!{{ $order ? 'true' : 'false' }}) {
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
        const response = await fetch('{{ route("tenant.path.tables.storeOrder", ["tenant" => request()->route("tenant"), "table_id" => $table->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                window.location.href = '{{ route("tenant.path.tables.index", ["tenant" => request()->route("tenant")]) }}';
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
@endsection
