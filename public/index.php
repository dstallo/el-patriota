<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Determinar si la aplicación está en modo de mantenimiento...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Registrar el cargador automático de Composer...
require __DIR__.'/../vendor/autoload.php';

// 3. Arrancar Laravel y manejar la petición HTTP entrante...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());