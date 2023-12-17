<?php

namespace App;

use App\Axys\Traits\TieneArchivos;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use TieneArchivos;

    protected $table = 'popups';

    protected $fillable = [
        'nombre', 'link',
    ];

    protected $dir = [
        'imagen' => 'popups-generales',
        'imagen_vertical' => 'popups-generales',
    ];
    protected $eliminarConArchivos = ['imagen', 'imagen_vertical'];
}
