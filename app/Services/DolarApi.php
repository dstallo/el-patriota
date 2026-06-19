<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Cotizacion;
use Carbon\Carbon;

class DolarApi extends ServiceBase {

    static public function refrescar(mixed $c): int {

        $cotizaciones = Cotizacion::obtener(static::class, $c);

        if ($cotizaciones->count() == 0)
            return 0;
        
        $key = $cotizaciones->count() == 1 ? $cotizaciones->first()->key : null;

        $url = 'https://dolarapi.com/v1/dolares';

        if ($key)
            $url .= '/' . $key;

        $response = parent::api($url);

        $refrescadas = 0;

        foreach($cotizaciones as $cotizacion) {
            
            $cotizacion->status = $response['status'];
            $cotizacion->message = $response['message'];

            if (! $response['error']) {
                $data = static::buscarCotizacion($response['body'], $cotizacion->key);
                if ($data && (isset($data['compra']) || isset($data['compra']))) {
                    if (isset($data['compra']))
                        $cotizacion->compra = $data['compra'];
                    if (isset($data['venta']))
                        $cotizacion->venta = $data['venta'];
                    
                    $cotizacion->refrescada = isset($data['fechaActualizacion']) ? new Carbon($data['fechaActualizacion']) : Carbon::now();
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

    static public function buscarCotizacion(array $body, string $key) {
        if (! array_is_list($body)) {
            $body = [$body];
        }

        foreach($body as $cotizacion) {
            if ($cotizacion && isset($cotizacion['casa']) && $cotizacion['casa'] == $key) {
                return $cotizacion;
            }
        }

        return null;
    }
}