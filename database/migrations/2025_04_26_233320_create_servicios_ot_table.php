<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios_ot', function (Blueprint $table) {
            $table->id('id_servicio_ot');
            $table->unsignedBigInteger('id_servicio')->nullable();
            $table->unsignedBigInteger('id_ot')->nullable();
            $table->timestamps();

            $table->foreign('id_servicio')->references('id_servicio')->on('servicios')->onDelete('cascade');
            $table->foreign('id_ot')->references('id_ot')->on('ot')->onDelete('cascade');

            $table->index('id_servicio');
            $table->index('id_ot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios_ot');
    }
};
