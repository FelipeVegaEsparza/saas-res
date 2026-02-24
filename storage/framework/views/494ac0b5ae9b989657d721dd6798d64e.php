<?php $__env->startSection('title', 'Mapa de Mesas'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
    .restaurant-map {
        position: relative;
        width: 100%;
        height: 700px;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-item {
        position: absolute;
        width: 100px;
        height: 100px;
        cursor: move;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Mapa de Mesas</h1>
            <p class="text-muted">Arrastra las mesas para posicionarlas según tu restaurante</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('tenant.path.tables.index', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-outline-secondary">
                <i class="ri ri-grid-line me-1"></i> Vista Grid
            </a>
            <button id="toggleEditMode" class="btn btn-primary">
                <i class="ri ri-edit-line me-1"></i> <span id="editModeText">Activar Edición</span>
            </button>
            <button id="savePositions" class="btn btn-success" style="display: none;">
                <i class="ri ri-save-line me-1"></i> Guardar Posiciones
            </button>
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
    <div class="card-body">
        <div class="restaurant-map" id="restaurantMap">
            <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="table-item card"
                     data-table-id="<?php echo e($table->id); ?>"
                     style="left: <?php echo e($table->position_x ?? (($loop->index % 8) * 120 + 20)); ?>px; top: <?php echo e($table->position_y ?? (floor($loop->index / 8) * 120 + 20)); ?>px;">

                    <div class="table-visual-map <?php echo e($table->status); ?>">
                        <i class="ri ri-restaurant-2-line table-icon-map"></i>
                    </div>

                    <div class="table-info-map">
                        <strong class="d-block"><?php echo e($table->number); ?></strong>
                        <small class="text-muted">
                            <i class="ri ri-user-line"></i> <?php echo e($table->capacity); ?>

                        </small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php if($tables->count() === 0): ?>
    <div class="alert alert-info mt-3">
        <i class="ri ri-information-line me-2"></i>
        No hay mesas creadas. <a href="<?php echo e(route('tenant.path.tables.create', ['tenant' => request()->route('tenant')])); ?>">Crear primera mesa</a>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
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
        toggleEditBtn.classList.remove('btn-primary');
        toggleEditBtn.classList.add('btn-warning');
        saveBtn.style.display = 'inline-block';
        editModeBadge.style.display = 'block';
        enableDragging();
    } else {
        editModeText.textContent = 'Activar Edición';
        toggleEditBtn.classList.remove('btn-warning');
        toggleEditBtn.classList.add('btn-primary');
        saveBtn.style.display = 'none';
        editModeBadge.style.display = 'none';
        disableDragging();
    }
});

function enableDragging() {
    tableItems.forEach(item => {
        item.style.cursor = 'move';
        item.addEventListener('mousedown', handleMouseDown);
    });
}

function disableDragging() {
    tableItems.forEach(item => {
        item.style.cursor = 'default';
        item.removeEventListener('mousedown', handleMouseDown);
    });
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
        const response = await fetch('<?php echo e(route("tenant.path.tables.updatePositions", ["tenant" => request()->route("tenant")])); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/tables/map.blade.php ENDPATH**/ ?>