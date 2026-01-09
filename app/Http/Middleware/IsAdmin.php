<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si está logueado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */ // <--- ESTA LÍNEA ES LA MAGIA
        $user = Auth::user();

        // 2. Ahora VS Code ya sabe que $user tiene el método isAdmin()
        if (!$user->isAdmin()) {
            abort(403, 'ACCESO DENEGADO: Área restringida solo para administradores.');
        }

        return $next($request);
    }
}