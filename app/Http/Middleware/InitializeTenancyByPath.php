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
        $dbName = 'tenant_' . $tenant->getTenantKey();

        // Actualizar la configuración de la conexión tenant
        Config::set('database.connections.tenant.database', $dbName);
        Config::set('database.connections.tenant.host', config('database.connections.mysql.host'));
        Config::set('database.connections.tenant.port', config('database.connections.mysql.port'));
        Config::set('database.connections.tenant.username', config('database.connections.mysql.username'));
        Config::set('database.connections.tenant.password', config('database.connections.mysql.password'));

        // Purgar y reconectar
        DB::purge('tenant');

        // Forzar que la conexión por defecto sea tenant dentro de este contexto
        Config::set('database.default', 'tenant');
        DB::purge('mysql');
        DB::setDefaultConnection('tenant');

        // Inicializar tenancy (esto ejecutará los bootstrappers restantes)
        $this->tenancy->initialize($tenant);

        // Ejecutar el request con tenancy inicializado
        $response = $next($request);

        // Restaurar la conexión por defecto
        Config::set('database.default', 'mysql');
        DB::setDefaultConnection('mysql');

        return $response;
    }

}
