<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Cotizacion;
use Carbon\Carbon;

class DolarApi extends ServiceBase {

    const API_ROOT = 'https://dolarapi.com/v1';

    static public function refrescar(mixed $c): int {

        $cotizaciones = Cotizacion::obtener(static::class, $c);

        if ($cotizaciones->count() == 0)
            return 0;
        
        $key = $cotizaciones->count() == 1 ? $cotizaciones->first()->key : null;

        $response = static::api($key);

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

    static public function api(?string $key = null) {
        $url = static::API_ROOT . '/dolares';

        if ($key)
            $url .= '/' . $key;

        $response = Http::get($url);

		if (! $response) {
			return ['error' => true, 'status' => null, 'message' => "Error de conexión"];
		}

        $body = $response->json();

        // dd($body);

        if (! $response->successful()) {
            return ['error' => true, 'status' => $response->status(), 'message' => "Error de Http"];
        }

        if (! $body) {
            return ['error' => true, 'status' => $response->status(), 'message' => "Respuesta vacía"];
        }

        return ['error' => false, 'status' => $response->status(), 'message' => 'Éxito', 'body' => $body];
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