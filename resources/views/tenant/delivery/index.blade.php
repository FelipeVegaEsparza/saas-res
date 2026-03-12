@extends('tenant.layouts.admin')

@section('title', 'Pedidos Delivery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Pedidos Delivery</h1>
        <p class="text-muted">Gestiona pedidos de delivery y para llevar</p>
    </div>
    <div class="d-flex gap-2">
        <!-- Botón Compartir Link de Pedidos -->
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="ri ri-share-line me-1"></i> Compartir Link
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#" onclick="shareWhatsApp()">
                        <i class="ri ri-whatsapp-line me-2 text-success"></i> WhatsApp
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="shareFacebook()">
                        <i class="ri ri-facebook-circle-line me-2 text-primary"></i> Facebook
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="shareTwitter()">
                        <i class="ri ri-twitter-x-line me-2 text-dark"></i> X (Twitter)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="shareInstagram()">
                        <i class="ri ri-instagram-line me-2 text-danger"></i> Instagram
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" onclick="copyLink()">
                        <i class="ri ri-file-copy-line me-2 text-secondary"></i> Copiar Link
                    </a>
                </li>
            </ul>
        </div>

        <a href="{{ route('tenant.path.delivery.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
            <i class="ri ri-add-line me-1"></i> Nuevo Pedido
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Número, cliente, teléfono..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select name="type" class="form-select">
                    <option value="">Todos</option>
                    <option value="delivery" {{ request('type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="takeaway" {{ request('type') === 'takeaway' ? 'selected' : '' }}>Para Llevar</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparando</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Listo</option>
                    <option value="on_delivery" {{ request('status') === 'on_delivery' ? 'selected' : '' }}>En Camino</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregado</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="ri ri-search-line me-1"></i> Buscar
                </button>
                <a href="{{ route('tenant.path.delivery.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
                    <i class="ri ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de pedidos -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                                <br><small class="text-muted">{{ $order->items->count() }} items</small>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $order->type === 'delivery' ? 'primary' : 'info' }}">
                                    <i class="ri ri-{{ $order->type === 'delivery' ? 'e-bike-2' : 'shopping-bag' }}-line me-1"></i>
                                    {{ $order->type_label }}
                                </span>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_phone }}</td>
                            <td><strong>@price($order->total)</strong></td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tenant.path.delivery.show', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order]) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="ri ri-eye-line ri-20px"></i>
                                </a>
                                <form action="{{ route('tenant.path.delivery.destroy', ['tenant' => request()->route('tenant'), 'deliveryOrder' => $order]) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            onclick="return confirm('¿Eliminar este pedido?')">
                                        <i class="ri ri-delete-bin-7-line ri-20px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-center">
                                    <i class="ri ri-e-bike-2-line ri-48px text-muted mb-3"></i>
                                    <p class="text-muted">No hay pedidos registrados</p>
                                    <a href="{{ route('tenant.path.delivery.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary mt-2">
                                        <i class="ri ri-add-line me-1"></i> Crear Primer Pedido
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL del link de pedidos online
    const orderUrl = '{{ url("/" . request()->route("tenant") . "/order") }}';
    const restaurantName = '{{ tenant()->restaurant()->name ?? "Nuestro Restaurante" }}';

    window.shareWhatsApp = function() {
        const message = `🍽️ ¡Haz tu pedido online en ${restaurantName}!\n\n📱 Ordena fácil y rápido desde tu celular:\n${orderUrl}\n\n🚚 Delivery y para llevar disponible`;
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }

    window.shareFacebook = function() {
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(orderUrl)}&quote=${encodeURIComponent(`¡Haz tu pedido online en ${restaurantName}! 🍽️`)}`;
        window.open(facebookUrl, '_blank', 'width=600,height=400');
    }

    window.shareTwitter = function() {
        const message = `🍽️ ¡Haz tu pedido online en ${restaurantName}! Delivery y para llevar disponible 🚚`;
        const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(message)}&url=${encodeURIComponent(orderUrl)}`;
        window.open(twitterUrl, '_blank', 'width=600,height=400');
    }

    window.shareInstagram = function() {
        copyLink();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Link copiado',
                text: 'El link se ha copiado al portapapeles. Puedes pegarlo en tu historia o post de Instagram.',
                confirmButtonText: 'Entendido'
            });
        } else {
            alert('Link copiado al portapapeles. Puedes pegarlo en Instagram.');
        }
    }

    window.copyLink = function() {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(orderUrl).then(function() {
                showCopySuccess();
            }).catch(function() {
                fallbackCopyTextToClipboard(orderUrl);
            });
        } else {
            fallbackCopyTextToClipboard(orderUrl);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.position = 'fixed';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showCopySuccess();
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            alert('No se pudo copiar el link automáticamente. URL: ' + text);
        }

        document.body.removeChild(textArea);
    }

    function showCopySuccess() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Link copiado!',
                text: 'El link de pedidos se ha copiado al portapapeles',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert('¡Link copiado al portapapeles!');
        }
    }
});
</script>
@endsection
