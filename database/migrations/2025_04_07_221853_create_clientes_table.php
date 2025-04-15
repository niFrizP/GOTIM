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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre_cliente');
            $table->string('apellido_cliente');
            $table->string('rut')->nullable();
            $table->string('email')->unique();
            $table->string('direccion')->nullable();
            $table->string('nro_contacto')->nullable();
            $table->enum('estado', ['activo', 'inhabilitado'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
