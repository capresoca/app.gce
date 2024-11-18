<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConcausalhospitalizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_concausalhospitalizacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cm_convisita_id');
            $table->integer('cm_causalhospitalizacion_id')->unsigned();
            $table->string('detalle')->nullable();
            $table->integer('rs_cup_id')->nullable();
            $table->integer('rs_cum_id')->nullable();
            $table->integer('rs_servicio_id')->nullable();

            $table->foreign('cm_convisita_id')->references('id')->on('cm_convisitas')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_causalhospitalizacion_id')->references('id')->on('cm_tipocausalhospitalizacions')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_cup_id')->references('id')->on('rs_cupss')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_cum_id')->references('id')->on('rs_cums')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_servicio_id')->references('id')->on('rs_servicios')->onUpdate('no action')->onDelete('restrict');

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
        Schema::dropIfExists('cm_concausalhospitalizacion');
    }
}
