<?php

namespace App\Console\Commands;

use App\Cotizacion;
use App\Services\DolarApi;
use Illuminate\Console\Command;

class CotizacionesRefrescar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cotizaciones:refrescar 
                                {--servicio= : se refrescarán las cotizaciones del servicio}
                                {--key= : se refrescará la cotización con key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite refrescar las cotizaciones ya agregadas, de todos o de un servicio o cotización en específico.';

    /**
     * Servicios registrados disponibles
     */

    protected $servicios = [
        DolarApi::class
    ];

    /**
     * Namespace de los servicios registrados
     */

    protected $servicios_namespace = 'App\\Services\\';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->option('key');

        if ($this->option('key') && ! $this->option('servicio')) {
            $this->error('Error: Si especificas una --key de cotización, debes especificar un --servicio');
            $this->logServiciosDisponibles();

            return Command::FAILURE; 
        }

        if ($this->option('servicio')) {
            $servicio = $this->servicios_namespace . $this->option('servicio');

            if (! class_exists($servicio)) {
                $this->error('Error: El parámetro --servicio ingresado no corresponde a un servicio existente.');
                $this->logServiciosDisponibles();

                return Command::FAILURE; 
            }

            $servicios = [$servicio];
        }
        else
            $servicios = $this->servicios;

        if (count($servicios) == 0) {
            $this->error('Error: No se encontraron servicios para refrescar cotizaciones.');
            return Command::FAILURE; 
        }
        
        $this->getOutput()->progressStart(count($servicios));

        foreach ($servicios as $servicio) {
            $short = str_replace($this->servicios_namespace, "", $servicio);
            $this->newLine();
            $this->newLine();
            $this->line('Refrescando servicio '. $short . ' ...');
            $refrescadas = $servicio::refrescar($key);
            $this->newLine();
            $this->info ('Se refrescaron '.$refrescadas.' cotizaciones del servicio '.$short.'.');
            $this->newLine();

            $this->getOutput()->progressAdvance();
        }
        
        $this->getOutput()->progressFinish();
        
        return Command::SUCCESS;
    }

    public function logServiciosDisponibles(): void {
        $this->newLine();
        $this->info('Servicios Disponibles: ');
        foreach ($this->servicios as $servicio)
            $this->info(" - ".str_replace($this->servicios_namespace, "", $servicio));
        $this->newLine();
    }
}
