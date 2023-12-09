<?php

namespace App\Axys\Traits;

trait EsOrdenable
{
	/**
	 * Asigna en el campo orden, el próximo correspondiente. Se le puede pasar un array
	 * de condiciónes, como para que sea el "subpróximo", por ejemplo si tengo productos
	 * categorizados y quiero que el ordenamiento sea aislado por categoría, haría:
	 * (new Producto)->ordenar([
	 *		['id_categoria', $categoria->id]
	 *		o
	 *		['id_categoria', '=', $categoria->id]
	 * ]);
	 */
    public function ordenar($where=[])
    {
    	$query=self::query();
    	foreach($where as $condicion) {
    		if(count($condicion)==2) {
    			$query->where($condicion[0],$condicion[1]);
    		} elseif(count($condicion)==3) {
    			$query->where($condicion[0],$condicion[1],$condicion[2]);
    		}
    	}
    	$this->orden=intval($query->max('orden'))+1;
    	return $this;
    }
}
