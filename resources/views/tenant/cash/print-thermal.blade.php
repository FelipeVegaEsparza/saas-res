<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja - {{ $cashSession->closed_at->format('d/m/Y') }}</title>
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
            font-size: 11px;
            line-height: 1.4;
            padding: 6mm 4mm;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .restaurant-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .report-date {
            font-size: 10px;
        }

        .section {
            margin-bottom: 12px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 8px;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 6px;
            text-align: center;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            padding: 0 2px;
        }

        .info-label {
            font-weight: bold;
            font-size: 10px;
        }

        .payment-method {
            margin-bottom: 8px;
            padding: 4px;
            border: 1px solid #ddd;
        }

        .method-header {
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            font-size: 12px;
        }

        .method-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 2px;
        }

        .method-total {
            font-weight: bold;
            border-top: 1px solid #ccc;
            padding-top: 3px;
            margin-top: 3px;
        }

        .difference-positive {
            color: #000;
        }

        .difference-negative {
            color: #000;
        }

        .table-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            padding: 1px 2px;
        }

        .table-header {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }

        .total-section {
            background: #f0f0f0;
            padding: 6px;
            margin: 8px 0;
            border: 1px solid #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-final {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 3px;
        }

        .footer {
            text-align: center;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 2px dashed #000;
            font-size: 9px;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="restaurant-name">{{ tenant()->restaurant()->name }}</div>
        <div class="report-title">CIERRE DE CAJA</div>
        <div class="report-date">{{ $cashSession->closed_at->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Información Básica -->
    <div class="section">
        <div class="section-title">INFORMACION</div>
        <div class="info-row">
            <span class="info-label">Cajero:</span>
            <span>{{ $cashSession->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Apertura:</span>
            <span>{{ $cashSession->opened_at->format('d/m H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Cierre:</span>
            <span>{{ $cashSession->closed_at->format('d/m H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Duracion:</span>
            <span>{{ $cashSession->opened_at->diffForHumans($cashSession->closed_at, true) }}</span>
        </div>
    </div>

    <!-- Efectivo -->
    <div class="section">
        <div class="payment-method">
            <div class="method-header">💵 EFECTIVO</div>
            <div class="method-row">
                <span>Inicial:</span>
                <span>@price($cashSession->opening_balance)</span>
            </div>
            <div class="method-row">
                <span>Ventas:</span>
                <span>@price($cashSession->expected_cash - $cashSession->opening_balance)</span>
            </div>
            <div class="method-row">
                <span>Propinas:</span>
                <span>@price($cashSession->tips_cash)</span>
            </div>
            <div class="method-total">
                <div class="method-row">
                    <span>Esperado:</span>
                    <span>@price($cashSession->expected_cash)</span>
                </div>
                <div class="method-row">
                    <span>Contado:</span>
                    <span>@price($cashSession->counted_cash)</span>
                </div>
                <div class="method-row">
                    <span>Diferencia:</span>
                    <span>@price($cashSession->difference_cash)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta -->
    @if($cashSession->expected_card > 0 || $cashSession->counted_card > 0)
    <div class="section">
        <div class="payment-method">
            <div class="method-header">💳 TARJETA</div>
            <div class="method-row">
                <span>Ventas:</span>
                <span>@price($cashSession->expected_card)</span>
            </div>
            <div class="method-row">
                <span>Propinas:</span>
                <span>@price($cashSession->tips_card)</span>
            </div>
            <div class="method-total">
                <div class="method-row">
                    <span>Esperado:</span>
                    <span>@price($cashSession->expected_card)</span>
                </div>
                <div class="method-row">
                    <span>Registrado:</span>
                    <span>@price($cashSession->counted_card)</span>
                </div>
                <div class="method-row">
                    <span>Diferencia:</span>
                    <span>@price($cashSession->difference_card)</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Transferencia -->
    @if($cashSession->expected_transfer > 0 || $cashSession->counted_transfer > 0)
    <div class="section">
        <div class="payment-method">
            <div class="method-header">🏦 TRANSFERENCIA</div>
            <div class="method-row">
                <span>Ventas:</span>
                <span>@price($cashSession->expected_transfer)</span>
            </div>
            <div class="method-row">
                <span>Propinas:</span>
                <span>@price($cashSession->tips_transfer)</span>
            </div>
            <div class="method-total">
                <div class="method-row">
                    <span>Esperado:</span>
                    <span>@price($cashSession->expected_transfer)</span>
                </div>
                <div class="method-row">
                    <span>Registrado:</span>
                    <span>@price($cashSession->counted_transfer)</span>
                </div>
                <div class="method-row">
                    <span>Diferencia:</span>
                    <span>@price($cashSession->difference_transfer)</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Resumen de Ventas -->
    @if($paymentsByMethod->count() > 0)
    <div class="section">
        <div class="section-title">RESUMEN VENTAS</div>
        <div class="table-header table-row">
            <span>Metodo</span>
            <span>Cant</span>
            <span>Total</span>
        </div>
        @foreach($paymentsByMethod as $payment)
            <div class="table-row">
                <span>
                    @if($payment->payment_method === 'cash')
                        Efectivo
                    @elseif($payment->payment_method === 'card')
                        Tarjeta
                    @else
                        Transfer
                    @endif
                </span>
                <span>{{ $payment->count }}</span>
                <span>@price($payment->total)</span>
            </div>
        @endforeach
        <div class="table-row" style="font-weight: bold; border-top: 1px solid #000; padding-top: 2px;">
            <span>TOTAL</span>
            <span>{{ $paymentsByMethod->sum('count') }}</span>
            <span>@price($paymentsByMethod->sum('total'))</span>
        </div>
    </div>
    @endif

    <!-- Propinas por Mesero -->
    @if($tipsByWaiter->count() > 0)
    <div class="section">
        <div class="section-title">PROPINAS MESEROS</div>
        <div class="table-header table-row">
            <span>Mesero</span>
            <span>Ped</span>
            <span>Propina</span>
        </div>
        @foreach($tipsByWaiter as $tipData)
            <div class="table-row">
                <span>{{ Str::limit($tipData['waiter_name'], 12) }}</span>
                <span>{{ $tipData['orders_count'] }}</span>
                <span>@price($tipData['total_tips'])</span>
            </div>
        @endforeach
        <div class="table-row" style="font-weight: bold; border-top: 1px solid #000; padding-top: 2px;">
            <span>TOTAL</span>
            <span>{{ $tipsByWaiter->sum('orders_count') }}</span>
            <span>@price($tipsByWaiter->sum('total_tips'))</span>
        </div>
    </div>
    @endif

    <!-- Totales Finales -->
    <div class="total-section">
        <div class="total-row">
            <span>Total Esperado:</span>
            <span>@price($cashSession->expected_balance)</span>
        </div>
        <div class="total-row">
            <span>Total Contado:</span>
            <span>@price($cashSession->closing_balance)</span>
        </div>
        <div class="total-row total-final">
            <span>DIFERENCIA:</span>
            <span>@price($cashSession->difference)</span>
        </div>
    </div>

    @if($cashSession->closing_notes)
    <div class="section">
        <div class="section-title">NOTAS</div>
        <div style="font-size: 10px; text-align: justify;">
            {{ $cashSession->closing_notes }}
        </div>
    </div>
    @endif

    <div class="footer">
        <div>¡Gracias por su trabajo!</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 100);
        };
    </script>
</body>
</html>
