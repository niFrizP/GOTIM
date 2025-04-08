<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ot', function (Blueprint $table) {
            $table->id('id_ot');

            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_responsable')->nullable();
            $table->unsignedBigInteger('id_estado');

            $table->dateTime('fecha_creacion')->useCurrent();
            $table->string('estado', 50); // campo redundante, podría eliminarse si usas solo id_estado

            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
            $table->foreign('id_responsable')->references('id')->on('users')->onDelete('set null');
            $table->foreign('id_estado')->references('id_estado')->on('estado_ot')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ot');
    }
};