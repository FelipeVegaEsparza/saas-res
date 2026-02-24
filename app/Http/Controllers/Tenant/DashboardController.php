<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\Table;
use App\Models\Tenant\DeliveryOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard principal del restaurante - Redirige a mesas
     */
    public function index()
    {
        // Redirigir directamente a la vista de mesas
        return redirect()->route('tenant.path.tables.index', ['tenant' => request()->route('tenant')]);
    }
}
