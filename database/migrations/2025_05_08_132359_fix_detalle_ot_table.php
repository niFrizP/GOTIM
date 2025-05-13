<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_ot', function (Blueprint $table) {
            // eliminar columnas incorrectas
            $table->dropForeign(['id_producto']);
            $table->dropColumn(['id_producto', 'cantidad']);

            // agregar columna correcta según MER
            $table->text('descripcion_actividad')->after('id_ot');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ot', function (Blueprint $table) {
            $table->dropColumn('descripcion_actividad');

            $table->unsignedBigInteger('id_producto')->nullable();
            $table->integer('cantidad')->nullable();

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('restrict');
        });
    }
};
