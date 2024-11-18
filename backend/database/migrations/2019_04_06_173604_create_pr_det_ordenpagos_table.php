<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetOrdenpagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_det_ordenpagos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ven');
            $table->unsignedInteger('pr_orden_de_pago_id');
            $table->foreign('pr_orden_de_pago_id')->references('id')->on('pr_ordenes_de_pagos')->onDelete('restrict');
            $table->unsignedInteger('pr_detobligacion_id');
            $table->foreign('pr_detobligacion_id')->references('id')->on('pr_detobligaciones')->onDelete('restrict');
            $table->unsignedInteger('pr_rubro_id');
            $table->foreign('pr_rubro_id')->references('id')->on('pr_conceptos')->onDelete('restrict');
            $table->integer('pr_tipo_gasto_id');
            $table->foreign('pr_tipo_gasto_id')->references('id')->on('pr_tipos_gastos')->onDelete('restrict');
            $table->integer('pr_rp_id');
            $table->foreign('pr_rp_id')->references('id')->on('pr_rps')->onDelete('restrict');
            $table->integer('pr_cdp_id');
            $table->foreign('pr_cdp_id')->references('id')->on('pr_cdps')->onDelete('restrict');
            $table->double('valor')->default(0);
            $table->double('valor_ejecutado')->default(0);
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
        Schema::dropIfExists('pr_det_ordenpagos');
    }
}
