<?php

namespace App;

use App\Axys\AxysVideo;
use App\Axys\Traits\EsOrdenable;
use App\Axys\Traits\TieneArchivos;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use EsOrdenable;
    use TieneArchivos;

    protected $table = 'videos';

    protected $fillable = ['nombre', 'bajada', 'link', 'volanta'];

    protected $dir = [
        'imagen' => 'videos',
        // 'imagen_vertical' => 'videos'
    ];

    protected $thumbnails = [
        'imagen' => [
            'tn' => [280, 150],
        ],
    ];

    protected $eliminarConArchivos = ['imagen', 'tn'/* , 'imagen_vertical' */];

    public function getVertical()
    {
        if ($this->tiene('imagen_vertical')) {
            return $this->url('imagen_vertical');
        }

        return $this->url('imagen');
    }

    public function getVideo()
    {
        if (empty($this->link)) {
            return null;
        }
        $video = new AxysVideo($this->link);
        if ($video->ok()) {
            return $video;
        }

        return null;
    }
}
