<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePortalAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('portal_usuario_id')) {
            return redirect()->route('portal.login');
        }

        return $next($request);
    }
}
