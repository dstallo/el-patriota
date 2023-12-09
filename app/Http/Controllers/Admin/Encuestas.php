<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;

use App\Encuesta;

class Encuestas extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $input)
    {
        if(!session()->has('axys.listado.encuestas.valores.buscando_desde')) {
            session(["axys.listado.encuestas.valores.buscando_desde" => date('Y-m-d', strtotime('-2 month'))]);
            session(["axys.listado.encuestas.valores.buscando_hasta" => date('Y-m-d', strtotime('+1 day'))]);
            session(["axys.listado.encuestas.orden" => 'created_at']);
        }

        $listado=new Listado(
        	'encuestas',
            Encuesta::query(),
            $input,
            ['id','created_at','nombre'],
            [
            	'buscando'	=> [
            		['campo'=>'nombre','comparacion'=>'like'],
            	],
            	'buscando_id' => [
            		['campo'=>'id','comparacion'=>'igual']
            	],
                'buscando_desde'  =>[
                    ['campo'=>'created_at','comparacion'=>'mayor'],
                ],
                'buscando_hasta'  =>[
                    ['campo'=>'created_at','comparacion'=>'menor'],
                ],
            ]
        );
        
        $encuestas=$listado->paginar();

        return view('admin.encuestas.index', compact('encuestas', 'listado'));
    }

    public function eliminar(Encuesta $encuesta)
    {
        $id=$encuesta->id;
        try {
            
            $encuesta->opciones()->delete();
            $encuesta->delete();
            $flasher=Flasher::set("La encuesta #$id fue eliminada.", 'Encuesta Eliminada', 'success');
            
        } catch (\Exception $e) {
            $flasher=Flasher::set('No se pudo eliminar la encuesta.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function crear()
    {
        $encuesta = new Encuesta;
        return view('admin.encuestas.crear',compact('encuesta'));
    }

    public function editar(Encuesta $encuesta)
    {
        return view('admin.encuestas.editar',compact('encuesta'));
    }

    public function guardar(Request $input, $id=null)
    {
        $this->validate($input, [
            'nombre' => 'required',
            'pregunta' => 'required',
        ]);

        if($id) {
            $encuesta=Encuesta::findOrFail($id);
            $encuesta->fill($input->all());
            $encuesta->save();
            Flasher::set("La encuesta #$encuesta->id fue modificada exitosamente.", 'Encuesta Editada', 'success')->flashear();
            return back();
        } else {
            $encuesta=new Encuesta($input->all());
            $encuesta->visible = false;
            $encuesta->save();
            Flasher::set("La encuesta #$encuesta->id fue creada exitosamente.", 'Encuesta Creada', 'success')->flashear();
            return redirect()->route('editar_encuesta', $encuesta);
        }
    }

    public function visibilidad(Encuesta $encuesta)
    {
        if($encuesta->visible) {
            $encuesta->visible = false;
            Flasher::set('Se ocultó el registro exitosamente', 'Oculto', 'success')->flashear();
        } else {
            Encuesta::query()->update(['visible' => false]);
            $encuesta->visible = true;
            Flasher::set('Se visibilizó el registro exitosamente', 'Visible', 'success')->flashear();
        }
        $encuesta->save();
        return back();
    }
}