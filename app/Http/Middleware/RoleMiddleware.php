<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $user = auth()->user();
          if (!in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción. Rol requerido: ' . implode(', ', $roles)
            ], 403);
        }

        return $next($request);
    }
}