<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si está logueado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Verificamos si es staff o admin
        if (!$user->isStaff()) {
            abort(403, 'ACCESO DENEGADO: Área restringida solo para staff.');
        }

        return $next($request);
    }
}
