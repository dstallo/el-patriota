<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'nombre' => 'Diego',
            'email' => 'dstallo@gmail.com',
            'password' => bcrypt('p@ssw@rd'),

            'api_token' => Str::random(60),
            'remember_token' => Str::random(10),

            //'rol' => 'Administrador',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('administradores')->insert([
            'nombre' => 'Gastón',
            'email' => 'gastonsiseles@gmail.com',
            'password' => bcrypt('p@ssw@rd'),

            'api_token' => Str::random(60),
            'remember_token' => Str::random(10),

            //'rol' => 'Administrador',

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
