<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si hay un usuario autenticado
        $admin = Auth::user();

        if (! $admin) {
            return redirect()->route('login');
        }

        // 2. Comprobar si el usuario está deshabilitado (cambia 'is_enabled' por tu columna real)
        if (! $admin->visible || $admin->soloApi()) {
            
            // Cerrar la sesión en la guardia actual (Web, Passport, Sanction, etc.)
            Auth::logout();

            // Opcional: Si usas sesiones basadas en web, invalida la sesión actual
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            // 3. Retornar la respuesta según el tipo de petición (API o Web)
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Acceso denegado al panel.'
                ], 403); // 403 Forbidden es el código HTTP correcto para este caso
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Acceso denegado al panel.',
            ]);
        }

        return $next($request);
    }
}