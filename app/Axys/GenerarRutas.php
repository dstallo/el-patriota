<?php
/**
 * Genera las rutas de un ABM full
 * Todos los ID tienen un constrain regex para evitar ambigüedades
 */

namespace App\Axys;

use \Route;

class GenerarRutas
{
	public static function ABM($url, $var, $controlador, $excepto=[])
	{
		if(!in_array('index', $excepto)) {
			Route::get("$url", "$controlador@index")
				->name($controlador.'Index');
		}
		if(!in_array('eliminar-archivo', $excepto)) {
	    	Route::get("$url/eliminar/{".$var."}/{campo}", "$controlador@eliminarArchivo")
	    		->where($var, '[0-9]+')
	    		->name($controlador.'EliminarArchivo');
		}
	    if(!in_array('eliminar', $excepto)) {
	    	Route::get("$url/eliminar/{".$var."}", "$controlador@eliminar")
	    		->where($var, '[0-9]+')
	    		->name($controlador.'Eliminar');
	    }
	    if(!in_array('crear', $excepto)) {
	    	Route::get("$url/crear", "$controlador@crear")
	    		->name($controlador.'Crear');
	    }
	    if(!in_array('editar', $excepto)) {
	    	Route::get("$url/editar/{".$var."}", "$controlador@editar")
	    		->where($var, '[0-9]+')
	    		->name($controlador.'Editar');
	    }
	    if(!in_array('guardar', $excepto)) {
	    	Route::post("$url/guardar/{".$var."?}", "$controlador@guardar")
	    		->where($var, '[0-9]+')
	    		->name($controlador.'Guardar');
	    }
	    if(!in_array('visibilidad', $excepto)) {
	    	Route::get("$url/visibilidad/{".$var."}", "$controlador@visibilidad")
	    		->where($var, '[0-9]+')
	    		->name($controlador.'Visibilidad');
	    }
	}

	public static function Fotos($urlPadre, $varPadre, $url, $var, $controlador)
	{
		Route::get("$urlPadre/{".$varPadre."}/$url", "$controlador@index")
			->where($varPadre, '[0-9]+')
			->name($controlador.'Index');
	    Route::get("$urlPadre/{".$varPadre."}/$url/eliminar/{".$var."}", "$controlador@eliminar")
	    	->where([$var => '[0-9]+', $varPadre => '[0-9]+'])
	    	->name($controlador.'Eliminar');
	    Route::get("$urlPadre/{".$varPadre."}/$url/principal/{".$var."}", "$controlador@principal")
	    	->where([$var => '[0-9]+', $varPadre => '[0-9]+'])
	    	->name($controlador.'Principal');
	    Route::post("$urlPadre/{".$varPadre."}/$url", "$controlador@subir")
	    	->where($varPadre, '[0-9]+')
	    	->name($controlador.'Subir');
	    Route::get("$urlPadre/{".$varPadre."}/$url/editar-epigrafe/{".$var."}", "$controlador@editar")
	    	->where([$var => '[0-9]+', $varPadre => '[0-9]+'])
	    	->name($controlador.'Editar');
	    Route::post("$urlPadre/{".$varPadre."}/$url/editar-epigrafe/{".$var."}", "$controlador@guardar")
	    	->where([$var => '[0-9]+', $varPadre => '[0-9]+'])
	    	->name($controlador.'Guardar');
	}

	public static function auth()
	{
		//Auth::routes();
	    /// AUTH (hago esto, porque con Auth::routes() no puedo excluir las rutas de registro, y aparte nombro todas para usarlas más correctamente)
	    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	    Route::post('login', 'Auth\LoginController@login')->name('login-post');
	    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
	    // Password Reset Routes...
	    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('reset-password');
	    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('email-password-post');
	    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('reset-password-token');
	    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('reset-password-post');
	    ////
	}

	
	
}