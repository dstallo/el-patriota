<?php
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

use App\Console\Commands\CotizacionesRefrescar;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CotizacionesRefrescar::class)->hourly()->between('9:00', '18:00')->weekdays()->timezone('America/Argentina/Buenos_Aires');