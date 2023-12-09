<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'encuestas';
    protected $fillable = ['nombre', 'pregunta'];

    public function opciones()
    {
    	return $this->hasMany(Opcion::class, 'id_encuesta')->orderBy('orden');
    }

    public static function activa()
    {
        return static::where('visible', true)->with('opciones')->orderBy('id', 'desc')->first();
    }

}
