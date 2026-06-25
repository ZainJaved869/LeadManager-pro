<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If user is authenticated, get tenant from the user
        if (Auth::check()) {
            $tenant = Auth::user()->tenant;
            if ($tenant) {
                App::instance('tenant', $tenant);
                session(['tenant_id' => $tenant->id]);
            }
        } else {
            // For guest routes (login, register), we might have a tenant from session? 
            // But registration creates tenant, so we don't need one yet.
            // If you want to force a tenant for guests (e.g., default), handle here.
        }

        return $next($request);
    }
}