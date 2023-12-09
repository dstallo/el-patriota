<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;

use App\Region;

class Regiones extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Region::orderBy('orden');

        $listado=new Listado(
            'listado_regiones',
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
        
        //$regiones=$listado->paginar(50);
        $regiones=$listado->get();

        return view('admin.regiones.index', compact('regiones', 'listado'));
    }

    public function eliminar(Region $region)
    {
        try {
            $region->delete();
            $flasher=Flasher::set("La región fue eliminada.", 'Región Eliminada', 'success');
        } catch (\Exception $e) {
            $flasher=Flasher::set('No se pudo borrar la región, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function crear(Request $request)
    {
        $region = new Region;

        return view('admin.regiones.crear',compact('region'));
    }

    public function editar(Region $region, Request $request)
    {
        return view('admin.regiones.editar',compact('region'));
    }

    public function guardar(Request $request, $id=null)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        if($id) {
            $region=Region::findOrFail($id);
        } else {
            $region=new Region;
            $region->ordenar();
        }

        $region->fill($request->all())
            ->save();

        if($id) {
            Flasher::set("La región fue modificada exitosamente.", 'Región Editada', 'success')->flashear();
            return back();
        } else {
            Flasher::set("La región fue creada exitosamente.", 'Región Creada', 'success')->flashear();
            return redirect()->route('regiones');
        }
    }

    public function ordenar(Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach($ids as $id) {
            Region::where('id', $id)->update(['orden' => $orden]);
            $orden += 1;
        }
        return ['ok'=>true];
    }
}