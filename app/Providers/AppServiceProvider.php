<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // API: para configurar el tiempo de vida de los tokens.
        Passport::tokensExpireIn(CarbonInterval::days(7));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));

        // API: Para habilitar Password Grant. Esto permite no pasar por el flujo de autorización previo.
        Passport::enablePasswordGrant();

        // Implementamos un Rate Limiter para limitar la cantidad de requests a la API por minuto.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perSecond(1)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Has excedido el límite de pedidos. Esperá unos instantes y volvé a intentar.'
                    ], 429, $headers);
                });
        });
    }
}
