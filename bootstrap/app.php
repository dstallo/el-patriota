<?php

use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\Rol;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'rol' => Rol::class,
            'admin' => AuthAdmin::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // 1. Forzar respuestas JSON en todas las rutas de la API, sin importar las cabeceras del cliente
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });

        // 2. Personalizar las respuestas de autenticación y autorización para la API
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                
                // Si Passport determina que el token no es válido o no existe (Error 401)
                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Acceso denegado.'
                    ], 401);
                }

                // Si las Policies o Spatie Permission determinan que el usuario no tiene permisos (Error 403)
                if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Permiso denegado.'
                    ], 403);
                }
                
            }
        });

    })->create();
