<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Axys\Traits\EsOrdenable;
use Illuminate\Support\Str;

class Seccion extends Model
{
    use EsOrdenable;
    
    protected $table = 'secciones';

    protected $fillable = ['nombre'];

    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'id_seccion');
    }

    public static function front()
    {
    	return static::where('visible', true)->orderBy('orden')->get();
    }

    public function link()
    {
        return route('seccion', [$this, Str::slug($this->nombre)]);
    }
}
