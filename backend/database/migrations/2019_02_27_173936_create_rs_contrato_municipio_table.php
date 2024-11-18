<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsContratoMunicipioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_contrato_municipio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rs_contrato_id');
            $table->integer('gn_municipio_id');
            $table->foreign('rs_contrato_id')->references('id')->on('rs_contratos')->onDelete('restrict');
            $table->foreign('gn_municipio_id')->references('id')->on('gn_municipios')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rs_contrato_municipio');
    }
}
