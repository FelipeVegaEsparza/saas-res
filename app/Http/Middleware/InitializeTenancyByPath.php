<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;

class InitializeTenancyByPath
{
    protected $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }

    public function handle(Request $request, Closure $next)
    {
        // Obtener el tenant del path (primer segmento de la URL)
        $tenantId = $request->route('tenant');

        if (!$tenantId) {
            abort(404, 'Tenant no especificado en la URL');
        }

        // Buscar el tenant
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            abort(404, "Tenant '{$tenantId}' no encontrado");
        }

        // Configurar manualmente la conexión tenant con la base de datos correcta
        $dbName = config('tenancy.database.prefix') . $tenant->getTenantKey() . config('tenancy.database.suffix');

        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => $dbName,
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
            'charset' => config('database.connections.mysql.charset'),
            'collation' => config('database.connections.mysql.collation'),
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        // Purgar la conexión para forzar reconexión
        DB::purge('tenant');

        // Inicializar tenancy (esto ejecutará los bootstrappers)
        $this->tenancy->initialize($tenant);

        // Ejecutar el request con tenancy inicializado
        $response = $next($request);

        return $response;
    }
}
