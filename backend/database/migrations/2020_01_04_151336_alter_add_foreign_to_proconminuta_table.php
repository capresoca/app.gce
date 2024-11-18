<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddForeignToProconminutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->foreign('gn_municipio_id')->references('id')->on('gn_municipios');
            $table->foreign('ce_proconestprevio_id')->references('id')->on('ce_proconestprevios');
        });

        Schema::table('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->foreign('ce_proconminutageo_id')->references('id')->on('ce_proconminutageos');
            $table->foreign('rs_servicio_id')->references('id')->on('rs_servicios');
        });

        Schema::table('ce_proconminutaupcedades', function (Blueprint $table) {
            $table->foreign('ce_proconminutageo_id')->references('id')->on('ce_proconminutageos');
            $table->foreign('rs_ranupc_id')->references('id')->on('rs_ranupcs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
