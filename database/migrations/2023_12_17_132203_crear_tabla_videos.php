<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->boolean('visible')->default(true);
            $table->integer('orden')->unsigned();

            $table->boolean('destacado')->default(false);

            $table->string('volanta')->nullable();
            $table->string('nombre');
            $table->text('bajada')->nullable();
            $table->string('link');
            $table->string('imagen')->nullable();
            $table->string('tn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
