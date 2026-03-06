<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda - Mesa {{ $table->number }} - {{ $area->name }}</title>
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
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            padding: 8mm 6mm;
            width: 80mm;
        }

        .header {
            text-align: center;
            margin-bottom: 8mm;
            padding-bottom: 4mm;
            border-bottom: 2px dashed #000;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .header .area-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2mm;
            padding: 2mm;
            background: #000;
            color: #fff;
        }

        .header .table-info {
            font-size: 20px;
            font-weight: bold;
            margin: 3mm 0;
        }

        .info-section {
            margin-bottom: 6mm;
            padding-bottom: 4mm;
            border-bottom: 1px dashed #000;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2mm;
        }

        .info-label {
            font-weight: bold;
        }

        .items-section {
            margin-bottom: 6mm;
        }

        .item {
            margin-bottom: 4mm;
            padding-bottom: 3mm;
            border-bottom: 1px dotted #ccc;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .item-quantity {
            font-size: 14px;
        }

        .item-notes {
            margin-top: 2mm;
            padding: 2mm;
            background: #f5f5f5;
            font-style: italic;
        }

        .notes-section {
            margin-top: 6mm;
            padding: 3mm;
            background: #f5f5f5;
            border: 1px solid #000;
        }

        .notes-section strong {
            display: block;
            margin-bottom: 2mm;
        }

        .footer {
            margin-top: 8mm;
            padding-top: 4mm;
            border-top: 2px dashed #000;
            text-align: center;
            font-size: 10px;
        }

        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COMANDA</h1>
        <div class="area-name">{{ strtoupper($area->name) }}</div>
        <div class="table-info">MESA {{ $table->number }}</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Pedido:</span>
            <span>{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span>{{ $order->created_at->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Hora:</span>
            <span>{{ $order->created_at->format('H:i') }}</span>
        </div>
        @if($order->waiter)
        <div class="info-row">
            <span class="info-label">Mesero:</span>
            <span>{{ $order->waiter->name }}</span>
        </div>
        @endif
    </div>

    <div class="items-section">
        @foreach($items as $item)
            <div class="item">
                <div class="item-header">
                    <span class="item-quantity">{{ $item->quantity }}x</span>
                    <span>{{ $item->product->name }}</span>
                </div>
                @if($item->notes)
                    <div class="item-notes">
                        Nota: {{ $item->notes }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if($order->kitchen_notes)
        <div class="notes-section">
            <strong>NOTAS PARA COCINA:</strong>
            {{ $order->kitchen_notes }}
        </div>
    @endif

    <div class="footer">
        <p>{{ tenant()->restaurant()->name }}</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
