<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index($tenant)
    {
        $restaurant = tenant()->restaurant();

        // Esquemas de color disponibles
        $colorSchemes = [
            'classic' => [
                'name' => 'Clásico',
                'primary' => '#2c3e50',
                'secondary' => '#e74c3c',
                'accent' => '#f39c12',
                'background' => '#ecf0f1',
                'text' => '#2c3e50',
            ],
            'modern' => [
                'name' => 'Moderno',
                'primary' => '#3498db',
                'secondary' => '#2ecc71',
                'accent' => '#9b59b6',
                'background' => '#ffffff',
                'text' => '#34495e',
            ],
            'elegant' => [
                'name' => 'Elegante',
                'primary' => '#1a1a1a',
                'secondary' => '#d4af37',
                'accent' => '#8b7355',
                'background' => '#f5f5f5',
                'text' => '#1a1a1a',
            ],
            'fresh' => [
                'name' => 'Fresco',
                'primary' => '#27ae60',
                'secondary' => '#16a085',
                'accent' => '#f1c40f',
                'background' => '#e8f8f5',
                'text' => '#2c3e50',
            ],
            'warm' => [
                'name' => 'Cálido',
                'primary' => '#e67e22',
                'secondary' => '#d35400',
                'accent' => '#c0392b',
                'background' => '#fef5e7',
                'text' => '#5d4037',
            ],
        ];

        return view('tenant.settings.index', compact('restaurant', 'colorSchemes'));
    }

    public function update(Request $request, $tenant)
    {
        $restaurant = tenant()->restaurant();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'menu_color_scheme' => 'required|in:classic,modern,elegant,fresh,warm',
            'logo_horizontal' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_square' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'menu_background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        // Manejar subida de logo horizontal
        if ($request->hasFile('logo_horizontal')) {
            // Eliminar logo anterior si existe
            if ($restaurant->logo_horizontal) {
                Storage::disk('public')->delete($restaurant->logo_horizontal);
            }
            $validated['logo_horizontal'] = $request->file('logo_horizontal')->store('logos', 'public');
        }

        // Manejar subida de logo cuadrado
        if ($request->hasFile('logo_square')) {
            if ($restaurant->logo_square) {
                Storage::disk('public')->delete($restaurant->logo_square);
            }
            $validated['logo_square'] = $request->file('logo_square')->store('logos', 'public');
        }

        // Manejar subida de imagen de fondo del menú
        if ($request->hasFile('menu_background_image')) {
            if ($restaurant->menu_background_image) {
                Storage::disk('public')->delete($restaurant->menu_background_image);
            }
            $validated['menu_background_image'] = $request->file('menu_background_image')->store('menu-backgrounds', 'public');
        }

        $restaurant->update($validated);

        return redirect()->route('tenant.path.settings.index', ['tenant' => $tenant])
            ->with('success', 'Configuración actualizada exitosamente');
    }

    public function downloadQR($tenant)
    {
        $restaurant = tenant()->restaurant();
        $menuUrl = url("/{$tenant}/menu");

        // Generar QR usando una librería simple
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" . urlencode($menuUrl);

        return redirect($qrCodeUrl);
    }
}
