<?php

namespace App\Http\Controllers\API;

use App\Axys\AxysListado;
use App\Http\Controllers\Controller;
use App\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Noticias extends Controller
{
    public function index(Request $request)
    {
        $listado = static::listado();

        $noticias = $listado->paginar(20);

        return response()->json([
            "error"     => false, 
            "paginador"      => [
                "total"         =>  $noticias->total(),
                "paginas"       =>  $noticias->lastPage(),
                "por_pagina"    =>  $noticias->perPage(),
                "pagina_actual" =>  $noticias->currentPage(),
                "total_pagina_actual"  =>  $noticias->count()
            ],
            "noticias"  => $noticias->makeHidden(
                ['visitas', 'embebido_1', 'embebido_2', 'con_video']
            ),
        ], 200);
    }

    public function store(Request $request) {
        $validator = static::validar($request);

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "mensaje" => "Ocurrieron errores de validación de datos.",
                "errores" => $validator->errors()->messages()
            ], 400);
        }

        $noticia = static::guardar($request, $validator->validated(), null, true);

        return [
            "error"     => false,
            "mensaje"   => "Noticia creada exitosamente",
            "noticia"   => $noticia?->makeHidden(
                ['visitas', 'embebido_1', 'embebido_2', 'con_video']
            )
        ];
    }

    // Obtener listado de noticias, con filtros y ordenamiento.
    static public function listado (?String $identificador = null, mixed $query = null) {
        
        if (! $query)
            $query = Noticia::with('seccion:id,nombre')->with('region:id,nombre')->with('creador:id,nombre');
    
        return new AxysListado(
            $identificador,
            $query,
            request(),
            ['id', 'fecha', 'titulo', 'visitas', 'grupo', 'id_region', 'id_seccion'],
            [
                'query' => [
                    ['campo' => 'titulo', 'comparacion' => 'like'],
                    ['campo' => 'bajada', 'comparacion' => 'like'],
                    ['campo' => 'texto', 'comparacion' => 'like'],
                    ['campo' => 'autor', 'comparacion' => 'like'],
                ],
                'id' => [
                    ['campo' => 'id', 'comparacion' => 'igual'],
                ],
                'seccion' => [
                    ['campo' => 'id_seccion', 'comparacion' => 'igual'],
                ],
                'region' => [
                    ['campo' => 'id_region', 'comparacion' => 'igual'],
                ],
                'grupo' => [
                    ['campo' => 'grupo', 'comparacion' => 'igual'],
                ],
                'destacada' => [
                    ['campo' => 'destacada', 'comparacion' => 'igual'],
                ],
                'visible' => [
                    ['campo' => 'destacada', 'comparacion' => 'igual'],
                ],
                'fecha_inicio' => [
                    ['campo' => 'fecha', 'comparacion' => 'mayor'],
                ],
                'fecha_fin' => [
                    ['campo' => 'fecha', 'comparacion' => 'menor'],
                ],
                'creador' => [
                    ['campo' => 'id_creador', 'comparacion' => 'igual'],
                ],
                'creado_por_api' => [
                    ['campo' => 'creado_por_api', 'comparacion' => 'igual'],
                ],
            ]
        );
    }

    // Validar campos de las noticias
    static public function validar(Request $request, $id = null) {

        $validator = Validator::make($request->all(), [
            'id_seccion'        => ['nullable', 'exists:secciones,id'],
            'id_region'         => ['nullable', 'exists:regiones,id'],
            'fecha'             => ['required', 'date'],
            'titulo'            => ['required', 'max:255'],
            'autor'             => ['nullable', 'max:255'],
            'volanta'           => ['nullable', 'max:255'],
            'bajada'            => ['nullable'],
            'texto'             => ['nullable'],
            'thumbnail'         => ['nullable', 'file', 'mimes:jpg,png', 'max:1024'],
            'thumbnail_celular' => ['nullable', 'file', 'mimes:jpg,png', 'max:1024'],
            'banner'            => ['nullable', 'file', 'mimes:jpg,png', 'max:1024'],
            'banner_celular'    => ['nullable', 'file', 'mimes:jpg,png', 'max:1024'],
            'grupo'             => ['nullable'],
            'embebido_1'        => ['nullable'],
            'embebido_2'        => ['nullable'],
        ], [
            'titulo.max'        => 'El título no debe superar los 255 caracteres',
            'autor.max'         => 'El autor no debe superar los 255 caracteres',
            'volanta.max'       => 'La volanta no debe superar los 255 caracteres'
        ], [
            'id_seccion'        => 'sección',
            'id_region'         => 'región',
            'titulo'            => 'título',
            'grupo'             => 'grupo de noticias'
        ]);

        return $validator;
    }

    // Guardar campos ya validados en la noticia especificada o una nueva.
    static public function guardar(Request $request, $form, $id = null, $por_api = false) {
        if ($id) {
            $noticia = Noticia::findOrFail($id);
        } else {
            $noticia = new Noticia();
            $noticia->visible = false;
            $noticia->creado_por_api = $por_api;
        }

        $noticia->fill($form)
            ->subir($request->file('thumbnail'), 'thumbnail')
            ->subir($request->file('thumbnail_celular'), 'thumbnail_celular')
            ->subir($request->file('banner'), 'banner')
            ->subir($request->file('banner_celular'), 'banner_celular');

        foreach (['con_video', 'destacada'] as $check) {
            $noticia->$check = boolval($request->input($check));
        }

        $noticia->id_creador = Auth::user()->id;

        $noticia->save();

        return $noticia;
    }
}
