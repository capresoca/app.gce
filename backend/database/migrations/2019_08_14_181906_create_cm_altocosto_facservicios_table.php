<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmAltocostoFacserviciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_altocosto_facservicios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cm_factura_id')->nullable();
            $table->integer('cm_facservicio_id')->nullable();
            $table->unsignedInteger('as_tipaltocosto_id')->nullable();
            $table->foreign('cm_factura_id')->references('id')->on('cm_facturas')->onDelete('restrict');
            $table->foreign('cm_facservicio_id')->references('id')->on('cm_facservicios')->onDelete('restrict');
            $table->foreign('as_tipaltocosto_id')->references('id')->on('as_tipaltocostos')->onDelete('restrict');
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
        Schema::dropIfExists('cm_altocosto_facservicios');
    }
}