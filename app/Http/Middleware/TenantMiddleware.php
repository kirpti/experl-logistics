<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        // SaaS multi-tenancy: kullanıcının tenant'ını session'a set et
        if ($request->user()) {
            app()->instance('tenant_id', $request->user()->tenant_id);
        }
        return $next($request);
    }
}
