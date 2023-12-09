<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaNoticias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('visible')->default(true);

            $table->bigInteger('id_seccion')->unsigned();
            $table->foreign('id_seccion')->references('id')->on('secciones');

            $table->bigInteger('id_region')->unsigned();
            $table->foreign('id_region')->references('id')->on('regiones');

            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_celular')->nullable();

            $table->string('titulo');
            $table->date('fecha');
            $table->string('autor')->nullable();
            $table->text('bajada')->nullable();
            $table->text('texto')->nullable();
            
            $table->integer('visitas')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('noticias');
    }
}
