<?php

use App\Noticia;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    
// En AuthServiceProvider se deshabilitaron las rutas que crea Passport por defecto. Solo habilitaremos manualmente las siguientes. 


// Ruta para emitir access token
//Route::post('/oauth/token', ['uses' => 'AccessTokenController@issueToken', 'as' => 'passport.token']);
Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])->name('api.oauth.token');

// Segui acá: cambiar middleware para usar 'api'.
// Lograr implementar bien la validación del guard en ese middleware.
Route::middleware('auth:api')->group(function(){
    Route::post('/noticias', function(){
        return [
            "error" => false,
            "noticia" => Noticia::first()?->makeHidden(
                ['visitas', 'embebido_1', 'embebido_2', 'con_video']
            )
        ];
    })->name('api.noticias.crear');

    Route::get('/noticias', function(){
        return [
            "error" => false,
            "noticias" => Noticia::get()?->makeHidden(
                ['visitas', 'embebido_1', 'embebido_2', 'con_video']
            )
        ];
    })->name('api.noticias.listar');
});



/*
Route::post('/token/refresh', [
    'uses' => 'TransientTokenController@refresh',
    'as' => 'passport.token.refresh',
]);

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


