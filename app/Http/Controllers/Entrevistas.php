<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Noticia;
use App\Video;
use Illuminate\Http\Request;

class Entrevistas extends Controller
{
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

    public function index(Request $request)
    {
        $noticias = Noticia::obtener()->take(3)->get();
        $leidas = Noticia::leidas()->take(3)->get();
        $banners = $this->banners();

        $destacados = Video::where('visible', true)->orderBy('orden')->where('destacado', true)->get()->filter(function ($video) {
            return ($video->resuelto = $video->getVideo()) && $video->tiene('imagen');
        });
        $resto = Video::where('visible', true)->orderBy('orden')->where('destacado', false)->get()->filter(function ($video) {
            return ($video->resuelto = $video->getVideo()) && $video->tiene('imagen');
        });

        $videos = [
            'primarios' => [],
            'secundarios' => [],
        ];
        for ($i = 0; $i < 4 && count($resto) > 0; ++$i) {
            $videos['primarios'][] = $resto->shift();
        }
        $videos['primarios'] = collect($videos['primarios']);
        $videos['secundarios'] = $resto;

        return view('entrevistas', compact('destacados', 'videos', 'noticias', 'leidas', 'banners'));
    }
}
