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
        Schema::create('historial_inventario', function (Blueprint $table) {
            $table->id('id_historial');

            $table->unsignedBigInteger('id_inventario')->nullable();
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->unsignedBigInteger('id_responsable');

            $table->string('campo_modificado');
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();
            $table->timestamp('fecha_modificacion')->useCurrent();

            $table->foreign('id_inventario')->references('id_inventario')->on('inventario')->onDelete('set null');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('set null');
            $table->foreign('id_responsable')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_inventario');
    }
};
