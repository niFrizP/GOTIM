<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->rol === 'Técnico') {
            return $next($request);
        }

        abort(403, 'Acceso denegado. Solo técnicos.');
    }
}
