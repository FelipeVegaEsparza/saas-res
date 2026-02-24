@extends('tenant.layouts.admin')

@section('title', 'Mesas')

@section('page-style')
<style>
    .restaurant-map {
        position: relative;
        width: 100%;
        height: calc(100vh - 250px);
        min-height: 600px;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
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
        background: white;
        padding: 8px 16px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
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
        background: white;
        padding: 8px;
        text-align: center;
        border-radius: 0 0 8px 8px;
        border: 1px solid #dee2e6;
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
                    <div class="table-item card"
                         data-table-id="{{ $table->id }}"
                         data-status="{{ $table->status }}"
                         style="left: {{ $table->position_x ?? (($loop->index % 8) * 120 + 20) }}px; top: {{ $table->position_y ?? (floor($loop->index / 8) * 120 + 80) }}px;">

                        <div class="table-visual-map {{ $table->status }}">
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
@endsection

@section('page-script')
<script>
let editMode = false;
let draggedElement = null;
let offsetX = 0;
let offsetY = 0;

const toggleEditBtn = document.getElementById('toggleEditMode');
const saveBtn = document.getElementById('savePositions');
const editModeBadge = document.getElementById('editModeBadge');
const editModeText = document.getElementById('editModeText');
const restaurantMap = document.getElementById('restaurantMap');
const tableItems = document.querySelectorAll('.table-item');

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
        // Deshabilitar click para tomar pedido en modo edición
        item.onclick = null;
    });
}

function disableDragging() {
    tableItems.forEach(item => {
        item.style.cursor = 'pointer';
        item.removeEventListener('mousedown', handleMouseDown);
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
    if (!editMode) return;

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
