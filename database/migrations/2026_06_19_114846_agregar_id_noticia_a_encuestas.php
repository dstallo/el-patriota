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
            $table->bigInteger('id_encuesta')->unsigned()->nullable();
            $table->foreign('id_encuesta')->references('id')->on('encuestas')->nullOnDelete();
        });
        Schema::table('encuestas', function (Blueprint $table) {
            $table->boolean('visible_en_noticias')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropForeign(['id_encuesta']);
            $table->dropColumn('id_encuesta');
        });
        Schema::table('encuestas', function (Blueprint $table) {
            $table->dropColumn('visible_en_noticias');
        });
    }
};
