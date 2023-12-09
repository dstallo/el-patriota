<?php

namespace App\Axys\Traits;

use App;

/**
 * Trait para modelos de Eloquent
 * Multi-idiomiza (ja) el modelo, pero usando campos _es, _en, etc en la misma tabla.
 * Tal vez no sea lo más lindo para agregar idiomas nuevos, pero es lo más eficiente :-D.
 */

trait EsMultiIdioma
{
    /**
     * Multidiomiza los fillable que estan idiomatizados.
     *
     * @return void
     */
    public function initializeEsMultiIdioma()
    {
        //esto corre al instanciarse un objeto de la clase...

        $idiomas = config('idiomas.idiomas');
        $agregar = []; $borrar = [];
        for($i=0;$i<count($this->fillable);$i++) {
            $campo = $this->fillable[$i];
            //dump($campo, $this->idiomatizados);
            if(in_array($campo, $this->idiomatizados)) {
                $borrar[] = $i;
                foreach($idiomas as $idioma => $nombre) {
                    $agregar[] = $campo . '_' . $idioma;
                }
            }
        }
        foreach($borrar as $i) unset($this->fillable[$i]);
        foreach($agregar as $campo) $this->fillable[] = $campo;
        $this->fillable = array_values($this->fillable);
    }

    /**
     * Multidiomiza los fillable que estan idiomatizados.
     *
     * @return void
     */
    public function traducir($campo)
    {
        $campo = $campo . "_" . App::getLocale();
        return $this->$campo;
    }

    /**
     * Multidiomiza con "accessors" virtuales.
     * OJO-> ESTOY REESCRIBIENDO __get... no lo voy a poder reescribir de nuevo en otro trait / lado!
     *
     * @return void
     */
    public function __get($key)
    {
        if(in_array($key, $this->idiomatizados)) {
            return $this->traducir($key);
        }

        return parent::__get($key);
    }
}