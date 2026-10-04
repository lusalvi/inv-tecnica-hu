<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de autorización por rol.
 *
 * Uso en rutas:
 *   ->middleware('role:Administrador')
 *   ->middleware('role:Administrador,Super administrador')
 *
 * Los nombres de rol utilizados aquí deben coincidir con los valores
 * almacenados en la columna roles.nombre de la base de datos.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRol($roles)) {
            abort(403, 'No tenés permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}