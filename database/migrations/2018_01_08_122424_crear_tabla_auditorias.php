<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAuditorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('tabla');
            $table->index('tabla');
            $table->bigInteger('id_administrador')->unsigned()->nullable();
            $table->foreign('id_administrador')->references('id')->on('administradores')->onDelete('set null');

            $table->bigInteger('id_registro')->unisgned();

            $table->text('informacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auditorias');
    }
}
