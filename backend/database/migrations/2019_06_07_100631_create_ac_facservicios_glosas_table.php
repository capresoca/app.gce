<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcFacserviciosGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_facservicios_glosas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_glosa');
            $table->unsignedInteger('ac_auditor_id');
            $table->foreign('ac_auditor_id')->references('id')->on('ac_auditores');
            $table->enum('tipo',['Valor','Porcentaje'])->nullable();
            $table->integer('cm_factura_id')->nullable()->default(null);
            $table->foreign('cm_factura_id')->references('id')->on('cm_facturas')->onDelete('restrict');
            $table->integer('cm_facservicio_id')->nullable()->default(null);
            $table->foreign('cm_facservicio_id')->references('id')->on('cm_facservicios')->onDelete('restrict');
            $table->integer('cm_manglosa_id');
            $table->foreign('cm_manglosa_id')->references('id')->on('cm_manglosas');
            $table->longText('observacion')->nullable()->default(null);
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
        Schema::dropIfExists('ac_facservicios_glosas');
    }
}
