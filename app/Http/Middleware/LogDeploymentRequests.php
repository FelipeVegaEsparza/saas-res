<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogDeploymentRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Log todas las requests al webhook de deployment
        Log::info('Deployment webhook request received', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);

        $response = $next($request);

        // Log la respuesta
        Log::info('Deployment webhook response sent', [
            'status' => $response->getStatusCode(),
            'content' => $response->getContent(),
        ]);

        return $response;
    }
}
