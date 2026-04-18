<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';
    protected $fillable = ['nombre', 'valor'];

    protected $primaryKey = 'nombre';

    public $timestamps = false;

    public static function obtener($nombre = null) {

        if ($nombre == null) {
            return static::pluck('valor', 'nombre');
        }

        return static::where('nombre', $nombre)->first();
    }
}
