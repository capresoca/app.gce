<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmCongestionriesgosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_congestionriesgos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cm_concurrencia_id');
            $table->enum('evento_centinela',['BAJO PESO AL NACER', 'HOSPITALIZACIÓN DE NIÑOS DE 3-5 AÑOS POR EDA', 'HOSPITALIZACIÓN DE 3-5 AÑOS POR IRA', 'MALTRATO INFANTIL', 'MORTINATO', 'MUERTE MATERNA', 'MUERTE PERITONAL', 'MUERTE POR DENGUE', 'MUERTE POR MALARIA', 'QUEMADURA EN MENOR 5 AÑOS', 'TRASMISIÓN VERTICAL (VIH-SÍFILIS)'])->nullable();
            $table->integer('cm_eventosaludpublica_id')->unsigned()->nullable();
            $table->integer('cm_patologiatrazadora_id')->unsigned()->nullable();
            $table->integer('cm_comppatologia_id')->unsigned()->nullable();
            $table->enum('ruta_atencion',['PROMOCIÓN Y MANTENIMIENTO DE LA SALUD', 'SPA Y SALUD MENTAL', 'MATERNO PERITONAL', 'CÁNCER', 'CEREBRO BASCULAR'])->nullable();
            $table->longText('observaciones')->nullable();
            $table->integer('gs_usuario_id');

            $table->foreign('cm_concurrencia_id')->references('id')->on('cm_concurrencias')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_eventosaludpublica_id')->references('id')->on('cm_eventosaludpublicas')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_patologiatrazadora_id')->references('id')->on('cm_patologiatrazadoras')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_comppatologia_id')->references('id')->on('cm_comppatologias')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict')->onUpdate('no action');
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
        Schema::dropIfExists('cm_congestionriesgos');
    }
}
