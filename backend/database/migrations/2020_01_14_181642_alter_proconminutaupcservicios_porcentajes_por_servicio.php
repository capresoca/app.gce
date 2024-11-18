<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProconminutaupcserviciosPorcentajesPorServicio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->decimal('porcent_pob_contributivo', 5, 2)->nullable()->change();
            $table->decimal('porcent_pob_subsidiado', 5, 2)->nullable()->change();
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
            $table->integer('porcent_pob_contributivo')->nullable()->change();
            $table->integer('porcent_pob_subsidiado')->nullable()->change();
        });
    }
}
