<?php

namespace App\Axys\Traits;

use Illuminate\Database\Eloquent\Model;
use App\Axys\AxysFlasher as Flasher;

/**
 * Agrega funcionalidad de visible/invisible a los controladors
 */

trait TieneVisibilidad
{
    protected function cambiarVisibilidad(Model $modelo)
    {
    	if($modelo->visible) {
    		$modelo->visible=false;
    		Flasher::set('Se ocultó el registro exitosamente', 'Oculto', 'success')->flashear();
    	} else {
    		$modelo->visible=true;
    		Flasher::set('Se visibilizó el registro exitosamente', 'Visible', 'success')->flashear();
    	}
    	$modelo->save();

    	return redirect()->back();
    }
}