<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;
use App\Axys\AxysVideo as Video;
use Illuminate\Support\Facades\Validator;

use App\Noticia;
use App\Contenido;

class Contenidos extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Noticia $noticia, Request $request)
    {
        
        $contenidos = $noticia->contenidos;

        return view('admin.multimedia-noticias.index', compact('noticia', 'contenidos'));
    }

    public function eliminar(Noticia $noticia, Contenido $contenido)
    {
        try {
            $contenido->delete();
            $flasher=Flasher::set("El contenido multimedia fue eliminado.", 'Contenido Eliminado', 'success');
        } catch (\Exception $e) {
            $flasher=Flasher::set('No se pudo borrar el contenido multimedia.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function crearVideo(Noticia $noticia, Request $request)
    {
        Video::agregarValidacion();

        $this->validate($request, [
            'nombre' => 'required',
            'imagen' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|video',
        ]);

        
        $contenido=new Contenido;
        $contenido->tipo = 'video';
        $contenido->id_noticia = $noticia->id;

        $contenido->fill($request->all())
            ->subir($request->file('imagen'),'imagen')
            ->crearThumbnails()
            ->ordenar([['id_noticia', $noticia->id]])
            ->save();

        
        Flasher::set("El video fue creado exitosamente.", 'Video Creado', 'success')->flashear();

        return back();
    }

    public function subirImagen(Noticia $noticia, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'imagen' => 'file|max:10240|mimes:jpeg,png,jpg',
        ]);
        if ($validator->fails()) {
            return response($validator->messages()->all()[0] ?? "Ocurrió un error al subir el archivo", 422);
        }

        $imagen=(new Contenido)
            ->subir($request->file('imagen'), 'imagen')
            ->crearThumbnails()
            ->ordenar([['id_noticia',$noticia->id]]);

        $imagen->tipo = 'imagen';
        $imagen->nombre = pathinfo($request->file('imagen')->getClientOriginalName(), PATHINFO_FILENAME);

        $noticia->contenidos()->save($imagen);
        
        return response('OK', 200);
    }

    public function editar(Noticia $noticia, Contenido $contenido)
    {
        $video=new Video($contenido->video);
        return view('admin.multimedia-noticias.editar',compact('noticia', 'contenido', 'video'));
    }

    public function guardar(Noticia $noticia, Contenido $contenido, Request $request)
    {
        Video::agregarValidacion();

        $this->validate($request, [
            'nombre' => 'required',
            'imagen' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|video',
        ]);

        $contenido->fill($request->all())
            ->subir($request->file('imagen'),'imagen')
            ->crearThumbnails()
            ->ordenar([['id_noticia', $noticia->id]])
            ->save();

        Flasher::set("El contenido multimedia fue modificado exitosamente.", 'Contenido Editado', 'success')->flashear();
        
        return back();
    }

    public function visibilidad(Noticia $noticia, Contenido $contenido)
    {
        return $this->cambiarVisibilidad($contenido);
    }

    public function eliminarImagen(Noticia $noticia, Contenido $contenido)
    {
        $contenido->eliminarArchivo('imagen')->save();
        Flasher::set("Se eliminó la imagen", 'Archivo Eliminado', 'success')->flashear();
        return back();
    }

    public function ordenar(Noticia $noticia, Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach($ids as $id) {
            $noticia->contenidos()->where('id', $id)->update(['orden' => $orden]);
            $orden += 1;
        }
        return ['ok'=>true];
    }
}