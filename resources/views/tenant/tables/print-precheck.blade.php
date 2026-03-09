<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Precuenta - Mesa {{ $table->number }}</title>
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

        .precheck-title {
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
            margin-bottom: 8px;
            padding: 4px 2px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .item-left {
            flex: 1;
            padding-right: 8px;
        }

        .item-qty {
            font-weight: bold;
            margin-right: 6px;
        }

        .item-name {
            display: inline;
        }

        .item-price {
            white-space: nowrap;
            font-weight: bold;
        }

        .item-notes {
            font-style: italic;
            color: #333;
            margin-left: 20px;
            font-size: 11px;
            margin-top: 2px;
        }

        .totals-section {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 12px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 0 2px;
        }

        .total-row.subtotal {
            font-size: 13px;
        }

        .total-row.final {
            font-size: 16px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 8px;
            margin-top: 8px;
        }

        .tips-section {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 2px dashed #000;
        }

        .tips-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .tip-option {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            padding: 6px 4px;
            background: #f5f5f5;
        }

        .tip-label {
            font-weight: bold;
        }

        .tip-total {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 18px;
            padding-top: 12px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .print-time {
            margin-top: 10px;
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
        <div class="restaurant-name">{{ tenant()->restaurant()->name }}</div>
        <div class="precheck-title">PRECUENTA</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Mesa:</span>
            <span>{{ $table->number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pedido:</span>
            <span>{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mesero:</span>
            <span>{{ $order->waiter->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="items-section">
        @foreach($order->items as $item)
            <div class="item">
                <div class="item-row">
                    <div class="item-left">
                        <span class="item-qty">{{ $item->quantity }}x</span>
                        <span class="item-name">{{ $item->product->name }}</span>
                    </div>
                    <div class="item-price">@price($item->subtotal)</div>
                </div>
                @if($item->notes)
                    <div class="item-notes">
                        * {{ $item->notes }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="totals-section">
        <div class="total-row subtotal">
            <span>Subtotal:</span>
            <span>@price($order->subtotal)</span>
        </div>
        <div class="total-row final">
            <span>TOTAL:</span>
            <span>@price($order->total)</span>
        </div>
    </div>

    <div class="tips-section">
        <div class="tips-title">PROPINA SUGERIDA</div>

        <div class="tip-option">
            <span class="tip-label">10%</span>
            <span class="tip-total">@price($order->total + $tips[10])</span>
        </div>

        <div class="tip-option">
            <span class="tip-label">15%</span>
            <span class="tip-total">@price($order->total + $tips[15])</span>
        </div>

        <div class="tip-option">
            <span class="tip-label">20%</span>
            <span class="tip-total">@price($order->total + $tips[20])</span>
        </div>
    </div>

    <div class="footer">
        <div class="thank-you">¡Gracias por su visita!</div>
        <div>Esperamos verle pronto</div>
        <div class="print-time">Impreso: {{ now()->format('d/m/Y H:i:s') }}</div>
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
