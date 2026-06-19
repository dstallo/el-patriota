<?php
namespace App\Services;

use App\Cotizacion;
use Illuminate\Support\Facades\Http;

class ServiceBase
{
    // Todos los servicios deben implementar un método de refrescar
    public static function refrescar(mixed $cotizacion) {}

    static public function api(string $url) {
        $response = Http::get($url);

		if (! $response) {
			return ['error' => true, 'status' => null, 'message' => "Error de conexión"];
		}

        $body = $response->json();

        if (! $response->successful()) {
            return ['error' => true, 'status' => $response->status(), 'message' => "Error de Http"];
        }

        if (! $body) {
            return ['error' => true, 'status' => $response->status(), 'message' => "Respuesta vacía"];
        }

        return ['error' => false, 'status' => $response->status(), 'message' => 'Éxito', 'body' => $body];
    }

}
