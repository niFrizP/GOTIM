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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('razon_social')->nullable()->after('apellido_cliente');
            $table->string('giro')->nullable()->after('razon_social');
            $table->unsignedBigInteger('id_region')->nullable()->after('giro');
            $table->unsignedBigInteger('id_ciudad')->nullable()->after('id_region');

            $table->foreign('id_region')
                ->references('id_region')->on('regiones')
                ->nullOnDelete();
            $table->foreign('id_ciudad')
                ->references('id_ciudad')->on('ciudades')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['id_region']);
            $table->dropForeign(['id_ciudad']);
            $table->dropColumn(['razon_social', 'giro', 'id_region', 'id_ciudad']);
        });
    }
};
