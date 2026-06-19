<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;

use App\Encuesta;
use App\Noticia;
use Illuminate\Validation\Rule;

class Encuestas extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('rol:admin');
    }

    public function index(Request $input)
    {
        if (!session()->has('axys.listado.encuestas.orden')) {
            session(['axys.listado.encuestas.orden' => 'created_at']);
            session(['axys.listado.encuestas.sentido' => 'desc']);
        }

        $query = Encuesta::query();

        $query->withNoticiasCount();

        $listado=new Listado(
        	'encuestas',
            $query,
            $input,
            ['id','created_at','nombre'],
            [
            	'buscando'	=> [
            		['campo'=>'nombre','comparacion'=>'like'],
            	],
            	'buscando_id' => [
            		['campo'=>'id','comparacion'=>'igual']
            	]
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
        $noticias = Noticia::all();
        return view('admin.encuestas.crear',compact('encuesta', 'noticias'));
    }

    public function editar(Encuesta $encuesta)
    {
        $noticias = Noticia::all();
        return view('admin.encuestas.editar',compact('encuesta', 'noticias'));
    }

    public function guardar(Request $input, $id=null)
    {
        $this->validate($input, [
            'nombre' => 'required',
            'pregunta' => 'required',
            'id_noticia' => ['nullable', Rule::exists('noticias', 'id')]
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
            Flasher::set('Se ocultó la encuesta general exitosamente', 'Encuesta Ocultada', 'success')->flashear();
        } else {
            Encuesta::query()->update(['visible' => false]);
            $encuesta->visible = true;
            Flasher::set('Se habilitó la encuesta general exitosamente', 'Encuesta Habilitada', 'success')->flashear();
        }
        $encuesta->save();
        return back();
    }

    public function visibilidad_noticias(Encuesta $encuesta)
    {
        if($encuesta->visible_en_noticias) {
            $encuesta->visible_en_noticias = false;
            Flasher::set('Se ocultó la encuesta en las noticias asociadas', 'Encuesta Ocultada', 'success')->flashear();
        } else {
            $encuesta->visible_en_noticias = true;
            Flasher::set('Se habilitó la encuesta en las noticias asociadas', 'Encuesta Habilitada', 'success')->flashear();
        }
        $encuesta->save();
        return back();
    }
}