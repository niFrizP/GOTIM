<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ot', function (Blueprint $table) {
            $table->date('fecha_entrega')->nullable()->after('fecha_creacion');
        });
    }

    public function down()
    {
        Schema::table('ot', function (Blueprint $table) {
            $table->dropColumn('fecha_entrega');
        });
    }
};
