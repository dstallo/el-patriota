<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Axys\Traits\EsOrdenable;

class Region extends Model
{
    use EsOrdenable;
    
    protected $table = 'regiones';

    protected $fillable = ['nombre'];

    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'id_region');
    }

    public static function front()
    {
    	return static::orderBy('orden')->get();
    }

    public function link()
    {
        return route('region', [$this, str_slug($this->nombre)]);
    }
}
