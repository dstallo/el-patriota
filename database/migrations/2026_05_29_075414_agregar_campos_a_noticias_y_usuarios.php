<?php

use App\Administrador;
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
        Schema::table('administradores', function (Blueprint $table) {
            $table->tinyInteger('rol')->default(Administrador::ROL_ADMIN);
            $table->boolean('visible')->default(true);
        });

        Schema::table('noticias', function (Blueprint $table) {
            $table->bigInteger('id_creador')->unsigned()->nullable();
            $table->foreign('id_creador')->references('id')->on('administradores')->nullOnDelete();
            $table->boolean('creado_por_api')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropForeign(['id_creador']);
            $table->dropColumn('id_creador');
            $table->dropColumn('creado_por_api');
        });

        Schema::table('administradores', function (Blueprint $table) {
            $table->dropColumn('rol');
            $table->dropColumn('visible');
        });    
    }
};
