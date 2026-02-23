<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'settings' => 'required|array',
                'settings.*' => 'nullable',
                'settings.company_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            foreach ($request->input('settings', []) as $key => $value) {
                $setting = SystemSetting::where('key', $key)->first();

                if (!$setting) {
                    continue;
                }

                // Manejar subida de archivos
                if ($key === 'company_logo' && $request->hasFile("settings.{$key}")) {
                    try {
                        \Log::info('Intentando subir logo...');

                        // Verificar que el disco público existe
                        $publicPath = storage_path('app/public');
                        \Log::info('Public path: ' . $publicPath);
                        \Log::info('Public path exists: ' . (file_exists($publicPath) ? 'yes' : 'no'));
                        \Log::info('Public path writable: ' . (is_writable($publicPath) ? 'yes' : 'no'));

                        // Asegurar que el directorio existe
                        if (!\Storage::disk('public')->exists('logos')) {
                            \Log::info('Creando directorio logos...');
                            \Storage::disk('public')->makeDirectory('logos');
                        }

                        // Eliminar logo anterior si existe
                        if ($setting->value && \Storage::disk('public')->exists($setting->value)) {
                            \Log::info('Eliminando logo anterior: ' . $setting->value);
                            \Storage::disk('public')->delete($setting->value);
                        }

                        // Guardar nuevo logo
                        $file = $request->file("settings.{$key}");
                        \Log::info('Archivo recibido: ' . $file->getClientOriginalName());

                        $path = $file->store('logos', 'public');
                        \Log::info('Logo guardado en: ' . $path);

                        $value = $path;
                    } catch (\Exception $e) {
                        \Log::error('Error al subir logo: ' . $e->getMessage());
                        \Log::error('Stack trace: ' . $e->getTraceAsString());
                        throw $e;
                    }
                }

                if ($value !== null || $key === 'company_logo') {
                    SystemSetting::set($key, $value ?? '', $setting->type, $setting->group);
                }
            }

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Configuraciones actualizadas exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar configuraciones: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Error al actualizar configuraciones: ' . $e->getMessage());
        }
    }
}
