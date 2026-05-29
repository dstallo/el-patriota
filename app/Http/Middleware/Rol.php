<?php

namespace App\Http\Middleware;

use App\Axys\AxysFlasher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Rol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        if (! $request->user()->tieneRol($rol)) {
            AxysFlasher::set('No tienes permiso para acceder.', 'Acceso denegado', 'error')->flashear();
            return redirect()->route('login');
        }
 
        return $next($request);
    }
}
