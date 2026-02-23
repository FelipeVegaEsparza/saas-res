<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                'settings.company_favicon' => 'nullable|image|mimes:ico,png,jpg,svg|max:1024',
            ]);

            // Manejar subida de logo primero (antes del loop)
            if ($request->hasFile('settings.company_logo')) {
                try {
                    Log::info('Intentando subir logo...');

                    $setting = SystemSetting::where('key', 'company_logo')->first();

                    if ($setting) {
                        // Verificar que el disco público existe
                        $publicPath = storage_path('app/public');
                        Log::info('Public path: ' . $publicPath);
                        Log::info('Public path exists: ' . (file_exists($publicPath) ? 'yes' : 'no'));
                        Log::info('Public path writable: ' . (is_writable($publicPath) ? 'yes' : 'no'));

                        // Asegurar que el directorio existe
                        if (!Storage::disk('public')->exists('logos')) {
                            Log::info('Creando directorio logos...');
                            Storage::disk('public')->makeDirectory('logos');
                        }

                        // Eliminar logo anterior si existe
                        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                            Log::info('Eliminando logo anterior: ' . $setting->value);
                            Storage::disk('public')->delete($setting->value);
                        }

                        // Guardar nuevo logo
                        $file = $request->file('settings.company_logo');
                        Log::info('Archivo recibido: ' . $file->getClientOriginalName());

                        $path = $file->store('logos', 'public');
                        Log::info('Logo guardado en: ' . $path);

                        SystemSetting::set('company_logo', $path, $setting->type, $setting->group);
                    }
                } catch (\Exception $e) {
                    Log::error('Error al subir logo: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                    throw $e;
                }
            }

            // Manejar subida de favicon
            if ($request->hasFile('settings.company_favicon')) {
                try {
                    Log::info('Intentando subir favicon...');

                    $setting = SystemSetting::where('key', 'company_favicon')->first();

                    if ($setting) {
                        // Asegurar que el directorio existe
                        if (!Storage::disk('public')->exists('favicons')) {
                            Log::info('Creando directorio favicons...');
                            Storage::disk('public')->makeDirectory('favicons');
                        }

                        // Eliminar favicon anterior si existe
                        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                            Log::info('Eliminando favicon anterior: ' . $setting->value);
                            Storage::disk('public')->delete($setting->value);
                        }

                        // Guardar nuevo favicon
                        $file = $request->file('settings.company_favicon');
                        Log::info('Archivo recibido: ' . $file->getClientOriginalName());

                        $path = $file->store('favicons', 'public');
                        Log::info('Favicon guardado en: ' . $path);

                        SystemSetting::set('company_favicon', $path, $setting->type, $setting->group);
                    }
                } catch (\Exception $e) {
                    Log::error('Error al subir favicon: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                    throw $e;
                }
            }

            // Procesar el resto de configuraciones
            foreach ($request->input('settings', []) as $key => $value) {
                // Saltar company_logo y company_favicon ya que se procesaron arriba
                if ($key === 'company_logo' || $key === 'company_favicon') {
                    continue;
                }

                $setting = SystemSetting::where('key', $key)->first();

                if (!$setting) {
                    continue;
                }

                if ($value !== null) {
                    SystemSetting::set($key, $value, $setting->type, $setting->group);
                }
            }

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Configuraciones actualizadas exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar configuraciones: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Error al actualizar configuraciones: ' . $e->getMessage());
        }
    }
}
