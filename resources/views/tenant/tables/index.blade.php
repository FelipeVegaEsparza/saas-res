@extends('tenant.layouts.admin')

@section('title', 'Mesas')

@section('page-style')
<style>
    .restaurant-map {
        position: relative;
        width: 100%;
        height: calc(100vh - 250px);
        min-height: 600px;
        background: var(--bs-body-bg);
        border: 2px dashed var(--bs-border-color);
        border-radius: 8px;
        overflow: hidden;
    }

    .map-stats {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 100;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .stat-badge {
        background: var(--bs-card-bg);
        padding: 8px 16px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        border: 1px solid var(--bs-border-color);
    }

    .stat-badge i {
        font-size: 1.2rem;
    }

    .stat-badge.available {
        border-left: 3px solid var(--bs-success);
    }

    .stat-badge.occupied {
        border-left: 3px solid var(--bs-danger);
    }

    .stat-badge.reserved {
        border-left: 3px solid var(--bs-warning);
    }

    .stat-badge.total {
        border-left: 3px solid var(--bs-primary);
    }

    .table-item {
        position: absolute;
        width: 100px;
        height: 100px;
        cursor: pointer;
        user-select: none;
        transition: box-shadow 0.2s;
    }

    /* Tamaños */
    .table-item.size-small {
        width: 80px;
        height: 80px;
    }

    .table-item.size-medium {
        width: 100px;
        height: 100px;
    }

    .table-item.size-large {
        width: 120px;
        height: 120px;
    }

    /* Formas rectangulares */
    .table-item.rectangle.size-small {
        width: 110px;
        height: 65px;
    }

    .table-item.rectangle.size-medium {
        width: 140px;
        height: 80px;
    }

    .table-item.rectangle.size-large {
        width: 170px;
        height: 95px;
    }

    /* Rectangulares verticales */
    .table-item.rectangle.vertical.size-small {
        width: 65px;
        height: 110px;
    }

    .table-item.rectangle.vertical.size-medium {
        width: 80px;
        height: 140px;
    }

    .table-item.rectangle.vertical.size-large {
        width: 95px;
        height: 170px;
    }

    /* Forma redonda */
    .table-item.round {
        border-radius: 50%;
        overflow: hidden;
    }

    .table-item.round .table-visual-map {
        border-radius: 0;
        height: 60%;
    }

    .table-item.round .table-info-map {
        border-radius: 0;
        height: 40%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .table-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10;
    }

    .table-item.dragging {
        opacity: 0.7;
        z-index: 1000;
    }

    .table-visual-map {
        width: 100%;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 8px 0 0;
        position: relative;
    }

    .table-visual-map::before {
        content: '';
        position: absolute;
        width: 70%;
        height: 60%;
        border: 2px solid currentColor;
        border-radius: 6px;
        opacity: 0.3;
    }

    .table-visual-map.shape-round::before {
        border-radius: 50%;
        width: 60%;
        height: 80%;
    }

    .table-visual-map.shape-rectangle::before {
        width: 80%;
        height: 50%;
    }

    .table-visual-map.available {
        background: rgba(var(--bs-success-rgb), 0.15);
        color: var(--bs-success);
    }

    .table-visual-map.occupied {
        background: rgba(var(--bs-danger-rgb), 0.15);
        color: var(--bs-danger);
    }

    .table-visual-map.reserved {
        background: rgba(var(--bs-warning-rgb), 0.15);
        color: var(--bs-warning);
    }

    .table-info-map {
        background: var(--bs-card-bg);
        padding: 8px;
        text-align: center;
        border-radius: 0 0 8px 8px;
        border: 1px solid var(--bs-border-color);
        border-top: none;
    }

    .table-icon-map {
        font-size: 2rem;
        position: relative;
        z-index: 1;
    }

    .edit-mode-badge {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 1000;
    }

    .context-menu {
        position: fixed;
        background: var(--bs-card-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        padding: 8px 0;
        z-index: 9999;
        min-width: 200px;
        display: none;
        backdrop-filter: none;
    }

    /* Asegurar fondo sólido 100% opaco en modo claro */
    [data-theme="light"] .context-menu {
        background: #ffffff !important;
        border: 1px solid #e0e0e0;
        opacity: 1 !important;
    }

    /* Asegurar fondo sólido 100% opaco en modo oscuro */
    [data-theme="dark"] .context-menu {
        background: #2b2c40 !important;
        border: 1px solid #3a3b54;
        opacity: 1 !important;
    }

    .context-menu.show {
        display: block;
    }

    .context-menu-item {
        padding: 10px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.2s;
        font-size: 0.9rem;
        color: var(--bs-body-color);
        background: transparent;
    }

    .context-menu-item:hover {
        background: var(--bs-secondary-bg) !important;
    }

    [data-theme="light"] .context-menu-item:hover {
        background: #f5f5f5 !important;
    }

    [data-theme="dark"] .context-menu-item:hover {
        background: #3a3b54 !important;
    }

    .context-menu-item i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    .context-menu-item.text-danger:hover {
        background: rgba(255, 62, 29, 0.1) !important;
    }

    .context-menu-divider {
        height: 1px;
        background: var(--bs-border-color);
        margin: 6px 0;
    }

    [data-theme="light"] .context-menu-divider {
        background: #e0e0e0;
    }

    [data-theme="dark"] .context-menu-divider {
        background: #3a3b54;
    }
</style>
@endsection

@section('content')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Mesas del Restaurante</h1>
            <p class="text-muted mb-0">Haz clic en una mesa para tomar pedido o arrastra para reposicionar</p>
        </div>
        <div class="d-flex gap-2">
            <button id="toggleEditMode" class="btn btn-outline-primary">
                <i class="ri ri-edit-line me-1"></i> <span id="editModeText">Modo Edición</span>
            </button>
            <button id="savePositions" class="btn btn-success" style="display: none;">
                <i class="ri ri-save-line me-1"></i> Guardar Posiciones
            </button>
            <a href="{{ route('tenant.path.tables.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                <i class="ri ri-add-line me-1"></i> Nueva Mesa
            </a>
        </div>
    </div>
</div>

<div id="editModeBadge" class="edit-mode-badge" style="display: none;">
    <div class="alert alert-warning mb-0">
        <i class="ri ri-edit-line me-2"></i>
        <strong>Modo Edición Activo</strong><br>
        <small>Arrastra las mesas para reposicionarlas</small>
    </div>
</div>

<!-- Mapa del Restaurante -->
<div class="card">
    <div class="card-body p-0">
        @if($tables->count() > 0)
            <div class="restaurant-map" id="restaurantMap">
                <!-- Estadísticas dentro del mapa -->
                <div class="map-stats">
                    <div class="stat-badge available">
                        <i class="ri ri-check-line text-success"></i>
                        <span><strong>{{ $tables->where('status', 'available')->count() }}</strong> Disponibles</span>
                    </div>
                    <div class="stat-badge occupied">
                        <i class="ri ri-user-line text-danger"></i>
                        <span><strong>{{ $tables->where('status', 'occupied')->count() }}</strong> Ocupadas</span>
                    </div>
                    <div class="stat-badge reserved">
                        <i class="ri ri-bookmark-line text-warning"></i>
                        <span><strong>{{ $tables->where('status', 'reserved')->count() }}</strong> Reservadas</span>
                    </div>
                    <div class="stat-badge total">
                        <i class="ri ri-table-line text-primary"></i>
                        <span><strong>{{ $tables->count() }}</strong> Total</span>
                    </div>
                </div>

                <!-- Mesas -->
                @foreach($tables as $table)
                    <div class="table-item card {{ $table->shape ?? 'square' }} size-{{ $table->size ?? 'medium' }} {{ ($table->shape === 'rectangle' && $table->orientation === 'vertical') ? 'vertical' : '' }}"
                         data-table-id="{{ $table->id }}"
                         data-status="{{ $table->status }}"
                         data-shape="{{ $table->shape ?? 'square' }}"
                         data-orientation="{{ $table->orientation ?? 'horizontal' }}"
                         data-size="{{ $table->size ?? 'medium' }}"
                         style="left: {{ $table->position_x ?? (($loop->index % 8) * 120 + 20) }}px; top: {{ $table->position_y ?? (floor($loop->index / 8) * 120 + 80) }}px;">

                        <div class="table-visual-map {{ $table->status }} shape-{{ $table->shape ?? 'square' }}">
                            <i class="ri ri-restaurant-2-line table-icon-map"></i>
                        </div>

                        <div class="table-info-map">
                            <strong class="d-block">{{ $table->number }}</strong>
                            <small class="text-muted">
                                <i class="ri ri-user-line"></i> {{ $table->capacity }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="ri ri-table-line ri-48px"></i>
                    </span>
                </div>
                <h5 class="mb-1">No hay mesas registradas</h5>
                <p class="text-muted mb-4">Crea tu primera mesa para comenzar</p>
                <a href="{{ route('tenant.path.tables.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">
                    <i class="ri ri-add-line me-1"></i> Crear Primera Mesa
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Menú Contextual -->
<div id="contextMenu" class="context-menu">
    <div class="context-menu-item" data-action="shape-square">
        <i class="ri ri-checkbox-blank-line"></i>
        <span>Forma Cuadrada</span>
    </div>
    <div class="context-menu-item" data-action="shape-round">
        <i class="ri ri-checkbox-blank-circle-line"></i>
        <span>Forma Redonda</span>
    </div>
    <div class="context-menu-item" data-action="shape-rectangle">
        <i class="ri ri-rectangle-line"></i>
        <span>Forma Rectangular</span>
    </div>
    <div class="context-menu-divider"></div>
    <div class="context-menu-item" data-action="size-small">
        <i class="ri ri-arrow-down-s-line"></i>
        <span>Tamaño Pequeño</span>
    </div>
    <div class="context-menu-item" data-action="size-medium">
        <i class="ri ri-subtract-line"></i>
        <span>Tamaño Mediano</span>
    </div>
    <div class="context-menu-item" data-action="size-large">
        <i class="ri ri-arrow-up-s-line"></i>
        <span>Tamaño Grande</span>
    </div>
    <div class="context-menu-divider"></div>
    <div class="context-menu-item" data-action="orientation-horizontal">
        <i class="ri ri-arrow-left-right-line"></i>
        <span>Horizontal</span>
    </div>
    <div class="context-menu-item" data-action="orientation-vertical">
        <i class="ri ri-arrow-up-down-line"></i>
        <span>Vertical</span>
    </div>
    <div class="context-menu-divider"></div>
    <div class="context-menu-item text-danger" data-action="delete">
        <i class="ri ri-delete-bin-line"></i>
        <span>Eliminar Mesa</span>
    </div>
</div>
@endsection

@section('page-script')
<script>
let editMode = false;
let draggedElement = null;
let offsetX = 0;
let offsetY = 0;
let contextMenuTarget = null;

const toggleEditBtn = document.getElementById('toggleEditMode');
const saveBtn = document.getElementById('savePositions');
const editModeBadge = document.getElementById('editModeBadge');
const editModeText = document.getElementById('editModeText');
const restaurantMap = document.getElementById('restaurantMap');
const tableItems = document.querySelectorAll('.table-item');
const contextMenu = document.getElementById('contextMenu');

// Toggle edit mode
toggleEditBtn.addEventListener('click', function() {
    editMode = !editMode;

    if (editMode) {
        editModeText.textContent = 'Desactivar Edición';
        toggleEditBtn.classList.remove('btn-outline-primary');
        toggleEditBtn.classList.add('btn-warning');
        saveBtn.style.display = 'inline-block';
        editModeBadge.style.display = 'block';
        enableDragging();
    } else {
        editModeText.textContent = 'Modo Edición';
        toggleEditBtn.classList.remove('btn-warning');
        toggleEditBtn.classList.add('btn-outline-primary');
        saveBtn.style.display = 'none';
        editModeBadge.style.display = 'none';
        disableDragging();
    }
});

function enableDragging() {
    tableItems.forEach(item => {
        item.style.cursor = 'move';
        item.addEventListener('mousedown', handleMouseDown);
        item.addEventListener('contextmenu', handleContextMenu);
        // Deshabilitar click para tomar pedido en modo edición
        item.onclick = null;
    });
}

function disableDragging() {
    tableItems.forEach(item => {
        item.style.cursor = 'pointer';
        item.removeEventListener('mousedown', handleMouseDown);
        item.removeEventListener('contextmenu', handleContextMenu);
        // Habilitar click para tomar pedido
        item.onclick = function() {
            handleTableClick(this.dataset.tableId, this.dataset.status);
        };
    });
}

// Habilitar clicks inicialmente
tableItems.forEach(item => {
    item.onclick = function() {
        if (!editMode) {
            handleTableClick(this.dataset.tableId, this.dataset.status);
        }
    };
});

function handleTableClick(tableId, status) {
    if (status === 'available') {
        window.location.href = `{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/take-order`;
    } else if (status === 'occupied') {
        window.location.href = `{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/show-order`;
    }
}

function handleMouseDown(e) {
    if (!editMode || e.button !== 0) return; // Solo botón izquierdo

    draggedElement = e.currentTarget;
    draggedElement.classList.add('dragging');

    const rect = draggedElement.getBoundingClientRect();
    const mapRect = restaurantMap.getBoundingClientRect();

    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;

    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('mouseup', handleMouseUp);

    e.preventDefault();
}

function handleMouseMove(e) {
    if (!draggedElement) return;

    const mapRect = restaurantMap.getBoundingClientRect();

    let newX = e.clientX - mapRect.left - offsetX;
    let newY = e.clientY - mapRect.top - offsetY;

    // Limitar dentro del mapa
    newX = Math.max(0, Math.min(newX, mapRect.width - draggedElement.offsetWidth));
    newY = Math.max(0, Math.min(newY, mapRect.height - draggedElement.offsetHeight));

    draggedElement.style.left = newX + 'px';
    draggedElement.style.top = newY + 'px';
}

function handleMouseUp() {
    if (draggedElement) {
        draggedElement.classList.remove('dragging');
        draggedElement = null;
    }

    document.removeEventListener('mousemove', handleMouseMove);
    document.removeEventListener('mouseup', handleMouseUp);
}

// Menú contextual
function handleContextMenu(e) {
    if (!editMode) return;

    e.preventDefault();
    contextMenuTarget = e.currentTarget;

    contextMenu.style.left = e.pageX + 'px';
    contextMenu.style.top = e.pageY + 'px';
    contextMenu.classList.add('show');
}

// Cerrar menú contextual al hacer click fuera
document.addEventListener('click', function(e) {
    if (!contextMenu.contains(e.target)) {
        contextMenu.classList.remove('show');
    }
});

// Manejar acciones del menú contextual
document.querySelectorAll('.context-menu-item').forEach(item => {
    item.addEventListener('click', async function() {
        const action = this.dataset.action;

        if (!contextMenuTarget) return;

        if (action === 'delete') {
            await deleteTable(contextMenuTarget);
        } else {
            const [type, value] = action.split('-');

            if (type === 'shape') {
                await updateTableShape(contextMenuTarget, value);
            } else if (type === 'orientation') {
                await updateTableOrientation(contextMenuTarget, value);
            } else if (type === 'size') {
                await updateTableSize(contextMenuTarget, value);
            }
        }

        contextMenu.classList.remove('show');
    });
});

async function updateTableShape(element, shape) {
    const tableId = element.dataset.tableId;

    try {
        const response = await fetch(`{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/update-shape`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ shape: shape })
        });

        const result = await response.json();

        if (result.success) {
            // Actualizar clases del elemento
            element.classList.remove('square', 'round', 'rectangle');
            element.classList.add(shape);
            element.dataset.shape = shape;

            // Actualizar clase visual
            const visual = element.querySelector('.table-visual-map');
            visual.classList.remove('shape-square', 'shape-round', 'shape-rectangle');
            visual.classList.add('shape-' + shape);

            // Si es rectangular, aplicar orientación actual
            if (shape === 'rectangle') {
                const orientation = element.dataset.orientation || 'horizontal';
                if (orientation === 'vertical') {
                    element.classList.add('vertical');
                }
            } else {
                element.classList.remove('vertical');
            }
        }
    } catch (error) {
        console.error('Error al actualizar forma:', error);
    }
}

async function updateTableOrientation(element, orientation) {
    const tableId = element.dataset.tableId;
    const shape = element.dataset.shape;

    // Solo aplicar orientación a mesas rectangulares
    if (shape !== 'rectangle') {
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: 'La orientación solo aplica a mesas rectangulares',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    try {
        const response = await fetch(`{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/update-orientation`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ orientation: orientation })
        });

        const result = await response.json();

        if (result.success) {
            element.dataset.orientation = orientation;

            if (orientation === 'vertical') {
                element.classList.add('vertical');
            } else {
                element.classList.remove('vertical');
            }
        }
    } catch (error) {
        console.error('Error al actualizar orientación:', error);
    }
}

async function updateTableSize(element, size) {
    const tableId = element.dataset.tableId;

    try {
        const response = await fetch(`{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}/update-size`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ size: size })
        });

        const result = await response.json();

        if (result.success) {
            // Actualizar clases del elemento
            element.classList.remove('size-small', 'size-medium', 'size-large');
            element.classList.add('size-' + size);
            element.dataset.size = size;
        }
    } catch (error) {
        console.error('Error al actualizar tamaño:', error);
    }
}

async function deleteTable(element) {
    const tableId = element.dataset.tableId;
    const tableNumber = element.querySelector('.table-info-map strong').textContent;

    const result = await Swal.fire({
        title: '¿Eliminar mesa?',
        html: `¿Estás seguro de eliminar la <strong>Mesa ${tableNumber}</strong>?<br><small class="text-muted">Esta acción no se puede deshacer</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch(`{{ url('/') }}/{{ request()->route('tenant') }}/tables/${tableId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            // Remover elemento del DOM con animación
            element.style.transition = 'all 0.3s ease';
            element.style.opacity = '0';
            element.style.transform = 'scale(0.8)';

            setTimeout(() => {
                element.remove();
            }, 300);

            Swal.fire({
                icon: 'success',
                title: '¡Eliminada!',
                text: 'La mesa ha sido eliminada correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message || 'Error al eliminar la mesa');
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo eliminar la mesa'
        });
    }
}

// Guardar posiciones
saveBtn.addEventListener('click', async function() {
    const positions = [];

    tableItems.forEach(item => {
        const tableId = item.dataset.tableId;
        const x = parseInt(item.style.left);
        const y = parseInt(item.style.top);

        positions.push({
            id: tableId,
            x: x,
            y: y
        });
    });

    try {
        const response = await fetch('{{ route("tenant.path.tables.updatePositions", ["tenant" => request()->route("tenant")]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ positions: positions })
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: 'Las posiciones de las mesas se han guardado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron guardar las posiciones: ' + error.message
        });
    }
});
</script>
@endsection
