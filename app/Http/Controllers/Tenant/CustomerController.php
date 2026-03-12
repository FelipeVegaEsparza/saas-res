<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $customers = Customer::orderBy('name')->paginate(20);
            return view('tenant.customers.index', compact('customers'));
        } catch (\Exception $e) {
            $customers = collect()->paginate(20);
            return view('tenant.customers.index', compact('customers'));
        }
    }

    public function create()
    {
        return view('tenant.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'document_type' => 'nullable|string|max:50',
            'document_number' => 'nullable|string|max:50',
            'credit_limit' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $validated['active'] = true;
        $validated['credit_used'] = 0;

        Customer::create($validated);

        return redirect()->route('tenant.path.customers.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Cliente creado exitosamente');
    }

    public function show(Customer $customer)
    {
        return view('tenant.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('tenant.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'document_type' => 'nullable|string|max:50',
            'document_number' => 'nullable|string|max:50',
            'credit_limit' => 'required|numeric|min:0',
            'active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('tenant.path.customers.show', ['tenant' => request()->route('tenant'), 'customer' => $customer])
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->credit_used > 0) {
            return back()->with('error', 'No se puede eliminar un cliente con deuda pendiente');
        }

        $customer->delete();

        return redirect()->route('tenant.path.customers.index', ['tenant' => request()->route('tenant')])
            ->with('success', 'Cliente eliminado exitosamente');
    }

    public function addPayment(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();
            $customer->addPayment($request->amount, $request->description);
            DB::commit();

            return back()->with('success', 'Pago registrado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar el pago');
        }
    }

    public function adjustCredit(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();
            $customer->adjustCredit($request->amount, $request->description);
            DB::commit();

            return back()->with('success', 'Ajuste realizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al realizar el ajuste');
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');

        $customers = Customer::where('active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'phone', 'credit_limit', 'credit_used']);

        return response()->json([
            'results' => $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'text' => $customer->name . ($customer->phone ? ' (' . $customer->phone . ')' : ''),
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'credit_available' => $customer->credit_limit - $customer->credit_used,
                    'credit_limit' => $customer->credit_limit
                ];
            })
        ]);
    }

    public function creditReport()
    {
        try {
            $customersWithDebt = Customer::where('credit_used', '>', 0)
                ->orderBy('credit_used', 'desc')
                ->get();

            $totalDebt = $customersWithDebt->sum('credit_used');
            $totalCreditLimit = Customer::sum('credit_limit');

            return view('tenant.customers.credit-report', compact(
                'customersWithDebt',
                'totalDebt',
                'totalCreditLimit'
            ));
        } catch (\Exception $e) {
            return view('tenant.customers.credit-report', [
                'customersWithDebt' => collect(),
                'totalDebt' => 0,
                'totalCreditLimit' => 0
            ]);
        }
    }
}
