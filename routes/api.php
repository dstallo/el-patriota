<?php

use App\Http\Controllers\API\Noticias;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

// En AuthServiceProvider se deshabilitaron las rutas que crea Passport por defecto. Solo habilitaremos manualmente las siguientes. 

// Ruta para emitir access token
Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])->name('api.oauth.token');

Route::middleware(['throttle:api', 'auth:api'])->group(function(){
    Route::post('/noticias', [Noticias::class, 'store'])->name('api.noticias.crear');

    Route::get('/noticias', [Noticias::class, "index"])->name('api.noticias.listar');
});



/*


Route::post('/tokens/refresh', [
    'uses' => 'ApproveAuthorizationController@approve',
    'as' => 'passport.token.refresh.approve',
]);

// Rutas para la gestión de tokens y clientes (opcionales)
Route::get('/tokens', [
    'uses' => 'AuthorizedAccessTokenController@forUser',
    'as' => 'passport.tokens.index',
]);
*/

    


/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API'], function() {

});
*/


