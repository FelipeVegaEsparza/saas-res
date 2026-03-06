<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comanda - <?php echo e($deliveryOrder->order_number); ?></title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 80mm;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            padding: 8mm 6mm;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .comanda-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }

        .order-type {
            font-size: 14px;
            font-weight: bold;
            background: #000;
            color: #fff;
            padding: 6px 10px;
            display: inline-block;
            margin-top: 8px;
        }

        .info-section {
            margin-bottom: 12px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 0 2px;
        }

        .info-label {
            font-weight: bold;
        }

        .customer-section {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
        }

        .customer-title {
            font-weight: bold;
            margin-bottom: 8px;
            text-align: center;
        }

        .address-section {
            background: #fff3cd;
            padding: 10px;
            margin-bottom: 12px;
            border: 2px solid #ffc107;
        }

        .address-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .items-section {
            margin-bottom: 12px;
        }

        .item {
            margin-bottom: 10px;
            padding: 6px 4px;
            border-bottom: 1px dotted #ccc;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .item-name {
            flex: 1;
            padding-left: 4px;
        }

        .item-qty {
            width: 45px;
            text-align: center;
        }

        .item-notes {
            font-style: italic;
            color: #333;
            margin-left: 12px;
            padding-left: 4px;
            font-size: 11px;
            margin-top: 3px;
        }

        .notes-section {
            background: #f0f0f0;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ddd;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .total-section {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 4px;
            margin: 12px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 0 2px;
        }

        .total-final {
            font-size: 16px;
            font-weight: bold;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 18px;
            padding-top: 12px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }

        .print-time {
            margin-top: 10px;
            font-size: 10px;
        }
        }

        .item-qty {
            width: 40px;
            text-align: center;
        }

        .item-notes {
            font-style: italic;
            color: #333;
            margin-left: 10px;
            font-size: 11px;
        }

        .notes-section {
            background: #f0f0f0;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .total-section {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
            margin: 10px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-final {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }

        .print-time {
            margin-top: 8px;
            font-size: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="restaurant-name"><?php echo e(tenant()->restaurant()->name); ?></div>
        <div class="comanda-title">COMANDA DE COCINA</div>
        <div class="order-type">
            <?php echo e($deliveryOrder->type === 'delivery' ? '🚴 DELIVERY' : '🛍️ PARA LLEVAR'); ?>

        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Pedido:</span>
            <span><?php echo e($deliveryOrder->order_number); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Hora:</span>
            <span><?php echo e($deliveryOrder->created_at->format('d/m/Y H:i')); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado:</span>
            <span><?php echo e($deliveryOrder->status_label); ?></span>
        </div>
    </div>

    <div class="customer-section">
        <div class="customer-title">DATOS DEL CLIENTE</div>
        <div class="info-row">
            <span class="info-label">Nombre:</span>
            <span><?php echo e($deliveryOrder->customer_name); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Teléfono:</span>
            <span><?php echo e($deliveryOrder->customer_phone); ?></span>
        </div>
        <?php if($deliveryOrder->customer_email): ?>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span style="font-size: 10px;"><?php echo e($deliveryOrder->customer_email); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <?php if($deliveryOrder->type === 'delivery' && $deliveryOrder->delivery_address): ?>
        <div class="address-section">
            <div class="address-title">📍 DIRECCIÓN DE ENTREGA:</div>
            <div><?php echo e($deliveryOrder->delivery_address); ?></div>
            <?php if($deliveryOrder->delivery_zone): ?>
                <div style="margin-top: 5px;">
                    <span class="info-label">Zona:</span> <?php echo e($deliveryOrder->delivery_zone); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="items-section">
        <div style="font-weight: bold; margin-bottom: 8px; text-align: center; border-bottom: 1px solid #000; padding-bottom: 5px;">
            PRODUCTOS
        </div>
        <?php $__currentLoopData = $deliveryOrder->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="item">
                <div class="item-header">
                    <span class="item-qty"><?php echo e($item->quantity); ?>x</span>
                    <span class="item-name"><?php echo e($item->product->name); ?></span>
                </div>
                <?php if($item->notes): ?>
                    <div class="item-notes">
                        * <?php echo e($item->notes); ?>

                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($deliveryOrder->notes): ?>
        <div class="notes-section">
            <div class="notes-title">NOTAS ESPECIALES:</div>
            <div><?php echo e($deliveryOrder->notes); ?></div>
        </div>
    <?php endif; ?>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>$<?php echo e(number_format($deliveryOrder->subtotal, 0, ',', '.')); ?></span>
        </div>
        <?php if($deliveryOrder->delivery_fee > 0): ?>
            <div class="total-row">
                <span>Envío:</span>
                <span>$<?php echo e(number_format($deliveryOrder->delivery_fee, 0, ',', '.')); ?></span>
            </div>
        <?php endif; ?>
        <div class="total-row total-final">
            <span>TOTAL:</span>
            <span>$<?php echo e(number_format($deliveryOrder->total, 0, ',', '.')); ?></span>
        </div>
    </div>

    <div class="footer">
        <div>¡Gracias por su preferencia!</div>
        <div class="print-time">Impreso: <?php echo e(now()->format('d/m/Y H:i:s')); ?></div>
    </div>

    <script>
        // Auto-imprimir al cargar
        window.onload = function() {
            window.print();
            // Cerrar ventana después de imprimir o cancelar
            setTimeout(function() {
                window.close();
            }, 100);
        };
    </script>
</body>
</html>
<?php /**PATH F:\saasres\resources\views/tenant/delivery/print-comanda.blade.php ENDPATH**/ ?>