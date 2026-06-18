<?php

namespace Database\Seeders;

use App\Cotizacion;
use App\Services\DolarApi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class CotizacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cotizaciones = [
            [
                'nombre'        => 'Dólar Oficial',
                'key'           => 'oficial',
                'servicio'      => 'DolarApi',
                'formateador'   => 'pesos'
            ],
            [
                'nombre'        => 'Dólar Mep',
                'key'           => 'bolsa',
                'servicio'      => 'DolarApi',
                'formateador'   => 'pesos'
            ],
            [
                'nombre'        => 'Dólar Informal',
                'key'           => 'blue',
                'servicio'      => 'DolarApi',
                'formateador'   => 'pesos'
            ],
            [
                'nombre'        => 'Dólar CCL',
                'key'           => 'contadoconliqui',
                'servicio'      => 'DolarApi',
                'formateador'   => 'pesos'
            ]
        ];

        $output = $this->command?->getOutput() ?? new ConsoleOutput();
        
        // Sólo si se está ejecutando este seeder desde la migración, debemos agregar 2 líneas en blanco para mejorar la visualización del output.
        if (! $this->command) {
            $output->writeln("");
            $output->writeln("");
        }
        
        foreach ($cotizaciones as $cotizacion) {
            Artisan::call('cotizaciones:agregar', $cotizacion, $output);
        }
    }
}
