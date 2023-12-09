<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Axys\Traits\EsOrdenable;
use App\Axys\Traits\TieneArchivos;
use App\Axys\AxysVideo as Video;

class Contenido extends Model
{
    use EsOrdenable, TieneArchivos;

    protected $table = 'contenidos';

    protected $fillable = ['nombre', 'video', 'epigrafe'];

    public $dir = ['imagen' => 'noticias/multimedia'];

    protected $eliminarConArchivos = ['imagen'];

    protected $thumbnails = [
        'imagen' => [
            'tn' => [1290, 585],
        ]
    ];

    public function noticia()
    {
        return $this->belongsTo(Noticia::class, 'id_noticia');
    }

    public function getVideo()
    {
        if(empty($this->video)) {
            return null;
        }
        $video = new Video($this->video);
        if($video->ok()) {
            return $video;
        }
        return null;
    }
    
}
