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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('codigo')->unique(); // código único para seguimiento
            $table->string('imagen')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
