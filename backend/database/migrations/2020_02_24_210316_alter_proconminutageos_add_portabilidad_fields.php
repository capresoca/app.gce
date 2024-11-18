<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProconminutageosAddPortabilidadFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->decimal('porcentaje_contributivo_portabilidad', 12, 2)
                ->nullable()
                ->after('porcentaje_contributivo');
            $table->decimal('porcentaje_subsidiado_portabilidad', 12, 2)
                ->nullable()
                ->after('porcentaje_subsidiado');

            $table->decimal('porcentaje_contributivo', 12, 2)->nullable()->change();
            $table->decimal('porcentaje_subsidiado', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->dropColumn('porcentaje_contributivo_portabilidad');
            $table->dropColumn('porcentaje_subsidiado_portabilidad');

            $table->integer('porcentaje_contributivo')->nullable()->change();
            $table->integer('porcentaje_subsidiado')->nullable()->change();
        });
    }
}
