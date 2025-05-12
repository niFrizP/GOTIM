<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFechaModificacionToDatetimeOnHistorialOt extends Migration
{
    public function up(): void
    {
        // Necesitas doctrine/dbal para usar ->change()
        // composer require doctrine/dbal

        Schema::table('historial_ot', function (Blueprint $table) {
            $table->dateTime('fecha_modificacion')->change();
        });
    }

    public function down(): void
    {
        Schema::table('historial_ot', function (Blueprint $table) {
            $table->date('fecha_modificacion')->change();
        });
    }
}
