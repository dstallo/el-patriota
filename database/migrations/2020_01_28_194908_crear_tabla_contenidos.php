<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaContenidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contenidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('visible')->default(true);
            $table->bigInteger('orden')->unsigned();

            $table->bigInteger('id_noticia')->unsigned();
            $table->foreign('id_noticia')->references('id')->on('noticias');

            $table->string('nombre');

            $table->string('imagen')->nullable();
            $table->string('tn')->nullable();
            $table->string('video')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contenidos');
    }
}
