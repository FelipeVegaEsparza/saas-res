<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comanda - Mesa <?php echo e($table->number); ?></title>
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

        .kitchen-notes {
            background: #f0f0f0;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ddd;
        }

        .kitchen-notes-title {
            font-weight: bold;
            margin-bottom: 6px;
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
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
        }

        .kitchen-notes-title {
            font-weight: bold;
            margin-bottom: 4px;
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
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Mesa:</span>
            <span><?php echo e($table->number); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Pedido:</span>
            <span><?php echo e($order->order_number); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Mesero:</span>
            <span><?php echo e($order->waiter->name ?? 'N/A'); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Hora:</span>
            <span><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
        </div>
    </div>

    <div class="items-section">
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

    <?php if($order->kitchen_notes): ?>
        <div class="kitchen-notes">
            <div class="kitchen-notes-title">NOTAS ESPECIALES:</div>
            <div><?php echo e($order->kitchen_notes); ?></div>
        </div>
    <?php endif; ?>

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
<?php /**PATH F:\saasres\resources\views/tenant/tables/print-comanda.blade.php ENDPATH**/ ?>