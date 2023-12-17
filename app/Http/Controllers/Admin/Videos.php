<?php

namespace App\Http\Controllers\Admin;

use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;

class Videos extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Video::orderBy('orden');

        $listado = new Listado(
            'videos',
            $query,
            $request,
            [],
            [
                'buscando' => [
                    ['campo' => 'nombre', 'comparacion' => 'like'],
                ],
                'buscando_id' => [
                    ['campo' => 'id', 'comparacion' => 'igual'],
                ],
            ]
        );

        // $videos=$listado->paginar(50);
        $videos = $listado->get();

        return view('admin.videos.index', compact('videos', 'listado'));
    }

    public function eliminar(Video $video)
    {
        try {
            $video->delete();
            $flasher = Flasher::set('El video fue eliminado.', 'Video Eliminado', 'success');
        } catch (\Exception $e) {
            $flasher = Flasher::set('No se pudo borrar el video, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();

        return redirect()->back();
    }

    public function eliminarArchivo(Video $video, $campo)
    {
        $video->eliminarArchivo($campo)->save();
        Flasher::set("Se eliminó el archivo $campo", 'Archivo Eliminado', 'success')->flashear();

        return back();
    }

    public function crear(Request $request)
    {
        $video = new Video();

        return view('admin.videos.crear', compact('video'));
    }

    public function editar(Video $video, Request $request)
    {
        $axvideo = $video->getVideo();

        return view('admin.videos.editar', compact('video', 'axvideo'));
    }

    public function guardar(Request $request, $id = null)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'imagen' => 'file|mimes:jpg,png|max:1024',
            // 'imagen_vertical' => 'file|mimes:jpg,png|max:1024',
            'link' => 'required',
        ]);

        if ($id) {
            $video = Video::findOrFail($id);
        } else {
            $video = new Video();
            $video->ordenar();
        }

        $video->fill($request->all())
            ->subir($request->file('imagen'), 'imagen', [1400, 650])
            // ->subir($request->file('imagen_vertical'), 'imagen_vertical')
            ->crearThumbnails();

        foreach (['destacado'] as $check) {
            $video->$check = boolval($request->input($check));
        }

        $video->save();

        if ($id) {
            Flasher::set('El video fue modificado exitosamente.', 'Video Editado', 'success')->flashear();

            return back();
        } else {
            Flasher::set('El video fue creado exitosamente.', 'Video Creado', 'success')->flashear();

            return redirect()->route('videos');
        }
    }

    public function ordenar(Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach ($ids as $id) {
            Video::where('id', $id)->update(['orden' => $orden]);
            ++$orden;
        }

        return ['ok' => true];
    }

    public function visibilidad(Video $video)
    {
        return $this->cambiarVisibilidad($video);
    }
}
