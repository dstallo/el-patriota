<?php

namespace App;

use App\Axys\Traits\TieneArchivos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Noticia extends Model
{
    use TieneArchivos;

    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = ['id_seccion', 'id_region', 'volanta', 'titulo', 'fecha', 'autor', 'bajada', 'texto', 'embebido_1', 'embebido_2', 'grupo'];

    protected $dir = [
        'thumbnail' => 'contenido/noticias',
        'thumbnail_celular' => 'contenido/noticias',
        'banner' => 'contenido/noticias',
        'banner_celular' => 'contenido/noticias',
    ];

    protected $eliminarConArchivos = ['thumbnail', 'thumbnail_celular', 'banner', 'banner_celular'];

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
        return route('ficha_noticia', [$this, Str::slug($this->titulo)]);
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

    public function getVertical()
    {
        if ($this->tiene('banner_celular')) {
            return $this->url('banner_celular');
        }

        return $this->url('banner');
    }

    public function scopeObtener($query)
    {
        return $query->where('noticias.visible', true)
            // visibilidad en secciones
            ->select('noticias.*')
            ->leftJoin('secciones as s', 's.id', '=', 'noticias.id_seccion')
            ->where(function($query){
                return $query->where('s.visible', true)->orWhereNull('s.visible');
            })
            ->with('seccion')
            ->with('region')
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc');
    }

    public function scopeLeidas($query)
    {
        return $query->where('noticias.visible', true)
            // visibilidad en secciones
            ->select('noticias.*')
            ->leftJoin('secciones as s', 's.id', '=', 'noticias.id_seccion')
            ->where(function($query){
                return $query->where('s.visible', true)->orWhereNull('s.visible');
            })
            ->where('fecha', '>=', Carbon::now()->subDays(16))
            // /
            ->orderBy('visitas', 'desc');
    }

    public function obtenerCategorias() {
        $categorias = [];
        if ($this->region)
            $categorias[] = $this->region->nombre;
        if ($this->seccion)
            $categorias[] = $this->seccion->nombre;

        return implode(' | ', $categorias);
    }

    public static function obtenerGrupos(){
        
        return Noticia::select('grupo as valor')->whereNotNull('grupo')->groupBy('valor')->orderBy('valor', 'asc')->get();
    
    }
}
