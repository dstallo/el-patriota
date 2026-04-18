<?php

namespace App\Http\Controllers\Admin;

use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;
use App\Http\Controllers\Controller;
use App\Noticia;
use App\Region;
use App\Seccion;
use Illuminate\Http\Request;

class Noticias extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!session()->has('axys.listado.listado_noticias.orden')) {
            session(['axys.listado.listado_noticias.orden' => 'id']);
            session(['axys.listado.listado_noticias.sentido' => 'desc']);
        }

        $listado = new Listado(
            'listado_noticias',
            Noticia::with('seccion')->with('region'),
            $request,
            ['id', 'fecha', 'titulo', 'visitas', 'grupo', 'id_region', 'id_seccion'],
            [
                'buscando' => [
                    ['campo' => 'titulo', 'comparacion' => 'like'],
                    ['campo' => 'bajada', 'comparacion' => 'like'],
                    ['campo' => 'texto', 'comparacion' => 'like'],
                ],
                'buscando_id' => [
                    ['campo' => 'id', 'comparacion' => 'igual'],
                ],
                'buscando_id_seccion' => [
                    ['campo' => 'id_seccion', 'comparacion' => 'igual'],
                ],
                'buscando_id_region' => [
                    ['campo' => 'id_region', 'comparacion' => 'igual'],
                ],
                'buscando_grupo' => [
                    ['campo' => 'grupo', 'comparacion' => 'igual'],
                ]
            ]
        );

        $noticias = $listado->paginar(50);

        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        $grupos = Noticia::obtenerGrupos();

        return view('admin.noticias.index', compact('noticias', 'listado', 'secciones', 'regiones', 'grupos'));
    }

    public function eliminar(Noticia $noticia)
    {
        try {
            foreach ($noticia->contenidos as $contenido) {
                $contenido->delete();
            }
            $noticia->delete();
            $flasher = Flasher::set('La noticia fue eliminada.', 'Noticia Eliminada', 'success');
        } catch (\Exception $e) {
            $flasher = Flasher::set('No se pudo borrar la noticia.', 'Error', 'error');
        }
        $flasher->flashear();

        return redirect()->back();
    }

    public function crear()
    {
        $noticia = new Noticia();
        $noticia->fecha = date('Y-m-d H:i');
        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        $grupos = Noticia::obtenerGrupos();

        return view('admin.noticias.crear', compact('noticia', 'secciones', 'regiones', 'grupos'));
    }

    public function editar(Noticia $noticia)
    {
        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        $grupos = Noticia::obtenerGrupos();

        return view('admin.noticias.editar', compact('noticia', 'secciones', 'regiones', 'grupos'));
    }

    public function guardar(Request $request, $id = null)
    {
        $this->validate($request, [
            'id_seccion' => 'nullable|exists:secciones,id',
            'id_region' => 'nullable|exists:regiones,id',
            'fecha' => 'required|date',
            'titulo' => 'required',
            'thumbnail' => 'nullable|file|mimes:jpg,png|max:1024',
            'thumbnail_celular' => 'nullable|file|mimes:jpg,png|max:1024',
            'banner' => 'nullable|file|mimes:jpg,png|max:1024',
            'banner_celular' => 'nullable|file|mimes:jpg,png|max:1024',
            'grupo' => ['nullable']
        ], [], [
            'id_seccion' => 'sección',
            'id_region' => 'región',
            'titulo' => 'título',
            'grupo'  => 'grupo de noticias'
        ]);

        if ($id) {
            $noticia = Noticia::findOrFail($id);
        } else {
            $noticia = new Noticia();
            $noticia->visible = false;
        }

        $noticia->fill($request->all())
            ->subir($request->file('thumbnail'), 'thumbnail')
            ->subir($request->file('thumbnail_celular'), 'thumbnail_celular')
            ->subir($request->file('banner'), 'banner')
            ->subir($request->file('banner_celular'), 'banner_celular');

        foreach (['con_video', 'destacada'] as $check) {
            $noticia->$check = boolval($request->input($check));
        }

        $noticia->save();

        // if($request->file('thumbnail')) $noticia->fit(___, ___, 'thumbnail');
        // if($request->file('thumbnail_celular')) $noticia->fit(___, ___, 'thumbnail_celular');

        if ($id) {
            Flasher::set('La noticia fue modificada exitosamente.', 'Noticia Editada', 'success')->flashear();
        } else {
            Flasher::set('La noticia fue creada exitosamente.', 'Noticia Creada', 'success')->flashear();
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
