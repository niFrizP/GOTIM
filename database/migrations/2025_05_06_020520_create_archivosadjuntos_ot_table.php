<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('archivosadjuntos_ot', function (Blueprint $table) {
            $table->id('id_archivo');
            $table->unsignedBigInteger('id_ot');
            $table->string('ruta_archivo');
            $table->string('tipo_archivo');
            $table->string('nombre_original');
            $table->timestamps();

            $table->foreign('id_ot')->references('id_ot')->on('ot')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivosadjuntos_ot');
    }
};
