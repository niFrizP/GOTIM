<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('nom_emp');
            $table->string('rut_empresa');
            $table->string('telefono')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('giro')->nullable();
            $table->enum('estado', ['activo', 'inhabilitado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
