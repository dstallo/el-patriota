<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::tokensExpireIn(CarbonInterval::days(15));
        //Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        //Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));

        // API: Para habilitar Password Grant. Esto permite no pasar por el flujo de autorización previo.
        Passport::enablePasswordGrant();
    }
}
