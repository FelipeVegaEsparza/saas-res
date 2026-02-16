<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Table;
use Illuminate\Http\Request;

class QRController extends Controller
{
    /**
     * Generar QR para una mesa
     */
    public function generate($tableId)
    {
        $table = Table::findOrFail($tableId);
        $restaurant = tenant()->restaurant();

        // URL del menú con código de mesa
        $menuUrl = $restaurant->menu_url . '?table=' . $table->qr_code;

        // Generar QR usando API externa (Google Charts)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($menuUrl);

        return view('tenant.qr.generate', compact('table', 'restaurant', 'menuUrl', 'qrUrl'));
    }

    /**
     * Descargar QR de una mesa
     */
    public function download($tableId)
    {
        $table = Table::findOrFail($tableId);
        $restaurant = tenant()->restaurant();

        $menuUrl = $restaurant->menu_url . '?table=' . $table->qr_code;

        // Redirigir a la API de QR con tamaño más grande para impresión
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&format=png&data=' . urlencode($menuUrl);

        return redirect($qrUrl);
    }

    /**
     * Vista para imprimir todos los QR
     */
    public function printAll()
    {
        $restaurant = tenant()->restaurant();
        $tables = Table::where('active', true)->orderBy('number')->get();

        $tablesWithQR = $tables->map(function ($table) use ($restaurant) {
            $menuUrl = $restaurant->menu_url . '?table=' . $table->qr_code;
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($menuUrl);

            return [
                'table' => $table,
                'menuUrl' => $menuUrl,
                'qrUrl' => $qrUrl,
            ];
        });

        return view('tenant.qr.print-all', compact('restaurant', 'tablesWithQR'));
    }
}
