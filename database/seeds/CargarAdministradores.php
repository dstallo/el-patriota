<?php

use Illuminate\Database\Seeder;

class CargarAdministradores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('administradores')->insert([
            'nombre' => 'Gaby',
            'email' => 'gaby86@gmail.com',
            'password' => bcrypt('mandi0ca'),

            'api_token' => str_random(60),
            'remember_token' => str_random(10),

            //'rol' => 'Administrador',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('administradores')->insert([
            'nombre' => 'Gastón',
            'email' => 'gastonsiseles@gmail.com',
            'password' => bcrypt('mandioca'),

            'api_token' => str_random(60),
            'remember_token' => str_random(10),

            //'rol' => 'Administrador',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
