<?php $__env->startSection('title', 'Caja'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="mb-1">Caja</h1>
    <p class="text-muted">Gestiona pagos y sesiones de caja</p>
</div>

<?php if($activeSession): ?>
    <!-- Barra de Sesión Activa -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%); color: white;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-4">
                        <div>
                            <i class="ri ri-checkbox-circle-line ri-24px"></i>
                            <strong class="ms-2">Sesión Activa</strong>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <small class="opacity-75">Apertura:</small>
                            <strong class="ms-1"><?php echo e($activeSession->opened_at->format('H:i')); ?></strong>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <small class="opacity-75">Efectivo Inicial:</small>
                            <strong class="ms-1">$<?php echo e(number_format($activeSession->opening_balance, 2)); ?></strong>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <small class="opacity-75">Duración:</small>
                            <strong class="ms-1"><?php echo e($activeSession->opened_at->diffForHumans(null, true)); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#historyModal">
                        <i class="ri ri-history-line me-1"></i> Historial
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#closeSessionModal">
                        <i class="ri ri-lock-line me-1"></i> Cerrar Caja
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuentas Pendientes de Pago -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Cuentas por Cobrar</h5>
            <span class="badge bg-label-primary"><?php echo e($pendingOrders->count()); ?> cuentas</span>
        </div>
        <div class="card-body">
            <?php if($pendingOrders->count() > 0): ?>
                <div class="row g-3">
                    <?php $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="ri ri-table-line me-1"></i>Mesa <?php echo e($order->table->number); ?>

                                            </h5>
                                            <small class="text-muted"><?php echo e($order->order_number); ?></small>
                                        </div>
                                        <span class="badge bg-warning">Por cobrar</span>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Mesero:</small>
                                            <small><?php echo e($order->waiter->name ?? 'N/A'); ?></small>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Hora:</small>
                                            <small><?php echo e($order->created_at->format('H:i')); ?></small>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Items:</small>
                                            <small><?php echo e($order->items->count()); ?> productos</small>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Total:</span>
                                        <h4 class="mb-0 text-primary">$<?php echo e(number_format($order->total, 2)); ?></h4>
                                    </div>

                                    <button class="btn btn-success w-100" onclick="showPaymentModal(<?php echo e($order->id); ?>, <?php echo e($order->total); ?>, '<?php echo e($order->table->number); ?>')">
                                        <i class="ri ri-cash-line me-1"></i> Cobrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri ri-check-line ri-36px"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">No hay cuentas pendientes</h5>
                    <p class="text-muted">Todas las cuentas han sido cobradas</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <!-- Sin Sesión Activa -->
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="avatar avatar-xl mx-auto mb-3">
                <span class="avatar-initial rounded bg-label-primary">
                    <i class="ri ri-lock-unlock-line ri-48px"></i>
                </span>
            </div>
            <h4 class="mb-2">No hay sesión de caja activa</h4>
            <p class="text-muted mb-4">Debes abrir una sesión de caja para procesar pagos</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#openSessionModal">
                <i class="ri ri-lock-unlock-line me-2"></i> Abrir Sesión de Caja
            </button>
            <button type="button" class="btn btn-outline-secondary btn-lg ms-2" data-bs-toggle="modal" data-bs-target="#historyModal">
                <i class="ri ri-history-line me-2"></i> Ver Historial
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Abrir Sesión -->
<div class="modal fade" id="openSessionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('tenant.path.cash.open', ['tenant' => request()->route('tenant')])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri ri-lock-unlock-line me-2"></i>Abrir Sesión de Caja
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="ri ri-information-line me-2"></i>
                        <div>
                            <strong>Importante:</strong> Cuenta el efectivo disponible antes de abrir la sesión
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Efectivo Inicial *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="opening_balance" class="form-control" step="0.01" min="0"
                                   placeholder="0.00" required autofocus>
                        </div>
                        <small class="text-muted">Monto en efectivo con el que inicias la caja</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas (opcional)</label>
                        <textarea name="opening_notes" class="form-control" rows="3"
                                  placeholder="Observaciones sobre la apertura..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-lock-unlock-line me-1"></i> Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cerrar Sesión -->
<?php if($activeSession): ?>
<div class="modal fade" id="closeSessionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('tenant.path.cash.close', ['tenant' => request()->route('tenant'), 'cashSession' => $activeSession])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="ri ri-lock-line me-2"></i>Cerrar Sesión de Caja
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-start">
                        <i class="ri ri-alert-line me-2 mt-1"></i>
                        <div>
                            <strong>Atención:</strong> Asegúrate de contar todo el efectivo en caja antes de cerrar la sesión. Esta acción no se puede deshacer.
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Efectivo Inicial</small>
                                    <h6 class="mb-0">$<?php echo e(number_format($activeSession->opening_balance, 2)); ?></h6>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Duración</small>
                                    <h6 class="mb-0"><?php echo e($activeSession->opened_at->diffForHumans(null, true)); ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Efectivo Final *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="closing_balance" class="form-control" step="0.01" min="0"
                                   placeholder="0.00" required autofocus>
                        </div>
                        <small class="text-muted">Monto total de efectivo contado en caja</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas de Cierre (opcional)</label>
                        <textarea name="closing_notes" class="form-control" rows="3"
                                  placeholder="Observaciones sobre el cierre, incidencias, etc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-lock-line me-1"></i> Cerrar Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Procesar Pago -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri ri-cash-line me-2"></i>Procesar Pago
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0" id="paymentTableNumber"></h6>
                                <small class="text-muted">Total a cobrar</small>
                            </div>
                            <h3 class="mb-0 text-primary" id="paymentTotal"></h3>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Método de Pago *</label>
                    <select id="paymentMethod" class="form-select">
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                        <option value="transfer">Transferencia</option>
                    </select>
                </div>

                <div class="mb-3" id="cashPaymentSection">
                    <label class="form-label">Monto Recibido *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" id="amountPaid" class="form-control" step="0.01" min="0" placeholder="0.00">
                    </div>
                    <small class="text-muted">Cambio: <span id="change" class="text-success fw-bold">$0.00</span></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="processPayment()">
                    <i class="ri ri-check-line me-1"></i> Confirmar Pago
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Historial -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri ri-history-line me-2"></i>Historial de Sesiones
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if($sessions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cajero</th>
                                    <th>Apertura</th>
                                    <th>Cierre</th>
                                    <th>Efectivo Inicial</th>
                                    <th>Efectivo Final</th>
                                    <th>Diferencia</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <?php echo e(substr($session->user->name, 0, 1)); ?>

                                                    </span>
                                                </div>
                                                <span><?php echo e($session->user->name); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-nowrap"><?php echo e($session->opened_at->format('d/m/Y')); ?></span><br>
                                            <small class="text-muted"><?php echo e($session->opened_at->format('H:i')); ?></small>
                                        </td>
                                        <td>
                                            <?php if($session->closed_at): ?>
                                                <span class="text-nowrap"><?php echo e($session->closed_at->format('d/m/Y')); ?></span><br>
                                                <small class="text-muted"><?php echo e($session->closed_at->format('H:i')); ?></small>
                                            <?php else: ?>
                                                <span class="badge bg-label-success">En curso</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong>$<?php echo e(number_format($session->opening_balance, 2)); ?></strong></td>
                                        <td>
                                            <?php if($session->status === 'closed'): ?>
                                                <strong>$<?php echo e(number_format($session->closing_balance ?? 0, 2)); ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($session->status === 'closed' && $session->difference !== null): ?>
                                                <span class="badge bg-label-<?php echo e($session->difference >= 0 ? 'success' : 'danger'); ?>">
                                                    $<?php echo e(number_format($session->difference, 2)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($session->status === 'open' ? 'success' : 'secondary'); ?>">
                                                <?php echo e($session->status === 'open' ? 'Abierta' : 'Cerrada'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($session->status === 'closed'): ?>
                                                <a href="<?php echo e(route('tenant.path.cash.report', ['tenant' => request()->route('tenant'), 'cashSession' => $session])); ?>"
                                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                   data-bs-toggle="tooltip" title="Ver Reporte">
                                                    <i class="ri ri-file-text-line ri-20px"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <?php echo e($sessions->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ri ri-history-line ri-36px"></i>
                            </span>
                        </div>
                        <h5 class="mb-1">No hay sesiones registradas</h5>
                        <p class="text-muted">Abre tu primera sesión de caja para comenzar</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
let currentOrderId = null;
let currentTotal = 0;

// Mostrar modal de pago
function showPaymentModal(orderId, total, tableNumber) {
    currentOrderId = orderId;
    currentTotal = total;

    document.getElementById('paymentTableNumber').textContent = 'Mesa ' + tableNumber;
    document.getElementById('paymentTotal').textContent = '$' + total.toFixed(2);
    document.getElementById('amountPaid').value = total.toFixed(2);

    calculateChange();

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

// Calcular cambio
document.getElementById('amountPaid')?.addEventListener('input', calculateChange);

function calculateChange() {
    const amountPaid = parseFloat(document.getElementById('amountPaid')?.value) || 0;
    const change = amountPaid - currentTotal;

    const changeElement = document.getElementById('change');
    if (changeElement) {
        changeElement.textContent = '$' + Math.max(0, change).toFixed(2);
    }
}

// Mostrar/ocultar sección de efectivo
document.getElementById('paymentMethod')?.addEventListener('change', function() {
    const cashSection = document.getElementById('cashPaymentSection');
    if (this.value === 'cash') {
        cashSection.style.display = 'block';
    } else {
        cashSection.style.display = 'none';
        document.getElementById('amountPaid').value = currentTotal.toFixed(2);
    }
});

// Procesar pago
async function processPayment() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    let amountPaid = currentTotal;

    if (paymentMethod === 'cash') {
        amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
        if (amountPaid < currentTotal) {
            Swal.fire({
                icon: 'warning',
                title: 'Monto Insuficiente',
                text: 'El monto recibido es menor al total',
                confirmButtonText: 'Entendido'
            });
            return;
        }
    }

    const data = {
        order_id: currentOrderId,
        payment_method: paymentMethod,
        amount_paid: amountPaid
    };

    try {
        const response = await fetch('<?php echo e(route("tenant.path.cash.payment", ["tenant" => request()->route("tenant")])); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            const change = amountPaid - currentTotal;
            let htmlMessage = `<div class="text-start">
                <p class="mb-2"><strong>Total:</strong> $${currentTotal.toFixed(2)}</p>`;
            if (paymentMethod === 'cash' && change > 0) {
                htmlMessage += `<p class="mb-0"><strong>Cambio:</strong> <span class="text-success">$${change.toFixed(2)}</span></p>`;
            }
            htmlMessage += '</div>';

            Swal.fire({
                icon: 'success',
                title: 'Pago Procesado',
                html: htmlMessage,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.error || 'Error desconocido',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'Error al procesar el pago: ' + error.message,
            confirmButtonText: 'Aceptar'
        });
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenant.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/cash/index.blade.php ENDPATH**/ ?>