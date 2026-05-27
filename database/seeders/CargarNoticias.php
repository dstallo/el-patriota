<?php

namespace Database\Seeders;

use App\Noticia;
use App\Region;
use App\Seccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargarNoticias extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seccion = Seccion::create(["nombre" => "Sección 1", "orden" => 1]);
        $region = Region::create(["nombre" => "Región 1", "orden" => 1]);
        Noticia::factory(10)->create([
            'id_seccion' => $seccion->id,
            'id_region' => $region->id
        ]);
    }
}
