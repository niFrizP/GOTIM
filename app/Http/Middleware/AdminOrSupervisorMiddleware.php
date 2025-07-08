<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOrSupervisorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && in_array(Auth::user()->rol, ['Administrador', 'Supervisor'])) {
            return $next($request);
        }

        abort(403, 'Acceso denegado, solo Administradores o supervisores pueden acceder a esta sección.');
    }
}
