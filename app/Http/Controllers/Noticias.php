<?php

namespace App\Http\Controllers;

use App\Axys\AxysFlasher as Flasher;
use App\Banner;
use App\Configuracion;
use App\Encuesta;
use App\Noticia;
use App\Popup;
use App\Region;
use App\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Noticias extends Controller
{
    protected function noticias($request = null, $excepto = [])
    {
        $noticias = Noticia::obtener();

        $noticias->whereNotIn('noticias.id', $excepto);

        if ($request && $buscar = trim($request->input('buscar'))) {
            $noticias->where(function ($query) use ($buscar) {
                $query->orWhere('titulo', 'like', "%$buscar%")
                    ->orWhere('bajada', 'like', "%$buscar%")
                ;
            });
        }

        return $noticias;
    }

    protected function dividir($noticias)
    {
        $noticias = $noticias->take(14)->get();

        $partes = [
            'principales' => [],
            'secundarias' => [],
            'terciarias' => [],
            'cuaternarias' => [],
        ];

        for ($i = 0; $i < 3 && count($noticias) > 0; $i++) {
            $partes['principales'][] = $noticias->shift();
        }
        $partes['principales'] = collect($partes['principales']);

        for ($i = 0; $i < 4 && count($noticias) > 0; $i++) {
            $partes['secundarias'][] = $noticias->shift();
        }
        $partes['secundarias'] = collect($partes['secundarias']);

        for ($i = 0; $i < 1 && count($noticias) > 0; $i++) {
            $partes['terciarias'][] = $noticias->shift();
        }
        $partes['terciarias'] = collect($partes['terciarias']);

        for ($i = 0; $i < 6 && count($noticias) > 0; $i++) {
            $partes['cuaternarias'][] = $noticias->shift();
        }
        $partes['cuaternarias'] = collect($partes['cuaternarias']);

        return $partes;
    }

    protected function leidas()
    {
        return Noticia::leidas()
            ->take(3)
        ;
    }

    protected function grupo(Request $request) {
        $query = $this->noticias($request);

        $grupo = Configuracion::obtener('GRUPO_ACTIVO');
        if ($grupo)
            $query->where('grupo', $grupo->valor);
        else
            $query->whereRaw('1 != 1');

        return $query;
    }

    protected function banners()
    {
        $horizontales = Banner::where('ubicacion', 'Horizontal')
            ->where('visible', true)
            // ->inRandomOrder()
            ->orderBy('orden')
            // ->take(3)
            ->get();

        $laterales = Banner::where('ubicacion', 'Lateral')
            ->where('visible', true)
            // ->inRandomOrder()
            ->orderBy('orden')
            // ->take(6)
            ->get();

        $responsive = clone $horizontales;
        $responsive = $responsive->concat(clone $laterales);

        return compact('horizontales', 'laterales', 'responsive');
    }

    public function home(Request $request)
    {
        $destacadas = $this->noticias($request)->where('destacada', true)->take(10)->get();
        $noticias = $this->noticias($request, $destacadas->pluck('id'));
        $partes = $this->dividir($noticias);

        $leidas = $this->leidas()->get();

        $grupo = Configuracion::obtener('GRUPO_ACTIVO');
        $noticias_grupo = $this->grupo($request)->get();
        
        $banners = $this->banners();
        $popup = Popup::where('visible', true)->orderBy('id', 'desc')->first();

        return view('home', compact('destacadas', 'partes', 'leidas', 'banners', 'grupo', 'noticias_grupo', 'popup'));
    }

    public function seccion(Seccion $seccion, $nombre, Request $request)
    {
        if (!$seccion->visible) {
            return redirect('/');
        }

        $noticias = $this->noticias($request)->where('id_seccion', $seccion->id);
        $partes = $this->dividir($noticias);

        $leidas = $this->leidas()->where('id_seccion', $seccion->id)->get();
        $banners = $this->banners();

        $grupo = Configuracion::obtener('GRUPO_ACTIVO');
        $noticias_grupo = $this->grupo($request)->where('id_seccion', $seccion->id)->get();

        return view('home', compact('partes', 'leidas', 'banners', 'grupo', 'noticias_grupo'));
    }

    public function region(Region $region, $nombre, Request $request)
    {
        $noticias = $this->noticias($request)->where('id_region', $region->id);
        $partes = $this->dividir($noticias);

        $leidas = $this->leidas()->where('id_region', $region->id)->get();
        $banners = $this->banners();

        $grupo = Configuracion::obtener('GRUPO_ACTIVO');
        $noticias_grupo = $this->grupo($request)->where('id_region', $region->id)->get();

        return view('home', compact('partes', 'leidas', 'banners'));
    }

    public function ficha(Noticia $noticia)
    {
        if (! ($noticia->seccion?->visible ?? true)) {
            return redirect('/');
        }

        ++$noticia->visitas;
        $noticia->save();

        $query = $this->noticias()->where('noticias.id', '<>', $noticia->id);
        
        if ($noticia->grupo)
            $query->where('grupo', $noticia->grupo);
        elseif ($noticia->id_seccion)
            $query->where('id_seccion', $noticia->id_seccion);
        elseif ($noticia->id_region)
            $query->where('id_region', $noticia->id_region);
        
        $relacionadas = $query->take(3)->get();

        $banners = $this->banners();

        $leidas = $this->leidas()->get();

        return view('ficha', compact('noticia', 'banners', 'leidas', 'relacionadas'));
    }

    public function verEncuesta($pregunta, Request $request)
    {
        $encuesta = Encuesta::activa();
        if (!$encuesta) {
            return redirect('/');
        }

        if ($request->session()->get('encuesta_votada_'.$encuesta->id)) {
            return redirect()->route('resultados-encuesta', Str::slug($encuesta->pregunta));
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
        if (!$encuesta) {
            return redirect('/');
        }

        if ($request->session()->get('encuesta_votada_'.$encuesta->id)) {
            return redirect()->route('resultados-encuesta', Str::slug($encuesta->pregunta));
        }

        $opcion = $encuesta->opciones()->find($request->input('opcion'));
        if ($opcion) {
            ++$opcion->votos;
            $opcion->save();
        }

        $request->session()->put('encuesta_votada_'.$encuesta->id, true);

        return redirect()->route('resultados-encuesta', Str::slug($encuesta->pregunta));
    }

    public function verResultadosEncuesta(Request $request)
    {
        $encuesta = Encuesta::activa();
        if (!$encuesta) {
            return redirect('/');
        }

        if (!$request->session()->get('encuesta_votada_'.$encuesta->id)) {
            return redirect()->route('ver-encuesta', Str::slug($encuesta->pregunta));
        }

        $noticias = $this->noticias();
        $partes = $this->dividir($noticias);

        $banners = $this->banners();

        $leidas = $this->leidas()->get();

        return view('encuestas.resultados', compact('encuesta', 'banners', 'partes', 'leidas'));
    }
}
