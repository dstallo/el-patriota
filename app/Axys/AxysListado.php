<?php

/**
 * Maneja el seteo, cacheo y procesamiento de filtros,
 * ordenamiento y paginación de un listado.
 *
 */

namespace App\Axys;

use Illuminate\Support\Facades\Session as Cache; //Reversionado para usar la sesión (la Caché es GLOBAL)
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AxysListado
{
    protected $identificador;
    protected $query;
    protected $input;

    protected $ordenables;
    protected $filtros;
    protected $minutosCache;

    protected $orden;
    protected $sentido;
    protected $valores; //valores de los filtros

    /**
     * Parametros del constructor:
     *    - identificador del listado (string)
     *    - querybuilder (que puede ya traer algunas cosas)
     *    - input (objeto tipo Request, que puede tener algun preproceso ya hecho)
     *
     *    - ordenables: array de campos por los que puede ser ordenado
     *    - filtros: array de filtros con las reglas correspondientes a cada filtro
     *      [
     *        'nombre-del-input'=>[
     *          ['campo'=>'nombre-del-campo','comparacion'=>tipo-de-comparacion],
     *          [regla2],
     *          [regla3],..
     *        ],
     *        'filtro-2'=>[reglas..],
     *        ...
     *      ]
     *      (tipo-de-comparacion: 'igual'/'like'/'mayor'/'menor')
     *    - minutosCache: cuanto tiempo cachear
     */
    public function __construct($identificador, $query, Request $input, $ordenables=[], $filtros=[], $minutosCache=30)
    {
        $this->identificador = $identificador;
        $this->query = $query;
        $this->input = $input;
        $this->ordenables = $ordenables;
        $this->filtros = $filtros;
        $this->minutosCache = $minutosCache;

        $this->valores=[];
        $this->obtenerValores();

        if(count($this->filtros)>0)
          $this->procesarFiltros();
        if(count($this->ordenables)>0)
          $this->procesarOrdenamiento();
    }

    private function olvidarPagina()
    {
      $claveCache='axys.listado.'.$this->identificador;

      Cache::forget("$claveCache.pagina");
    }

    protected function obtenerValores()
    {
      $claveCache='axys.listado.'.$this->identificador;

      foreach ($this->filtros as $filtro => $reglas) {
        $this->valores[$filtro] = Cache::get("$claveCache.valores.$filtro", '');
        if($this->input->exists($filtro)) {
            $this->olvidarPagina();
            $this->valores[$filtro] = $this->input->get($filtro);
            //cache(["$claveCache.valores.$filtro" => $this->valores[$filtro]], $this->minutosCache);
                session(["$claveCache.valores.$filtro" => $this->valores[$filtro]]);
        }
      }
    }

    protected function procesarOrdenamiento()
    {
        $claveCache='axys.listado.'.$this->identificador;
        
        $this->orden = Cache::get("$claveCache.orden", 'id');
        $this->sentido = Cache::get("$claveCache.sentido", 'asc');

        if ($this->input->has('orden')) {
          $this->olvidarPagina();

            $this->orden = in_array($this->input->get('orden'), $this->ordenables) ? $this->input->get('orden') : $this->orden;
            //cache(["$claveCache.orden"=>$this->orden], $this->minutosCache);
            session(["$claveCache.orden"=>$this->orden]);
        }

        if ($this->input->has('sentido')) {
          $this->olvidarPagina();

            $this->sentido = in_array($this->input->get('sentido'), ['asc','desc']) ? $this->input->get('sentido') : $this->sentido;
            //cache(["$claveCache.sentido"=>$this->sentido], $this->minutosCache);
            session(["$claveCache.sentido"=>$this->sentido]);
        }
        
        $this->query->orderBy($this->orden, $this->sentido);
    }

    protected function procesarFiltros()
    {
      foreach($this->filtros as $filtro => $reglas) {
        if(!empty($valor=$this->valores[$filtro])||$this->valores[$filtro]===0||$this->valores[$filtro]==="0") {
          $this->procesarFiltro($filtro, $valor, $reglas);
        }
      }
    }

    private function procesarFiltro($filtro, $valor, $reglas)
    {
    $this->query->where(
      function($query) use ($reglas, $valor) {
        foreach($reglas as $regla) {
          $campo=$regla['campo'];
          
          if($regla['comparacion']=='igual') {
                $query->orWhere($campo, $valor);
                continue;
              }

              if($regla['comparacion']=='like') {
                $query->orWhere($campo, 'like', '%'.$valor.'%');
                continue;
              }

              if($regla['comparacion']=='mayor') {
                $query->orWhere($campo, '>=', $valor);
                continue;
              }

              if($regla['comparacion']=='menor') {
                $query->orWhere($campo, '<=', $valor);
                continue;
              }
            }
      }
    );
    }


    /**
   * Hace un paginate de Eloquent, pero conserva el número de página en la caché.
   * La misma clase se encarga de olvidar el número cuando cambió un filtro
   * o un ordenamiento.
   * TODO: cambiar la variable GET de la página (por ahora es 'page').
     */
    public function paginar($registrosPorPagina=null)
    {
      $claveCache='axys.listado.'.$this->identificador;

      //fix para cachear la página también
      $pagina=Cache::get("$claveCache.pagina", '1');
      if($this->input->has('page')) {
        $pagina=$this->input->get('page');
        //cache(["$claveCache.pagina"=>$pagina], $this->minutosCache);
        session(["$claveCache.pagina"=>$pagina]);
      }
      Paginator::currentPageResolver(function() use ($pagina) {
         return $pagina;
    });

      if(!$registrosPorPagina) {
        $registrosPorPagina=config('axys.listado.registros_por_pagina');
      }

    return $this->query->paginate($registrosPorPagina);
    }

    /**
     * Devuelve la colección completa con el ordenamiento y los filtros procesados.
     */
    public function get()
    {
        return $this->query->get();
    }

    /**
     * Devuelve el querybuilder con el ordenamiento y los filtros procesados.
     */
    public function getQueryBuilder()
    {
        return $this->query;
    }

    /**
     * Setea el querybuilder.
     */
    public function setQueryBuilder($query)
    {
        return $this->query = $query;
    }

    /**
     * Devuelve el href para ordenar por tal o cual campo.
     */
    public function linkOrden($campo)
    {
        if (!in_array($campo, $this->ordenables)) {
            return '';
        }
        $link="?orden=$campo&sentido=";
        if ($this->orden==$campo) {
            $link .= $this->sentido=='asc' ? 'desc' : 'asc';
        } else {
            $link .= 'asc';
        }
        return $link;
    }

    /**
     * Devuelve el valor de un input de un filtro,
     * tipo el old de laravel para formularios.
     */
    public function old($filtro)
    {
      if(array_key_exists($filtro, $this->valores)) {
        return $this->valores[$filtro];
      }
      return '';
    }

    public function setFiltro($filtro, $valor)
    {
        if(array_key_exists($filtro, $this->valores)) {
            return $this->valores[$filtro] = $valor;
        }
        return false;
    }
}
