<?php

namespace App\Http\Controllers\Admin;

use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;
use App\Http\Controllers\API\Noticias as APINoticias;
use App\Http\Controllers\Controller;
use App\Noticia;
use App\Region;
use App\Seccion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Noticias extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('rol:admin');
    }

    public function index(Request $request)
    {
        if (!session()->has('axys.listado.listado_noticias.orden')) {
            session(['axys.listado.listado_noticias.orden' => 'id']);
            session(['axys.listado.listado_noticias.sentido' => 'desc']);
        }

        $listado = APINoticias::listado('listado_noticias');

        $noticias = $listado->paginar(50);

        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        $grupos = Noticia::obtenerGrupos();

        $opciones_generacion = [
            (object) ["id" => "0", "nombre" => "Manuales"],
            (object) ["id" => "1", "nombre" => "Automáticas"]
        ];

        return view('admin.noticias.index', compact('noticias', 'listado', 'secciones', 'regiones', 'grupos', 'opciones_generacion'));
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
        $opciones_publicacion = Noticia::obtenerOpcionesPublicacion();

        return view('admin.noticias.crear', compact('noticia', 'secciones', 'regiones', 'grupos', 'opciones_publicacion'));
    }

    public function editar(Noticia $noticia)
    {
        $secciones = Seccion::orderBy('orden')->get();
        $regiones = Region::orderBy('orden')->get();
        $grupos = Noticia::obtenerGrupos();
        $opciones_publicacion = Noticia::obtenerOpcionesPublicacion();

        return view('admin.noticias.editar', compact('noticia', 'secciones', 'regiones', 'grupos', 'opciones_publicacion'));
    }

    public function guardar(Request $request, $id = null)
    {
        $validator = APINoticias::validar($request, $id);

        if ($validator->fails())
            throw ValidationException::withMessages($validator->errors()->messages());

        $noticia = APINoticias::guardar($request, $validator->validated(), $id);

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
