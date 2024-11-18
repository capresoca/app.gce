<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProconminutaupcserviciosAddPorcentajePoblacionContributivoAddPorcentajePoblacionSubsidiado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->integer('porcent_pob_contributivo')->nullable()
                ->comment('Porcentaje de población del régimen contributivo cubierta');
            $table->integer('porcent_pob_subsidiado')->nullable()
                ->comment('Porcentaje de población del régimen subsidiado cubierta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->dropColumn('porcent_pob_contributivo');
            $table->dropColumn('porcent_pob_contributivo');
        });
    }
}
