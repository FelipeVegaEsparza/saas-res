<?php $__env->startSection('title', 'KDS - ' . $area->name); ?>

<?php $__env->startSection('content'); ?>
<style>
    .kds-column {
        min-height: calc(100vh - 250px);
        border-radius: 8px;
        padding: 1rem;
    }

    /* Light mode */
    [data-bs-theme="light"] .kds-column {
        background: #f8f9fa;
    }

    [data-bs-theme="light"] .kds-column.preparing {
        background: #fff3e0;
    }

    [data-bs-theme="light"] .kds-column.ready {
        background: #e8f5e9;
    }

    [data-bs-theme="light"] .column-header {
        background: white;
    }

    [data-bs-theme="light"] .column-header.preparing {
        background: #fff3e0;
        border: 2px solid #ff9800;
    }

    [data-bs-theme="light"] .column-header.ready {
        background: #e8f5e9;
        border: 2px solid #4caf50;
    }

    /* Dark mode */
    [data-bs-theme="dark"] .kds-column {
        background: #2b2c40;
    }

    [data-bs-theme="dark"] .kds-column.preparing {
        background: #3d3420;
    }

    [data-bs-theme="dark"] .kds-column.ready {
        background: #1e3a2a;
    }

    [data-bs-theme="dark"] .column-header {
        background: #2b2c40;
        border: 2px solid #444564;
    }

    [data-bs-theme="dark"] .column-header.preparing {
        background: #3d3420;
        border: 2px solid #ff9800;
    }

    [data-bs-theme="dark"] .column-header.ready {
        background: #1e3a2a;
        border: 2px solid #4caf50;
    }

    [data-bs-theme="dark"] .kds-card {
        background: #2b2c40;
        border-color: #444564;
    }

    [data-bs-theme="dark"] .empty-state {
        color: #a3a4cc;
    }

    .kds-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid <?php echo e($area->color); ?>;
    }

    .kds-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    [data-bs-theme="dark"] .kds-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }

    .kds-card.time-warning {
        border-left-color: #ff9800;
    }

    .kds-card.time-danger {
        border-left-color: #f44336;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    .column-header {
        position: sticky;
        top: 0;
        z-index: 10;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    [data-bs-theme="dark"] .column-header {
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .customer-info {
        padding: 0.5rem;
        border-radius: 4px;
    }

    [data-bs-theme="light"] .customer-info {
        background: #e3f2fd;
    }

    [data-bs-theme="dark"] .customer-info {
        background: #1e3a5f;
    }
</style>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 50px; height: 50px; background: <?php echo e($area->color); ?>; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="ri <?php echo e($area->icon); ?> ri-24px text-white"></i>
            </div>
            <div>
                <h1 class="mb-0"><?php echo e($area->name); ?></h1>
                <p class="text-muted mb-0">
                    <?php echo e($ordersByStatus['pending']->count() + $ordersByStatus['preparing']->count() + $ordersByStatus['ready']->count()); ?> pedidos activos
                </p>
            </div>
        </div>
        <div>
            <button onclick="location.reload()" class="btn btn-outline-primary">
                <i class="ri ri-refresh-line me-1"></i> Actualizar
            </button>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Columna PENDIENTE -->
    <div class="col-lg-4">
        <div class="kds-column">
            <div class="column-header">
                <h5 class="mb-0 d-flex align-items-center justify-content-between">
                    <span><i class="ri ri-time-line me-2 text-secondary"></i>Pendiente</span>
                    <span class="badge bg-secondary"><?php echo e($ordersByStatus['pending']->count()); ?></span>
                </h5>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $ordersByStatus['pending']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('tenant.kds.partials.order-card', ['order' => $order, 'area' => $area], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center empty-state py-5">
                    <i class="ri ri-checkbox-circle-line ri-48px mb-2"></i>
                    <p>No hay pedidos pendientes</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Columna PREPARANDO -->
    <div class="col-lg-4">
        <div class="kds-column preparing">
            <div class="column-header preparing">
                <h5 class="mb-0 d-flex align-items-center justify-content-between">
                    <span><i class="ri ri-restaurant-2-line me-2 text-warning"></i>Preparando</span>
                    <span class="badge bg-warning"><?php echo e($ordersByStatus['preparing']->count()); ?></span>
                </h5>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $ordersByStatus['preparing']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('tenant.kds.partials.order-card', ['order' => $order, 'area' => $area], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center empty-state py-5">
                    <i class="ri ri-checkbox-circle-line ri-48px mb-2"></i>
                    <p>No hay pedidos en preparación</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Columna LISTO -->
    <div class="col-lg-4">
        <div class="kds-column ready">
            <div class="column-header ready">
                <h5 class="mb-0 d-flex align-items-center justify-content-between">
                    <span><i class="ri ri-checkbox-circle-line me-2 text-success"></i>Listo</span>
                    <span class="badge bg-success"><?php echo e($ordersByStatus['ready']->count()); ?></span>
                </h5>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $ordersByStatus['ready']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('tenant.kds.partials.order-card', ['order' => $order, 'area' => $area], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center empty-state py-5">
                    <i class="ri ri-checkbox-circle-line ri-48px mb-2"></i>
                    <p>No hay pedidos listos</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    function initKDS() {
        // Usar delegación de eventos en el documento
        document.body.addEventListener('click', async function(e) {
            const button = e.target.closest('.btn-change-status');
            if (!button) return;

            e.preventDefault();
            e.stopPropagation();

            const card = button.closest('.kds-card');
            if (!card) return;

            const orderType = card.getAttribute('data-order-type');
            const orderId = card.getAttribute('data-order-id');
            const currentStatus = card.getAttribute('data-current-status');
            const nextStatus = button.getAttribute('data-next-status');
            const areaId = button.getAttribute('data-area-id');
            const tenant = button.getAttribute('data-tenant');

            if (!orderType || !orderId || !nextStatus || !areaId || !tenant) {
                alert('Error: Faltan datos necesarios');
                return;
            }

            // Deshabilitar botón
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Actualizando...';

            try {
                const url = `/${tenant}/kds/${areaId}/${orderType}/${orderId}/update`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: nextStatus })
                });

                const data = await response.json();

                if (response.ok) {
                    // Mover la card a la nueva columna
                    moveCardToColumn(card, currentStatus, nextStatus);

                    // Actualizar el estado en la card
                    card.setAttribute('data-current-status', nextStatus);

                    // Actualizar el botón
                    updateCardButton(card, nextStatus, areaId, tenant);

                    // Actualizar contadores
                    updateColumnCounters();
                } else {
                    alert('Error al actualizar el estado: ' + (data.message || 'Error desconocido'));
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al actualizar el estado: ' + error.message);
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }

    function moveCardToColumn(card, fromStatus, toStatus) {
        // Encontrar la columna destino
        const columns = {
            'pending': document.querySelector('.col-lg-4:nth-child(1) .kds-column'),
            'preparing': document.querySelector('.col-lg-4:nth-child(2) .kds-column'),
            'ready': document.querySelector('.col-lg-4:nth-child(3) .kds-column')
        };

        const targetColumn = columns[toStatus];
        if (!targetColumn) return;

        // Remover mensaje de "vacío" si existe
        const emptyState = targetColumn.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }

        // Animar salida
        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';

        setTimeout(() => {
            // Mover la card
            targetColumn.appendChild(card);

            // Animar entrada
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, 50);

            // Verificar si la columna origen quedó vacía
            const sourceColumn = columns[fromStatus];
            if (sourceColumn && sourceColumn.querySelectorAll('.kds-card').length === 0) {
                const emptyMessage = document.createElement('div');
                emptyMessage.className = 'text-center empty-state py-5';
                emptyMessage.innerHTML = '<i class="ri ri-checkbox-circle-line ri-48px mb-2"></i><p>No hay pedidos ' +
                    (fromStatus === 'pending' ? 'pendientes' : fromStatus === 'preparing' ? 'en preparación' : 'listos') + '</p>';
                sourceColumn.appendChild(emptyMessage);
            }
        }, 300);
    }

    function updateCardButton(card, newStatus, areaId, tenant) {
        const buttonContainer = card.querySelector('.d-grid');
        if (!buttonContainer) return;

        let buttonHTML = '';

        if (newStatus === 'pending') {
            buttonHTML = `
                <button class="btn btn-warning btn-change-status"
                        data-next-status="preparing"
                        data-area-id="${areaId}"
                        data-tenant="${tenant}">
                    <i class="ri ri-restaurant-2-line me-1"></i> Iniciar Preparación
                </button>`;
        } else if (newStatus === 'preparing') {
            buttonHTML = `
                <button class="btn btn-success btn-change-status"
                        data-next-status="ready"
                        data-area-id="${areaId}"
                        data-tenant="${tenant}">
                    <i class="ri ri-checkbox-circle-line me-1"></i> Marcar Listo
                </button>`;
        } else if (newStatus === 'ready') {
            buttonHTML = `
                <button class="btn btn-outline-secondary btn-sm btn-change-status"
                        data-next-status="preparing"
                        data-area-id="${areaId}"
                        data-tenant="${tenant}">
                    <i class="ri ri-arrow-left-line me-1"></i> Volver a Preparando
                </button>`;
        }

        buttonContainer.innerHTML = buttonHTML;
    }

    function updateColumnCounters() {
        const columns = [
            { selector: '.col-lg-4:nth-child(1)', status: 'pending' },
            { selector: '.col-lg-4:nth-child(2)', status: 'preparing' },
            { selector: '.col-lg-4:nth-child(3)', status: 'ready' }
        ];

        columns.forEach(col => {
            const column = document.querySelector(col.selector);
            if (!column) return;

            const count = column.querySelectorAll('.kds-card').length;
            const badge = column.querySelector('.badge');
            if (badge) {
                badge.textContent = count;
            }
        });
    }

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initKDS);
    } else {
        initKDS();
    }

    // Auto-refresh cada 30 segundos
    setTimeout(() => {
        window.location.reload();
    }, 30000);
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/kds/index.blade.php ENDPATH**/ ?>