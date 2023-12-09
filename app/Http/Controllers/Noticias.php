<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Noticia;
use App\Region;
use App\Seccion;
use App\Banner;
use App\Encuesta;

class Noticias extends Controller
{

	protected function noticias($request=null)
	{
		$noticias = Noticia::where('noticias.visible', true)
			//visibilidad en secciones
			->select('noticias.*')
			->join('secciones as s', 's.id', '=', 'noticias.id_seccion')
			->where('s.visible', true)
			///
			->with('seccion')
			->with('region')
			->orderBy('fecha', 'desc')
			->orderBy('id', 'desc')
		;

		if($request && $buscar = trim($request->input('buscar'))) {
			$noticias->where(function($query) use ($buscar) {
				$query->orWhere('titulo', 'like', "%$buscar%")
					->orWhere('bajada', 'like', "%$buscar%")
				;
			});
		}

		return $noticias;
	}

	protected function dividir($noticias)
	{
		$noticias = $noticias->take(24)->get();

		$partes = [
			'principales' => [],
			'secundarias' => [],
			'terciarias_1' => [],
			'terciarias_2' => [],
		];

		for($i=0;($i<3 && count($noticias)>0);$i++) {
			$partes['principales'][] = $noticias->shift();
		}
		$partes['principales'] = collect($partes['principales']);

		for($i=0;($i<6 && count($noticias)>0);$i++) {
			$partes['secundarias'][] = $noticias->shift();
		}
		$partes['secundarias'] = collect($partes['secundarias']);

		for($i=0;($i<3 && count($noticias)>0);$i++) {
			$partes['terciarias_1'][] = $noticias->shift();
		}
		$partes['terciarias_1'] = collect($partes['terciarias_1']);

		for($i=0;($i<12 && count($noticias)>0);$i++) {
			$partes['terciarias_2'][] = $noticias->shift();
		}
		$partes['terciarias_2'] = collect($partes['terciarias_2']);

		return $partes;
	}

	protected function leidas()
	{
		return Noticia::where('noticias.visible', true)
			//visibilidad en secciones
			->select('noticias.*')
			->join('secciones as s', 's.id', '=', 'noticias.id_seccion')
			->where('s.visible', true)
			->where('fecha', '>=', Carbon::now()->subDays(16))
			///
			->orderBy('visitas', 'desc')
			->take(3)
		;
	}

	protected function banners()
	{
		$horizontales = Banner::where('ubicacion', 'Horizontal')
			->where('visible', true)
			//->inRandomOrder()
			->orderBy('orden')
			->take(3)
			->get();

		$laterales = Banner::where('ubicacion', 'Lateral')
			->where('visible', true)
			//->inRandomOrder()
			->orderBy('orden')
			//->take(6)
			->get();

		$responsive = clone $horizontales;
		$responsive = $responsive->concat(clone $laterales);

		return compact('horizontales', 'laterales', 'responsive');
	}

    public function home(Request $request)
	{
		
		$noticias = $this->noticias($request);
		$partes = $this->dividir($noticias);

		$leidas = $this->leidas()->get();
		$banners = $this->banners();

		if(!count($partes['principales'])) {
			Flasher::set("No se encontraron noticias.", 'Sin resultados', 'error')->flashear();
			return redirect('/');
		}

	    return view('home', compact('partes', 'leidas', 'banners'));
	}

	public function seccion(Seccion $seccion, $nombre, Request $request)
	{
		if(!$seccion->visible) return redirect('/');

		$noticias = $this->noticias($request)->where('id_seccion', $seccion->id);
		$partes = $this->dividir($noticias);

		$leidas = $this->leidas()->where('id_seccion', $seccion->id)->get();
		$banners = $this->banners();

		if(!count($partes['principales'])) {
			Flasher::set("No se encontraron noticias.", 'Sin resultados', 'error')->flashear();
			return redirect('/');
		}
		
		return view('home', compact('partes', 'leidas', 'banners'));
	}

	public function region(Region $region, $nombre, Request $request)
	{
		$noticias = $this->noticias($request)->where('id_region', $region->id);
		$partes = $this->dividir($noticias);

		$leidas = $this->leidas()->where('id_region', $region->id)->get();
		$banners = $this->banners();

		if(!count($partes['principales'])) {
			Flasher::set("No se encontraron noticias.", 'Sin resultados', 'error')->flashear();
			return redirect('/');
		}
		
		return view('home', compact('partes', 'leidas', 'banners'));
	}

	public function ficha(Noticia $noticia)
	{
		if(!$noticia->seccion->visible) return redirect('/');

		$noticia->visitas += 1;
		$noticia->save();
		
		$noticias = $this->noticias()->where('id_seccion', $noticia->id_seccion)->where('id_region', $noticia->id_region)->where('noticias.id', '<>', $noticia->id);
		$partes = $this->dividir($noticias);

		$banners = $this->banners();

		$leidas = $this->leidas()->get();


		return view('ficha', compact('noticia', 'banners', 'partes', 'leidas'));
	}

	public function verEncuesta($pregunta, Request $request)
	{
		$encuesta = Encuesta::activa();
		if(!$encuesta) return redirect('/');

		if($request->session()->get('encuesta_votada_' . $encuesta->id)) {
			return redirect()->route('resultados-encuesta', str_slug($encuesta->pregunta));
		}

		$noticias = $this->noticias();
		$partes = $this->dividir($noticias);

		$banners = $this->banners();

		$leidas = $this->leidas()->get();


		return view('encuestas.votar', compact('encuesta', 'banners', 'partes', 'leidas'));
	}

	public function votarEncuesta($pregunta, Request $request)
	{
		$encuesta = Encuesta::activa();
		if(!$encuesta) return redirect('/');

		if($request->session()->get('encuesta_votada_' . $encuesta->id)) {
			return redirect()->route('resultados-encuesta', str_slug($encuesta->pregunta));
		}

		$opcion = $encuesta->opciones()->find($request->input('opcion'));
		if($opcion) {
			$opcion->votos += 1;
			$opcion->save();
		}

		$request->session()->put('encuesta_votada_' . $encuesta->id, true);

		return redirect()->route('resultados-encuesta', str_slug($encuesta->pregunta));
	}

	public function verResultadosEncuesta(Request $request)
	{
		$encuesta = Encuesta::activa();
		if(!$encuesta) return redirect('/');

		if(!$request->session()->get('encuesta_votada_' . $encuesta->id)) {
			return redirect()->route('ver-encuesta', str_slug($encuesta->pregunta));
		}

		$noticias = $this->noticias();
		$partes = $this->dividir($noticias);

		$banners = $this->banners();

		$leidas = $this->leidas()->get();


		return view('encuestas.resultados', compact('encuesta', 'banners', 'partes', 'leidas'));

	}
}
