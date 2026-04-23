<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Axys\Traits\TieneArchivos;
use App\Axys\Traits\EsOrdenable;

class Banner extends Model
{
	use TieneArchivos, EsOrdenable;

    protected $table = 'banners';

    protected $fillable = ['ubicacion', 'nombre', 'link'];

    protected $dir = [
        'imagen' => 'contenido/slides/imagenes',
        'imagen_responsive' => 'contenido/slides/imagenes',
    ];

    protected $eliminarConArchivos = ['imagen', 'imagen_responsive'];

    public static function ubicaciones()
    {
        return ['Horizontal', 'Lateral'];
    }

    public static function tamanos()
    {
        return [
            'Horizontal' => [1800, 260],
            'Lateral' => [450, null],
        ];
    }

    public static function tamanosResponsive()
    {
        return [
            'Horizontal' => [800, null],
            'Lateral' => [800, null],
        ];
    }

    public function resizeImagen()
    {
        if(Banner::tamanos()[$this->ubicacion][0] && Banner::tamanos()[$this->ubicacion][1]) {
            $this->fit(
                Banner::tamanos()[$this->ubicacion][0],
                Banner::tamanos()[$this->ubicacion][1],
                'imagen'
            );
        } else {
            $this->resize(
                Banner::tamanos()[$this->ubicacion][0],
                Banner::tamanos()[$this->ubicacion][1],
                'imagen'
            );
        }
    }

    public function resizeImagenResponsive()
    {
        if(Banner::tamanosResponsive()[$this->ubicacion][0] && Banner::tamanosResponsive()[$this->ubicacion][1]) {
            $this->fit(
                Banner::tamanosResponsive()[$this->ubicacion][0],
                Banner::tamanosResponsive()[$this->ubicacion][1],
                'imagen_responsive'
            );
        } else {
            $this->resize(
                Banner::tamanosResponsive()[$this->ubicacion][0],
                Banner::tamanosResponsive()[$this->ubicacion][1],
                'imagen_responsive'
            );
        }
    }

    public function urlImagenResponsive()
    {
        if($this->tiene('imagen_responsive')) {
            return $this->url('imagen_responsive');
        }
        return $this->url('imagen');
    }

    public function linkContador()
    {
        if ($this->link)
            return route('link_banner', $this);

        return null;
    }
    
}
