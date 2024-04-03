<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        // Obtener el rol del usuario
        $userRole = auth()->user()->rol_id;

        // Verificar si el rol del usuario está entre los roles permitidos
        if (in_array($userRole, $allowedRoles)) {
            // Permitir acceso
            return $next($request);
        }

        // Si el rol no está entre los permitidos, mostrar error de acceso denegado
        return response()->json(['error' => "Acceso denegado"], 400);
    }
}
