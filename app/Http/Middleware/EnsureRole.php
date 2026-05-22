<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $perfil = session('usuario_perfil');

        if (!in_array($perfil, $roles)) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
