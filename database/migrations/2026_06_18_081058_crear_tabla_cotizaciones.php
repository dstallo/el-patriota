<?php

use Database\Seeders\CotizacionesSeeder;
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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('nombre'); // Nombre humano para mostrar
            $table->string('key'); // Key de identificación en el servicio.
            $table->string('servicio'); // Servicio que actualiza esta cotización
            $table->string('formateador'); // Función formateadora de la cotización ($, %, USD ...)
            $table->float('compra')->nullable(); // Valor de compra de la cotización.
            $table->float('venta')->nullable(); // Valor de venta de la cotización (no todas las cotizaciones tienen doble valor)
            $table->dateTime('refrescada')->nullable(); // Último refresco de los valores (compra / venta) de la cotización.
            $table->string('status')->nullable(); // Último response status code.
            $table->string('message')->nullable(); // Último response message.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
