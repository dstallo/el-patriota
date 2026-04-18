<?php

namespace Database\Seeders;

use App\Configuracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuracion::create(["nombre" => "GRUPO_ACTIVO", "valor" => null]);
    }
}
