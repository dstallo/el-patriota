<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ReiniciarPassword as ResetPasswordNotification;
use App\Axys\Traits\TieneArchivos;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;

class Administrador extends Authenticatable
{
    use Notifiable, TieneArchivos;

    protected $table = 'administradores';

    const ROL_ADMIN = 1;
    const ROL_ADMIN_STR = 'admin';
    const ROL_ADMIN_HUMAN = 'Administrador';
    const ROL_API = 2;
    const ROL_API_STR = 'api';
    const ROL_API_HUMAN = 'Sólo API';

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

    public function obtenerRol(?string $attr = 'human') {
        return static::parsearRol($this->rol, $attr);
    }

    public function soloApi()
    {
        return $this->rol == static::ROL_API;
    }

    public function admin()
    {
        return $this->rol == static::ROL_ADMIN;
    }

    public function tieneRol(string $rol) {
        return $this->rol == static::parsearRol($rol, 'value');
    }

    public function logueado() {
        return $this->id == Auth::user()?->id;
    }

    //reescribo este método, para customizar el email del reseteo del password
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // Funciones estáticas

    public static function parsearRol(mixed $rol = null, ?String $attr = 'str')
    {
        if ($attr == 'str') {
            switch($rol) {
                case static::ROL_ADMIN: case static::ROL_ADMIN_HUMAN: case static::ROL_ADMIN_STR: return static::ROL_ADMIN_STR;
                case static::ROL_API: case static::ROL_API_HUMAN: case static::ROL_API_STR: return static::ROL_API_STR;
            }
        }
        elseif ($attr == 'human') {
            switch($rol) {
                case static::ROL_ADMIN: case static::ROL_ADMIN_HUMAN: case static::ROL_ADMIN_STR: return static::ROL_ADMIN_HUMAN;
                case static::ROL_API: case static::ROL_API_HUMAN: case static::ROL_API_STR: return static::ROL_API_HUMAN;
            }
        }
        elseif ($attr == 'value') {
            switch($rol) {
                case static::ROL_ADMIN: case static::ROL_ADMIN_HUMAN: case static::ROL_ADMIN_STR: return static::ROL_ADMIN;
                case static::ROL_API: case static::ROL_API_HUMAN: case static::ROL_API_STR: return static::ROL_API;
            }
        }

        return null;
    }

    public static function rolesPosibles(?String $format = 'object') {
        if ($format == 'values')
            return [static::ROL_ADMIN, static::ROL_API];
        else {
            return [
                (object) ["value" => static::ROL_ADMIN, "str" => static::ROL_ADMIN_STR, "human" => static::ROL_ADMIN_HUMAN],
                (object) ["value" => static::ROL_API, "str" => static::ROL_API_STR, "human" => static::ROL_API_HUMAN]
            ];
        }
            
    }
    
}
