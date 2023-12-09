<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;

use App\Noticia;
use App\Seccion;
use App\Region;
use App\Contenido;

class Noticias extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(!session()->has("axys.listado.listado_noticias.orden")) {
            session(["axys.listado.listado_noticias.orden" => 'id']);
            session(["axys.listado.listado_noticias.sentido" => 'desc']);
        }

        $listado=new Listado(
            'listado_noticias',
            Noticia::with('seccion')->with('region'),
            $request,
            ['id', 'fecha', 'titulo', 'visitas'],
            [
                'buscando'  =>[
                    ['campo'=>'titulo','comparacion'=>'like'],
                    ['campo'=>'bajada','comparacion'=>'like'],
                    ['campo'=>'texto','comparacion'=>'like'],
                ],
                'buscando_id' =>[
                    ['campo'=>'id','comparacion'=>'igual']
                ],
                'buscando_id_seccion' =>[
                    ['campo'=>'id_seccion','comparacion'=>'igual']
                ],
                'buscando_id_region' =>[
                    ['campo'=>'id_region','comparacion'=>'igual']
                ]
            ]
        );
        
        $noticias=$listado->paginar(50);

        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();

        return view('admin.noticias.index', compact('noticias', 'listado', 'secciones', 'regiones'));
    }

    public function eliminar(Noticia $noticia)
    {
        try {
            foreach($noticia->contenidos as $contenido) $contenido->delete();
            $noticia->delete();
            $flasher=Flasher::set("La noticia fue eliminada.", 'Noticia Eliminada', 'success');
        } catch (\Exception $e) {
            $flasher=Flasher::set('No se pudo borrar la noticia.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function crear()
    {
        $noticia = new Noticia;
        $noticia->fecha = date('Y-m-d H:i');
        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        return view('admin.noticias.crear', compact('noticia', 'secciones', 'regiones'));
    }

    public function editar(Noticia $noticia)
    {
        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        return view('admin.noticias.editar', compact('noticia', 'secciones', 'regiones'));
    }

    public function guardar(Request $request, $id=null)
    {
        $this->validate($request, [
            'id_seccion' => 'required|exists:secciones,id',
            'id_region' => 'required|exists:regiones,id',
            'fecha' => 'required|date',
            'titulo' => 'required',
            'thumbnail' => 'nullable|file|mimes:jpg,png|max:1024',
            'thumbnail_celular' => 'nullable|file|mimes:jpg,png|max:1024',
        ], [], [
            'id_seccion' => 'sección',
            'id_region' => 'región',
            'titulo' => 'título',
        ]);

        if($id) {
            $noticia=Noticia::findOrFail($id);
        } else {
            $noticia=new Noticia;
            $noticia->visible = false;
        }

        $noticia->fill($request->all())
            ->subir($request->file('thumbnail'),'thumbnail')
            ->subir($request->file('thumbnail_celular'),'thumbnail_celular');

        foreach(['con_video'] as $check) {
            $noticia->$check = boolval($request->input($check));
        }

        $noticia->save();

        // CÓDIGO PARA ACTUALIZAR ID's A MANO
        // if($id && ($id_nuevo = $request->input('id_noticia_update'))) {
        //     if($id_nuevo != $id) {
        //         \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        //         Contenido::where('id_noticia', $id)->update(['id_noticia' => $id_nuevo]);
        //         Noticia::where('id', $id)->update(['id' => $id_nuevo]);
        //         \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        //         $noticia->id = $id_nuevo;
        //     }
        // }
        // FIN DEL CÓDIGO PARA ACTUALIZAR ID's A MANO

        // if($request->file('thumbnail')) $noticia->fit(___, ___, 'thumbnail');
        // if($request->file('thumbnail_celular')) $noticia->fit(___, ___, 'thumbnail_celular');

        if($id) {
            Flasher::set("La noticia fue modificada exitosamente.", 'Noticia Editada', 'success')->flashear();
        } else {
            Flasher::set("La noticia fue creada exitosamente.", 'Noticia Creada', 'success')->flashear();
        }

        return redirect()->route('editar_noticia', $noticia);
    }

    public function visibilidad(Noticia $noticia)
    {
        return $this->cambiarVisibilidad($noticia);
    }

    public function eliminarArchivo(Noticia $noticia, $campo)
    {
        $noticia->eliminarArchivo($campo)->save();
        Flasher::set("Se eliminó el archivo $campo", 'Archivo Eliminado', 'success')->flashear();
        return back();
    }
}