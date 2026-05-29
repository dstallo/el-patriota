<?php

namespace App\Http\Controllers\API;

use App\Axys\AxysListado;
use App\Http\Controllers\Controller;
use App\Seccion;
use Illuminate\Http\Request;

class Secciones extends Controller
{
    public function index(Request $request)
    {
        $listado = static::listado();

        $secciones = $listado->paginar(20);

        return response()->json([
            "error"     => false, 
            "paginador"      => [
                "total"         =>  $secciones->total(),
                "paginas"       =>  $secciones->lastPage(),
                "por_pagina"    =>  $secciones->perPage(),
                "pagina_actual" =>  $secciones->currentPage(),
                "total_pagina_actual"  =>  $secciones->count()
            ],
            "secciones"  => $secciones->makeHidden(['orden']),
        ], 200);
    }

    // Obtener listado de secciones, con filtros y ordenamiento.
    static public function listado (?String $identificador = null, mixed $query = null) {
        
        if (! $query)
            $query = Seccion::query();
    
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
