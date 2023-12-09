<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use Illuminate\Support\Facades\Validator;
use \Curl;

use App\InscriptoNewsletter as Inscripto;

class General extends Controller
{
	function __construct()
	{
		$this->middleware('throttle:5,2')->only(['newsletter']);
	}

    public function newsletter(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'email' => 'required|email',
	    ]);

	    if ($validator->fails()) {
	        return response(['ok'=>false, 'errores'=>$validator->errors()->all()], 200);
	    }

	    if(!Inscripto::where('email',$request->get('email'))->first()) {
	        $inscripto = new Inscripto($request->all());
	        $inscripto->save();
	    }

	    return response(['ok'=>true], 200);
	}

	public function clima()
	{
		$respuesta = Curl::to('https://api.openweathermap.org/data/2.5/weather?id=3435910&units=metric&APPID=322686eeef233f80c3fbbc59c1ea5459')
		    ->asJson(true)
			->get()
		;

		if(!$respuesta || !isset($respuesta['main'])) {
			return response('', 404);
		}

		return [
			'temperatura' => $respuesta['main']['temp'],
			'codigo' => $respuesta['weather'][0]['id'],
		];
	}

}