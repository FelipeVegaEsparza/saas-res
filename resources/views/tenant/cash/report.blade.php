@extends('tenant.layouts.admin')

@section('title', 'Reporte de Caja')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Reporte de Caja</h1>
            <p class="text-muted">Sesión cerrada el {{ $cashSession->closed_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('tenant.path.cash.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-secondary">
            <i class="ri ri-arrow-left-line me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información de la Sesión -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Sesión</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label text-muted small">Cajero</label>
                        <p class="mb-0"><strong>{{ $cashSession->user->name }}</strong></p>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small">Estado</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">Cerrada</span>
                        </p>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small">Apertura</label>
                        <p class="mb-0"><strong>{{ $cashSession->opened_at->format('d/m/Y H:i') }}</strong></p>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small">Cierre</label>
                        <p class="mb-0"><strong>{{ $cashSession->closed_at->format('d/m/Y H:i') }}</strong></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">Duración</label>
                        <p class="mb-0"><strong>{{ $cashSession->opened_at->diffForHumans($cashSession->closed_at, true) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Efectivo -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumen de Efectivo</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label text-muted small">Efectivo Inicial</label>
                        <p class="mb-0"><strong>${{ number_format($cashSession->opening_balance, 2) }}</strong></p>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small">Efectivo Final</label>
                        <p class="mb-0"><strong>${{ number_format($cashSession->closing_balance, 2) }}</strong></p>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small">Efectivo Esperado</label>
                        <p class="mb-0"><strong>${{ number_format($cashSession->expected_balance, 2) }}</strong></p>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small">Diferencia</label>
                        <p class="mb-0">
                            <strong class="{{ $cashSession->difference >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($cashSession->difference, 2) }}
                            </strong>
                        </p>
                    </div>
                </div>

                @if($cashSession->closing_notes)
                    <div class="mt-3">
                        <label class="form-label text-muted small">Notas de Cierre</label>
                        <p class="mb-0">{{ $cashSession->closing_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ventas por Método de Pago -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ventas por Método de Pago</h5>
            </div>
            <div class="card-body">
                @if($paymentsByMethod->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentsByMethod as $payment)
                                    <tr>
                                        <td>
                                            @if($payment->payment_method === 'cash')
                                                <i class="ri ri-cash-line me-1"></i> Efectivo
                                            @elseif($payment->payment_method === 'card')
                                                <i class="ri ri-bank-card-line me-1"></i> Tarjeta
                                            @else
                                                <i class="ri ri-exchange-line me-1"></i> Transferencia
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $payment->count }}</td>
                                        <td class="text-end"><strong>${{ number_format($payment->total, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ $paymentsByMethod->sum('count') }}</strong></td>
                                    <td class="text-end"><strong>${{ number_format($paymentsByMethod->sum('total'), 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No hay ventas registradas</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Detalle de Ventas -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detalle de Ventas</h5>
            </div>
            <div class="card-body">
                @if($cashSession->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Hora</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashSession->payments as $payment)
                                    <tr>
                                        <td>
                                            @if($payment->order)
                                                {{ $payment->order->order_number }}
                                            @elseif($payment->deliveryOrder)
                                                {{ $payment->deliveryOrder->order_number }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $payment->created_at->format('H:i') }}</td>
                                        <td class="text-end">${{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No hay ventas registradas</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Propinas por Mesero -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Propinas por Mesero</h5>
            </div>
            <div class="card-body">
                @if($tipsByWaiter->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mesero</th>
                                    <th class="text-end">Pedidos</th>
                                    <th class="text-end">Total Propinas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tipsByWaiter as $tipData)
                                    <tr>
                                        <td>{{ $tipData['waiter_name'] }}</td>
                                        <td class="text-end">{{ $tipData['orders_count'] }}</td>
                                        <td class="text-end"><strong>${{ number_format($tipData['total_tips'], 2) }}</strong></td>
                                    </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ $tipsByWaiter->sum('orders_count') }}</strong></td>
                                    <td class="text-end"><strong>${{ number_format($tipsByWaiter->sum('total_tips'), 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No hay propinas registradas</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <button class="btn btn-primary" onclick="window.print()">
        <i class="ri ri-printer-line me-1"></i> Imprimir Reporte
    </button>
</div>
@endsection
