<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'encuestas';
    protected $fillable = ['nombre', 'pregunta', 'id_noticia'];
    
    public function opciones()
    {
    	return $this->hasMany(Opcion::class, 'id_encuesta')->orderBy('orden');
    }

    public function scopeWithNoticiasCount($query)
    {
        $query->addSelect(['noticias_count' => Noticia::selectRaw('COUNT(1)')->whereColumn('id_encuesta', 'encuestas.id')]);   
    }

    public static function activa()
    {
        return static::where('visible', true)->with('opciones')->orderBy('id', 'desc')->first();
    }

}
