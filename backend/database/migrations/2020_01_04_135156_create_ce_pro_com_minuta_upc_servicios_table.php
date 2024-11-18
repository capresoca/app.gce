<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCeProComMinutaUPCServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ce_proconminutaupcservicios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ce_proconminutageo_id');
            $table->integer('rs_servicio_id');
            $table->integer('porcentaje');
            $table->integer('valor');

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
        Schema::dropIfExists('ce_proconminutaupcservicios');
    }
}
