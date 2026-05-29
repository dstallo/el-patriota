<?php

namespace App\Http\Controllers\API;

use App\Axys\AxysListado;
use App\Http\Controllers\Controller;
use App\Region;
use Illuminate\Http\Request;

class Regiones extends Controller
{
    public function index(Request $request)
    {
        $listado = static::listado();

        $regiones = $listado->paginar(20);

        return response()->json([
            "error"     => false, 
            "paginador"      => [
                "total"         =>  $regiones->total(),
                "paginas"       =>  $regiones->lastPage(),
                "por_pagina"    =>  $regiones->perPage(),
                "pagina_actual" =>  $regiones->currentPage(),
                "total_pagina_actual"  =>  $regiones->count()
            ],
            "regiones"  => $regiones->makeHidden(['orden']),
        ], 200);
    }

    // Obtener listado de regiones, con filtros y ordenamiento.
    static public function listado (?String $identificador = null, mixed $query = null) {
        
        if (! $query)
            $query = Region::query();
    
        return new AxysListado(
            $identificador,
            $query,
            request(),
            ['id', 'nombre', 'created_at'],
            [
                'query' => [
                    ['campo' => 'nombre', 'comparacion' => 'like'],
                ],
                'id' => [
                    ['campo' => 'id', 'comparacion' => 'igual'],
                ]
            ]
        );
    }
}
