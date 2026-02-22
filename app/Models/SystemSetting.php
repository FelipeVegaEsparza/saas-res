<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Obtener un valor de configuración
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("system_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Establecer un valor de configuración
     */
    public static function set($key, $value, $type = 'text', $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        Cache::forget("system_setting_{$key}");

        return $setting;
    }

    /**
     * Obtener todas las configuraciones de un grupo
     */
    public static function getGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key');
    }
}
