<?php

namespace App\Console\Commands;

use App\Cotizacion;
use App\Services\DolarApi;
use Illuminate\Console\Command;

class CotizacionesAgregar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cotizaciones:agregar
                            {nombre : Nombre descriptivo de la cotización}
                            {key : Identificador único de la cotización en el servicio}
                            {servicio : Servicio desde el cuál se consulta la cotización}
                            {formateador=pesos : Función para formatear el o los valores de la cotización}
                            ';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para agregar nuevas cotizaciones';

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
        $servicio = $this->servicios_namespace.$this->argument('servicio');
        if (! class_exists($servicio)) {
            $this->newLine();
            $this->error('Error: No se encontró el servicio '.$this->argument('servicio'));
            $this->logServiciosDisponibles();
            return Command::FAILURE; 
        }

        if (! method_exists(Cotizacion::class, 'format_'.$this->argument('formateador'))) {
            $this->newLine();
            $this->error('Error: No se encontró el formateador '.$this->argument('formateador'));
            $this->newLine();
            return Command::FAILURE; 
        }

        $cotizaciones = Cotizacion::obtener($servicio, $this->argument('key'));

        if ($cotizaciones->count() > 0) {
            $this->newLine();
            $this->error('Error: Ya existe la cotización '.$this->argument('key').' para el servicio '.$this->argument('servicio'));
            $this->newLine();
            return Command::FAILURE; 
        }

        Cotizacion::create([
            'nombre' => $this->argument('nombre'),
            'key' => $this->argument('key'),
            'servicio' => $servicio,
            'formateador' => $this->argument('formateador'),
        ]);

        $this->info('Cotización '.$this->argument('nombre').' creada exitosamente');

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
