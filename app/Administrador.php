<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ReiniciarPassword as ResetPasswordNotification;
use App\Axys\Traits\TieneArchivos;

class Administrador extends Authenticatable
{
    use Notifiable, TieneArchivos;

    protected $table = 'administradores';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dir=[
        'foto'=>'contenido/administradores/fotos'
    ];

    protected $thumbnails = [
        'foto' => [
            'tn' => [160, 160],
        ]
    ];

    protected $eliminarConArchivos = ['foto'];

    public function tnPerfil()
    {
        if($this->tiene('tn')) {
            return $this->url('tn');
        }

        return url('img/usuario.svg');
    }

    //reescribo este método, para customizar el email del reseteo del password
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // public static function roles()
    // {
    //     return ['Administrador', 'Operador'];
    // }

    // public function admin()
    // {
    //     return $this->rol=='Administrador';
    // }
    // public function operador()
    // {
    //     return $this->rol=='Operador';
    // }
    
}
