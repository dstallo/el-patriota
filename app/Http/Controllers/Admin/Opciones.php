<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;

use App\Opcion;
use App\Encuesta;

class Opciones extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Encuesta $encuesta, Request $request)
    {
        $query = $encuesta->opciones();

        $listado=new Listado(
            'listado_opciones_' . $encuesta->id,
            $query,
            $request,
            [],
            [
                'buscando'  =>[
                    ['campo'=>'opcion','comparacion'=>'like'],
                ],
                'buscando_id' =>[
                    ['campo'=>'id','comparacion'=>'igual']
                ]
            ]
        );
        
        //$opciones=$listado->paginar(50);
        $opciones=$listado->get();

        return view('admin.opciones.index', compact('opciones', 'listado', 'encuesta'));
    }

    public function eliminar(Encuesta $encuesta, Opcion $opcion)
    {
        try {
            $opcion->delete();
            $flasher=Flasher::set("La opción fue eliminada.", 'Opción Eliminada', 'success');
        } catch (\Exception $e) {
            $flasher=Flasher::set('No se pudo borrar la opción, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function crear(Encuesta $encuesta, Request $request)
    {
        $opcion = new Opcion;

        return view('admin.opciones.crear',compact('opcion', 'encuesta'));
    }

    public function editar(Encuesta $encuesta, Opcion $opcion, Request $request)
    {
        return view('admin.opciones.editar',compact('opcion', 'encuesta'));
    }

    public function guardar(Encuesta $encuesta, $id=null, Request $request)
    {
        $this->validate($request, [
            'opcion' => 'required',
        ]);

        if($id) {
            $opcion=Opcion::findOrFail($id);
        } else {
            $opcion=new Opcion;
            $opcion->id_encuesta = $encuesta->id;
            $opcion->ordenar([['id_encuesta', $encuesta->id]]);
            $opcion->votos = 0;
        }

        $opcion->fill($request->all())
            ->save();

        if($id) {
            Flasher::set("La opción fue modificada exitosamente.", 'Opción Editada', 'success')->flashear();
            return back();
        } else {
            Flasher::set("La opción fue creada exitosamente.", 'Opción Creada', 'success')->flashear();
            return redirect()->route('opciones', $encuesta);
        }
    }

    public function ordenar(Encuesta $encuesta, Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach($ids as $id) {
            $encuesta->opciones()->where('id', $id)->update(['orden' => $orden]);
            $orden += 1;
        }
        return ['ok'=>true];
    }
}