<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AllUserMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && in_array(Auth::user()->rol, ['administrador', 'supervisor', 'tecnico'])) {
            return $next($request);
        }

        abort(403, 'Acceso denegado.');
    }
}
