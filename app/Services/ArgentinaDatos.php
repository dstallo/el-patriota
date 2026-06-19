<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Cotizacion;
use Carbon\Carbon;

class ArgentinaDatos extends ServiceBase {

    static public function refrescar(mixed $c): int {

        $cotizaciones = Cotizacion::obtener(static::class, $c);

        if ($cotizaciones->count() == 0)
            return 0;
        
        $url = 'https://api.argentinadatos.com/v1/finanzas/indices/**key**';

        $response = parent::api($url);

        $refrescadas = 0;

        foreach($cotizaciones as $cotizacion) {

            $response = parent::api(str_replace('**key**', $cotizacion->key, $url));
            
            $cotizacion->status = $response['status'];
            $cotizacion->message = $response['message'];

            if (! $response['error']) {
                $data = $response['body'];
                
                if (isset($data['valor'])) {
                    $cotizacion->compra = $data['valor'];
                    
                    $cotizacion->refrescada = isset($data['fecha']) ? new Carbon($data['fecha']) : Carbon::now();
                    $refrescadas ++;
                }
                else {
                    $cotizacion->message = "No encontrada";
                }
            }

            $cotizacion->save();
        }

        return $refrescadas;
    }
}