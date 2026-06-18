<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'nombre',
        'servicio',
        'key',
        'formateador'
    ];

    protected $casts = [
        'refrescada' => 'datetime'
    ];

    // Obtener cotización formateada.
    public function format($valor = 'compra') {
        $formateador = 'format_' . $this->formateador;
        return $this->$formateador($valor);
    }

    // Formateadores
    public function format_dolares($valor = 'compra') {
        return 'U$D '.number_format($this->$valor, 2, ',', '.');
    }

    public function format_pesos($valor = 'compra') {
        return '$ '.number_format($this->$valor, 2, ',', '.');
    }

    // #### Funciones estáticas ####

    // Obtener cotización desde Servicio & Key o todas las cotización de un Servicio.
    static public function obtener (?string $servicio = null, mixed $key = null) {

        $query = Cotizacion::query();

        if ($servicio) {
            $query->where('servicio', $servicio);
        }
            
        if ($key) {
            $query->where('key', $key);
        }

        return $query->get();
    }
}
