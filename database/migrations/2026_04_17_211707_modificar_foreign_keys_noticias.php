<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropForeign(['id_seccion']);
            $table->bigInteger('id_seccion')->unsigned()->nullable()->change();
            $table->foreign('id_seccion')->references('id')->on('secciones')->nullOnDelete();

            $table->dropForeign(['id_region']);
            $table->bigInteger('id_region')->unsigned()->nullable()->change();
            $table->foreign('id_region')->references('id')->on('regiones')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
