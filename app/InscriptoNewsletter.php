<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InscriptoNewsletter extends Model
{   
    protected $table = 'newsletter';
    protected $fillable = ['email'];

    public function getFechaInscripcionAttribute($valor)
    {
        return date('d/m/Y H:i', strtotime($this->attributes['created_at']));
    }   
}
