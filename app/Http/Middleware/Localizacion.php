<?php

namespace App\Http\Middleware;

use Closure;
use App;

class Localizacion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('idioma')) {
            App::setLocale(session()->get('idioma'));
        }

        return $next($request);
    }
}
