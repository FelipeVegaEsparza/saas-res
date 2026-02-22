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

        // Obtener cuentas cerradas pendientes de pago
        $pendingOrders = Order::whereIn('status', ['closed'])
            ->with(['table', 'waiter', 'items.product'])
            ->orderBy('closed_at', 'desc')
            ->get();

        return view('tenant.cash.index', compact('activeSession', 'sessions', 'pendingOrders'));
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

        $validated = $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string',
        ]);

        // Calcular totales de pagos en efectivo
        $payments = Payment::where('cash_session_id', $cashSession->id)->get();
        $totalCash = $payments->where('payment_method', 'cash')->sum('amount');

        // El balance esperado es el balance inicial + efectivo recibido
        $expectedBalance = $cashSession->opening_balance + $totalCash;
        $difference = $validated['closing_balance'] - $expectedBalance;

        $cashSession->update([
            'closed_at' => now(),
            'closing_balance' => $validated['closing_balance'],
            'expected_balance' => $expectedBalance,
            'difference' => $difference,
            'status' => 'closed',
            'closing_notes' => $validated['closing_notes'] ?? null,
        ]);

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
            'order_id' => 'required|exists:tenant.orders,id',
            'payment_method' => 'required|in:cash,card,transfer',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$activeSession) {
            return response()->json(['error' => 'No hay sesión de caja abierta'], 400);
        }

        $order = Order::with(['table', 'items.product'])->findOrFail($validated['order_id']);

        if (!$order->canBePaid()) {
            return response()->json(['error' => 'Esta orden no puede ser pagada'], 400);
        }

        DB::connection('tenant')->beginTransaction();

        try {
            // Crear pago
            $payment = Payment::create([
                'order_id' => $order->id,
                'cash_session_id' => $activeSession->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $order->total,
                'amount_paid' => $validated['amount_paid'],
                'change' => $validated['amount_paid'] - $order->total,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Rebajar stock de productos
            foreach ($order->items as $item) {
                if ($item->product && $item->product->track_stock) {
                    $item->product->reduceStock($item->quantity);
                }
            }

            // Marcar orden como pagada
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Liberar mesa
            if ($order->table) {
                $order->table->free();
            }

            DB::connection('tenant')->commit();

            return response()->json([
                'success' => true,
                'order' => $order,
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

        $cashSession->load(['payments.order.items.product']);

        $paymentsByMethod = $cashSession->payments()
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('tenant.cash.report', compact('cashSession', 'paymentsByMethod'));
    }
}
