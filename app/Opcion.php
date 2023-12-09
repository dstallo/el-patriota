<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Axys\Traits\EsOrdenable;

class Opcion extends Model
{
    use EsOrdenable;
    
    protected $table = 'opciones';
    protected $fillable = ['opcion'];

    public function encuesta()
    {
    	return $this->belongsTo(Encuesta::class, 'id_encuesta');
    }

}
