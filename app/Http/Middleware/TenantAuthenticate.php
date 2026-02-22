<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $tenantId = $request->route('tenant');
            return redirect()->route('tenant.path.login', ['tenant' => $tenantId]);
        }

        return $next($request);
    }
}
