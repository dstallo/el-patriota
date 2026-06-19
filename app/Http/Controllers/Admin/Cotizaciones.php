<?php

namespace App\Http\Controllers\Admin;

use App\Axys\Traits\TieneVisibilidad;
use App\Cotizacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Cotizaciones extends Controller
{
    use TieneVisibilidad;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cotizaciones = Cotizacion::obtener();

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    public function visibilidad(Cotizacion $cotizacion)
    {
        return $this->cambiarVisibilidad($cotizacion);
    }

    public function ordenar(Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach ($ids as $id) {
            Cotizacion::where('id', $id)->update(['orden' => $orden]);
            ++$orden;
        }

        return ['ok' => true];
    }
}
