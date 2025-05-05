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
        Schema::create('inventario', function (Blueprint $table) {
            $table->id('id_inventario');

            $table->unsignedBigInteger('id_producto')->nullable();
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('set null');

            $table->integer('cantidad')->nullable();
            $table->dateTime('fecha_ingreso')->nullable();
            $table->dateTime('fecha_salida')->nullable();
            $table->enum('estado', ['activo', 'eliminado'])->default('activo');

            $table->timestamps(); // Opcional, pero recomendable para trazabilidad
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
