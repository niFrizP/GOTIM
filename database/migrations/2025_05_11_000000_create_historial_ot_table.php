<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_ot', function (Blueprint $table) {
            $table->id('id_historial_ot');
            $table->unsignedBigInteger('id_ot');
            $table->unsignedBigInteger('id_responsable');
            $table->string('campo_modificado');
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();
            $table->date('fecha_modificacion');
            $table->timestamps();

            $table->foreign('id_ot')->references('id_ot')->on('ot')->onDelete('cascade');
            $table->foreign('id_responsable')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_ot');
    }
};
