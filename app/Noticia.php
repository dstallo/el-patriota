<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Axys\Traits\TieneArchivos;

class Noticia extends Model
{
	use TieneArchivos;

    protected $table = 'noticias';

    protected $fillable = ['id_seccion', 'id_region', 'titulo', 'fecha', 'autor', 'bajada', 'texto', 'embebido_1', 'embebido_2'];

    protected $dir=[
        'thumbnail'=>'contenido/noticias',
        'thumbnail_celular'=>'contenido/noticias'
    ];

    protected $eliminarConArchivos = ['thumbnail', 'thumbnail_celular'];

    public function seccion()
    {
    	return $this->belongsTo(Seccion::class, 'id_seccion');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }

    public function contenidos()
    {
        return $this->hasMany(Contenido::class, 'id_noticia')->orderBy('orden');
    }

    public function contenidosVisibles()
    {
        return $this->contenidos()->where('visible', true);
    }

    public function link()
    {
        return route('ficha_noticia', [$this, str_slug($this->titulo)]);
    }

    public function getFechaFAttribute()
    {
        return date('d/m/Y', strtotime($this->fecha));
    }
    public function getFechaFfAttribute()
    {
        return date('d/m/Y H:i', strtotime($this->fecha));
    }
    public function getFechaHtmlAttribute()
    {
        return date('Y-m-d\TH:i', strtotime($this->fecha));
    }

    public function getTituloHAttribute()
    {
        return str_replace('{', '<span>', str_replace('}', '</span>', $this->titulo));
    }
    public function getTituloPAttribute()
    {
        return str_replace('{', '', str_replace('}', '', $this->titulo));
    }

    public function getTextoConEmbebidosAttribute()
    {
        $texto = str_replace('{embebido_1}', $this->embebido_1, $this->texto);
        $texto = str_replace('{embebido_2}', $this->embebido_2, $texto);
        return $texto;
    }
    
}
