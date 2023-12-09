<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;

use App\Seccion;

class Secciones extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Seccion::orderBy('orden');

        $listado=new Listado(
            'listado_secciones',
            $query,
            $request,
            [],
            [
                'buscando'  =>[
                    ['campo'=>'nombre','comparacion'=>'like'],
                ],
                'buscando_id' =>[
                    ['campo'=>'id','comparacion'=>'igual']
                ]
            ]
        );
        
        //$secciones=$listado->paginar(50);
        $secciones=$listado->get();

        return view('admin.secciones.index', compact('secciones', 'listado'));
    }

    public function eliminar(Seccion $seccion)
    {
        try {
            $seccion->delete();
            $flasher=Flasher::set("La sección fue eliminada.", 'Sección Eliminada', 'success');
        } catch (\Exception $e) {
            $flasher=Flasher::set('No se pudo borrar la sección, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function crear(Request $request)
    {
        $seccion = new Seccion;

        return view('admin.secciones.crear',compact('seccion'));
    }

    public function editar(Seccion $seccion, Request $request)
    {
        return view('admin.secciones.editar',compact('seccion'));
    }

    public function guardar(Request $request, $id=null)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        if($id) {
            $seccion=Seccion::findOrFail($id);
        } else {
            $seccion=new Seccion;
            $seccion->ordenar();
        }

        $seccion->fill($request->all())
            ->save();

        if($id) {
            Flasher::set("La sección fue modificada exitosamente.", 'Sección Editada', 'success')->flashear();
            return back();
        } else {
            Flasher::set("La sección fue creada exitosamente.", 'Sección Creada', 'success')->flashear();
            return redirect()->route('secciones');
        }
    }

    public function ordenar(Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach($ids as $id) {
            Seccion::where('id', $id)->update(['orden' => $orden]);
            $orden += 1;
        }
        return ['ok'=>true];
    }

    public function visibilidad(Seccion $seccion)
    {
        return $this->cambiarVisibilidad($seccion);
    }
}