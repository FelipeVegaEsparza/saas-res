<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\CashSession;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CashRegisterController extends Controller
{
    public function index()
    {
        $activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        $sessions = CashSession::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener cuentas cerradas pendientes de pago (pedidos de mesa)
        $pendingOrders = Order::whereIn('status', ['closed'])
            ->with(['table', 'waiter', 'items.product'])
            ->orderBy('closed_at', 'desc')
            ->get();

        // Obtener pedidos de delivery entregados pendientes de pago
        $pendingDeliveryOrders = \App\Models\Tenant\DeliveryOrder::where('status', 'delivered')
            ->where('payment_status', 'pending')
            ->with(['items.product'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Calcular montos esperados por método de pago para la sesión activa
        $expectedAmounts = null;
        if ($activeSession) {
            try {
                $payments = Payment::where('cash_session_id', $activeSession->id)->get();

                $expectedAmounts = [
                    'cash' => $activeSession->opening_balance + $payments->where('payment_method', 'cash')->sum('amount_paid'),
                    'card' => $payments->where('payment_method', 'card')->sum('amount'),
                    'transfer' => $payments->where('payment_method', 'transfer')->sum('amount'),
                    'tips_cash' => $payments->where('payment_method', 'cash')->sum('tip'),
                    'tips_card' => $payments->where('payment_method', 'card')->sum('tip'),
                    'tips_transfer' => $payments->where('payment_method', 'transfer')->sum('tip'),
                ];
            } catch (\Exception $e) {
                // Si hay error (campo tip no existe), usar valores por defecto
                $payments = Payment::where('cash_session_id', $activeSession->id)->get();

                $expectedAmounts = [
                    'cash' => $activeSession->opening_balance + $payments->where('payment_method', 'cash')->sum('amount'),
                    'card' => $payments->where('payment_method', 'card')->sum('amount'),
                    'transfer' => $payments->where('payment_method', 'transfer')->sum('amount'),
                    'tips_cash' => 0,
                    'tips_card' => 0,
                    'tips_transfer' => 0,
                ];
            }
        }

        return view('tenant.cash.index', compact('activeSession', 'sessions', 'pendingOrders', 'pendingDeliveryOrders', 'expectedAmounts'));
    }

    public function openSession(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'opening_notes' => 'nullable|string',
        ]);

        // Verificar que no haya una sesión abierta
        $existingSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return back()->with('error', 'Ya tienes una sesión de caja abierta');
        }

        $session = CashSession::create([
            'user_id' => Auth::id(),
            'opened_at' => now(),
            'opening_balance' => $validated['opening_balance'],
            'status' => 'open',
            'opening_notes' => $validated['opening_notes'] ?? null,
        ]);

        return redirect()
            ->route('tenant.path.cash.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Sesión de caja abierta exitosamente');
    }

    public function closeSession(Request $request, $tenant, CashSession $cashSession)
    {
        if ($cashSession->status !== 'open') {
            return back()->with('error', 'Esta sesión ya está cerrada');
        }

        // Verificar que no haya pedidos pendientes de mesas (sin cobrar)
        $pendingTableOrders = Order::whereIn('status', ['pending', 'preparing', 'ready', 'served', 'closed'])
            ->count();

        if ($pendingTableOrders > 0) {
            return back()->with('error', "No se puede cerrar la caja. Hay {$pendingTableOrders} pedido(s) de mesa pendiente(s) de cobrar o cerrar.");
        }

        // Verificar que no haya pedidos de delivery pendientes de cobrar
        $pendingDeliveryOrders = \App\Models\Tenant\DeliveryOrder::where(function($query) {
                $query->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'on_delivery'])
                      ->orWhere(function($q) {
                          $q->where('status', 'delivered')
                            ->where('payment_status', 'pending');
                      });
            })
            ->count();

        if ($pendingDeliveryOrders > 0) {
            return back()->with('error', "No se puede cerrar la caja. Hay {$pendingDeliveryOrders} pedido(s) de delivery pendiente(s) de cobrar o completar.");
        }

        // Verificar qué campos existen en la tabla para validación condicional
        $hasDetailedFields = Schema::hasColumns('cash_sessions', [
            'expected_cash', 'expected_card', 'expected_transfer'
        ]);

        // Validar campos según disponibilidad
        if ($hasDetailedFields) {
            // Validar campos de cierre detallado por método de pago
            $validated = $request->validate([
                'counted_cash' => 'required|numeric|min:0',
                'counted_card' => 'required|numeric|min:0',
                'counted_transfer' => 'required|numeric|min:0',
                'closing_notes' => 'nullable|string',
            ]);
        } else {
            // Validación básica para compatibilidad con versiones anteriores
            $validated = $request->validate([
                'closing_balance' => 'nullable|numeric|min:0',
                'counted_cash' => 'nullable|numeric|min:0',
                'closing_notes' => 'nullable|string',
            ]);
        }

        // Calcular totales esperados por método de pago
        $payments = Payment::where('cash_session_id', $cashSession->id)->get();

        // Calcular montos esperados por método de pago (incluyendo propinas)
        $expectedCash = $payments->where('payment_method', 'cash')->sum('amount_paid') ?? 0;
        $expectedCard = $payments->where('payment_method', 'card')->sum('amount_paid') ?? 0;
        $expectedTransfer = $payments->where('payment_method', 'transfer')->sum('amount_paid') ?? 0;

        // Calcular propinas por método de pago
        $tipsCash = $payments->where('payment_method', 'cash')->sum('tip') ?? 0;
        $tipsCard = $payments->where('payment_method', 'card')->sum('tip') ?? 0;
        $tipsTransfer = $payments->where('payment_method', 'transfer')->sum('tip') ?? 0;

        // Calcular diferencias y totales según disponibilidad de campos
        if ($hasDetailedFields) {
            // Calcular diferencias por método de pago
            $differenceCash = $validated['counted_cash'] - $expectedCash;
            $differenceCard = $validated['counted_card'] - $expectedCard;
            $differenceTransfer = $validated['counted_transfer'] - $expectedTransfer;

            $closingBalance = $validated['counted_cash'];
            $totalDifference = $differenceCash + $differenceCard + $differenceTransfer;
        } else {
            // Compatibilidad con versión anterior - solo efectivo
            $closingBalance = $validated['counted_cash'] ?? $validated['closing_balance'] ?? 0;
            $expectedBalance = $cashSession->opening_balance + $expectedCash;
            $totalDifference = $closingBalance - $expectedBalance;

            // Valores por defecto para campos no disponibles
            $differenceCash = $totalDifference;
            $differenceCard = 0;
            $differenceTransfer = 0;
        }

        // Totales generales
        $expectedBalance = $cashSession->opening_balance + $expectedCash;

        // Actualizar sesión con campos básicos
        $updateData = [
            'closed_at' => now(),
            'closing_balance' => $closingBalance,
            'expected_balance' => $expectedBalance,
            'difference' => $totalDifference,
            'status' => 'closed',
            'closing_notes' => $validated['closing_notes'] ?? null,
        ];

        // Agregar campos detallados solo si existen en la tabla
        if ($hasDetailedFields) {
            $updateData = array_merge($updateData, [
                'expected_cash' => $expectedCash,
                'expected_card' => $expectedCard,
                'expected_transfer' => $expectedTransfer,
                'counted_cash' => $validated['counted_cash'],
                'counted_card' => $validated['counted_card'],
                'counted_transfer' => $validated['counted_transfer'],
                'difference_cash' => $differenceCash,
                'difference_card' => $differenceCard,
                'difference_transfer' => $differenceTransfer,
                'tips_cash' => $tipsCash,
                'tips_card' => $tipsCard,
                'tips_transfer' => $tipsTransfer,
            ]);
        }

        $cashSession->update($updateData);

        return redirect()
            ->route('tenant.path.cash.report', ['tenant' => request()->route('tenant'), 'cashSession' => $cashSession])
            ->with('success', 'Sesión de caja cerrada exitosamente');
    }

    public function pos()
    {
        $activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$activeSession) {
            return redirect()
                ->route('tenant.path.cash.index', ['tenant' => request()->route('tenant')])
                ->with('error', 'Debes abrir una sesión de caja primero');
        }

        $categories = Category::where('active', true)
            ->withCount('products')
            ->orderBy('order')
            ->get();

        $products = Product::where('available', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('tenant.cash.pos', compact('activeSession', 'categories', 'products'));
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:tenant.products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,transfer,credit',
            'amount_paid' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:tenant.customers,id',
            'tip' => 'nullable|numeric|min:0',
        ]);

        $activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$activeSession) {
            return response()->json(['error' => 'No hay sesión de caja abierta'], 400);
        }

        // Validar crédito directo
        if ($validated['payment_method'] === 'credit') {
            if (!$validated['customer_id']) {
                return response()->json(['error' => 'Cliente requerido para pago con crédito'], 400);
            }

            $customer = \App\Models\Tenant\Customer::findOrFail($validated['customer_id']);
            $total = collect($validated['items'])->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            if (!$customer->hasAvailableCredit($total)) {
                return response()->json(['error' => 'Crédito insuficiente'], 400);
            }
        }

        DB::connection('tenant')->beginTransaction();

        try {
            // Crear orden directa (sin mesa)
            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(uniqid()),
                'customer_id' => $validated['customer_id'],
                'user_id' => Auth::id(),
                'type' => 'pos',
                'status' => 'paid',
                'subtotal' => 0,
                'total' => 0,
                'paid_at' => now(),
            ]);

            $subtotal = 0;

            // Crear items de la orden
            foreach ($validated['items'] as $itemData) {
                $product = \App\Models\Tenant\Product::findOrFail($itemData['product_id']);
                $itemSubtotal = $itemData['price'] * $itemData['quantity'];
                $subtotal += $itemSubtotal;

                \App\Models\Tenant\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemSubtotal,
                ]);

                // Rebajar stock
                if ($product->track_stock) {
                    $product->reduceStock($itemData['quantity']);
                }
            }

            // Actualizar totales de la orden
            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            $tip = $validated['tip'] ?? 0;

            // Procesar pago según método
            if ($validated['payment_method'] === 'credit') {
                // Usar crédito del cliente
                $customer = \App\Models\Tenant\Customer::findOrFail($validated['customer_id']);
                $customer->useCredit($subtotal, "Compra POS - {$order->order_number}", $order->id);

                // Crear registro de pago
                \App\Models\Tenant\Payment::create([
                    'order_id' => $order->id,
                    'cash_session_id' => $activeSession->id,
                    'payment_method' => 'credit',
                    'amount' => $subtotal,
                    'amount_paid' => $subtotal,
                    'tip' => $tip,
                    'change' => 0,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            } else {
                // Pago tradicional
                \App\Models\Tenant\Payment::create([
                    'order_id' => $order->id,
                    'cash_session_id' => $activeSession->id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $subtotal,
                    'amount_paid' => $validated['amount_paid'],
                    'tip' => $tip,
                    'change' => $validated['amount_paid'] - $subtotal - $tip,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }

            DB::connection('tenant')->commit();

            return response()->json([
                'success' => true,
                'order' => $order->load(['items.product', 'customer']),
                'message' => 'Venta procesada exitosamente',
            ]);

        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            return response()->json(['error' => 'Error al procesar la venta: ' . $e->getMessage()], 500);
        }
    }

    public function processDeliveryPayment(Request $request)
    {
        $validated = $request->validate([
            'delivery_order_id' => 'required|exists:tenant.delivery_orders,id',
            'payment_method' => 'required|in:cash,card,transfer',
            'amount_paid' => 'required|numeric|min:0',
            'tip' => 'nullable|numeric|min:0',
        ]);

        $activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$activeSession) {
            return response()->json(['error' => 'No hay sesión de caja abierta'], 400);
        }

        $deliveryOrder = \App\Models\Tenant\DeliveryOrder::with(['items.product'])->findOrFail($validated['delivery_order_id']);

        if ($deliveryOrder->payment_status !== 'pending') {
            return response()->json(['error' => 'Este pedido ya fue pagado'], 400);
        }

        DB::connection('tenant')->beginTransaction();

        try {
            $tip = $validated['tip'] ?? 0;

            // Crear pago
            $payment = Payment::create([
                'delivery_order_id' => $deliveryOrder->id,
                'cash_session_id' => $activeSession->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $deliveryOrder->total,
                'amount_paid' => $validated['amount_paid'],
                'tip' => $tip,
                'change' => $validated['amount_paid'] - $deliveryOrder->total - $tip,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Rebajar stock de productos
            foreach ($deliveryOrder->items as $item) {
                if ($item->product && $item->product->track_stock) {
                    $item->product->reduceStock($item->quantity);
                }
            }

            // Marcar pedido como pagado
            $deliveryOrder->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            DB::connection('tenant')->commit();

            return response()->json([
                'success' => true,
                'order' => $deliveryOrder,
                'payment' => $payment,
                'message' => 'Pago procesado exitosamente',
            ]);

        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            return response()->json(['error' => 'Error al procesar el pago: ' . $e->getMessage()], 500);
        }
    }

    public function report($tenant, CashSession $cashSession)
    {
        if ($cashSession->user_id !== Auth::id()) {
            abort(403);
        }

        $cashSession->load(['payments.order.items.product', 'payments.order.waiter', 'payments.deliveryOrder.items.product']);

        $paymentsByMethod = $cashSession->payments()
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Calcular propinas por mesero
        $tipsByWaiter = $cashSession->payments()
            ->whereHas('order')
            ->with('order.waiter')
            ->get()
            ->groupBy(function($payment) {
                return $payment->order->waiter_id ?? 'sin_mesero';
            })
            ->map(function($payments) {
                $waiter = $payments->first()->order->waiter ?? null;
                return [
                    'waiter_name' => $waiter ? $waiter->name : 'Sin Mesero',
                    'total_tips' => $payments->sum('tip'),
                    'orders_count' => $payments->count(),
                ];
            })
            ->sortByDesc('total_tips');

        return view('tenant.cash.report', compact('cashSession', 'paymentsByMethod', 'tipsByWaiter'));
    }
    public function printLetter($tenant, CashSession $cashSession)
    {
        if ($cashSession->user_id !== Auth::id()) {
            abort(403);
        }

        $cashSession->load(['payments.order.items.product', 'payments.order.waiter', 'payments.deliveryOrder.items.product']);

        $paymentsByMethod = $cashSession->payments()
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Calcular propinas por mesero
        $tipsByWaiter = $cashSession->payments()
            ->whereHas('order')
            ->with('order.waiter')
            ->get()
            ->groupBy(function($payment) {
                return $payment->order->waiter_id ?? 'sin_mesero';
            })
            ->map(function($payments) {
                $waiter = $payments->first()->order->waiter ?? null;
                return [
                    'waiter_name' => $waiter ? $waiter->name : 'Sin Mesero',
                    'total_tips' => $payments->sum('tip'),
                    'orders_count' => $payments->count(),
                ];
            })
            ->sortByDesc('total_tips');

        return view('tenant.cash.print-letter', compact('cashSession', 'paymentsByMethod', 'tipsByWaiter'));
    }

    public function printThermal($tenant, CashSession $cashSession)
    {
        if ($cashSession->user_id !== Auth::id()) {
            abort(403);
        }

        $cashSession->load(['payments.order.items.product', 'payments.order.waiter', 'payments.deliveryOrder.items.product']);

        $paymentsByMethod = $cashSession->payments()
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Calcular propinas por mesero
        $tipsByWaiter = $cashSession->payments()
            ->whereHas('order')
            ->with('order.waiter')
            ->get()
            ->groupBy(function($payment) {
                return $payment->order->waiter_id ?? 'sin_mesero';
            })
            ->map(function($payments) {
                $waiter = $payments->first()->order->waiter ?? null;
                return [
                    'waiter_name' => $waiter ? $waiter->name : 'Sin Mesero',
                    'total_tips' => $payments->sum('tip'),
                    'orders_count' => $payments->count(),
                ];
            })
            ->sortByDesc('total_tips');

        return view('tenant.cash.print-thermal', compact('cashSession', 'paymentsByMethod', 'tipsByWaiter'));
    }
}
