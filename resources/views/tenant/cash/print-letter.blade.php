<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cierre de Caja - {{ $cashSession->closed_at->format('d/m/Y') }}</title>
    <style>
        @page {
            size: letter;
            margin: 2cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .restaurant-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-date {
            font-size: 14px;
            color: #666;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            background: #f5f5f5;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .table .text-end {
            text-align: right;
        }

        .table .text-center {
            text-align: center;
        }

        .totals-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .total-row.final {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 15px;
        }

        .difference-positive {
            color: #28a745;
        }

        .difference-negative {
            color: #dc3545;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .payment-method-card.cash {
            border-color: #28a745;
            background: #f8fff9;
        }

        .payment-method-card.card {
            border-color: #007bff;
            background: #f8fbff;
        }

        .payment-method-card.transfer {
            border-color: #17a2b8;
            background: #f8feff;
        }

        .method-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .method-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .method-amounts {
            font-size: 11px;
            color: #666;
        }

        .method-total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="restaurant-name">{{ tenant()->restaurant()->name }}</div>
        <div class="report-title">REPORTE DE CIERRE DE CAJA</div>
        <div class="report-date">{{ $cashSession->closed_at->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Información de la Sesión -->
    <div class="section">
        <div class="section-title">Información de la Sesión</div>
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Cajero</div>
                <div class="info-value">{{ $cashSession->user->name }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Duración</div>
                <div class="info-value">{{ $cashSession->opened_at->diffForHumans($cashSession->closed_at, true) }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Apertura</div>
                <div class="info-value">{{ $cashSession->opened_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Cierre</div>
                <div class="info-value">{{ $cashSession->closed_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Desglose por Método de Pago -->
    <div class="section">
        <div class="section-title">Desglose por Método de Pago</div>
        <div class="payment-methods">
            <div class="payment-method-card cash">
                <div class="method-icon">💵</div>
                <div class="method-title">EFECTIVO</div>
                <div class="method-amounts">
                    Inicial: @price($cashSession->opening_balance)<br>
                    Ventas: @price($cashSession->expected_cash - $cashSession->opening_balance)<br>
                    Propinas: @price($cashSession->tips_cash)
                </div>
                <div class="method-total">
                    Esperado: @price($cashSession->expected_cash)<br>
                    Contado: @price($cashSession->counted_cash)<br>
                    <span class="{{ $cashSession->difference_cash >= 0 ? 'difference-positive' : 'difference-negative' }}">
                        Diferencia: @price($cashSession->difference_cash)
                    </span>
                </div>
            </div>

            <div class="payment-method-card card">
                <div class="method-icon">💳</div>
                <div class="method-title">TARJETA</div>
                <div class="method-amounts">
                    Ventas: @price($cashSession->expected_card)<br>
                    Propinas: @price($cashSession->tips_card)
                </div>
                <div class="method-total">
                    Esperado: @price($cashSession->expected_card)<br>
                    Registrado: @price($cashSession->counted_card)<br>
                    <span class="{{ $cashSession->difference_card >= 0 ? 'difference-positive' : 'difference-negative' }}">
                        Diferencia: @price($cashSession->difference_card)
                    </span>
                </div>
            </div>

            <div class="payment-method-card transfer">
                <div class="method-icon">🏦</div>
                <div class="method-title">TRANSFERENCIA</div>
                <div class="method-amounts">
                    Ventas: @price($cashSession->expected_transfer)<br>
                    Propinas: @price($cashSession->tips_transfer)
                </div>
                <div class="method-total">
                    Esperado: @price($cashSession->expected_transfer)<br>
                    Registrado: @price($cashSession->counted_transfer)<br>
                    <span class="{{ $cashSession->difference_transfer >= 0 ? 'difference-positive' : 'difference-negative' }}">
                        Diferencia: @price($cashSession->difference_transfer)
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Ventas -->
    <div class="section">
        <div class="section-title">Resumen de Ventas</div>
        @if($paymentsByMethod->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Método de Pago</th>
                        <th class="text-center">Cantidad de Transacciones</th>
                        <th class="text-end">Total Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentsByMethod as $payment)
                        <tr>
                            <td>
                                @if($payment->payment_method === 'cash')
                                    💵 Efectivo
                                @elseif($payment->payment_method === 'card')
                                    💳 Tarjeta
                                @else
                                    🏦 Transferencia
                                @endif
                            </td>
                            <td class="text-center">{{ $payment->count }}</td>
                            <td class="text-end">@price($payment->total)</td>
                        </tr>
                    @endforeach
                    <tr style="background: #f8f9fa; font-weight: bold;">
                        <td>TOTAL</td>
                        <td class="text-center">{{ $paymentsByMethod->sum('count') }}</td>
                        <td class="text-end">@price($paymentsByMethod->sum('total'))</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    <!-- Propinas por Mesero -->
    @if($tipsByWaiter->count() > 0)
    <div class="section">
        <div class="section-title">Propinas por Mesero</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Mesero</th>
                    <th class="text-center">Pedidos Atendidos</th>
                    <th class="text-end">Total Propinas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipsByWaiter as $tipData)
                    <tr>
                        <td>{{ $tipData['waiter_name'] }}</td>
                        <td class="text-center">{{ $tipData['orders_count'] }}</td>
                        <td class="text-end">@price($tipData['total_tips'])</td>
                    </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td>TOTAL</td>
                    <td class="text-center">{{ $tipsByWaiter->sum('orders_count') }}</td>
                    <td class="text-end">@price($tipsByWaiter->sum('total_tips'))</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <!-- Totales Finales -->
    <div class="totals-section">
        <div class="total-row">
            <span>Total Esperado:</span>
            <span>@price($cashSession->expected_balance)</span>
        </div>
        <div class="total-row">
            <span>Total Contado:</span>
            <span>@price($cashSession->closing_balance)</span>
        </div>
        <div class="total-row final">
            <span>Diferencia Total:</span>
            <span class="{{ $cashSession->difference >= 0 ? 'difference-positive' : 'difference-negative' }}">
                @price($cashSession->difference)
            </span>
        </div>
    </div>

    @if($cashSession->closing_notes)
    <div class="section">
        <div class="section-title">Notas de Cierre</div>
        <p>{{ $cashSession->closing_notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>{{ tenant()->restaurant()->name }} - Sistema de Gestión</p>
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
