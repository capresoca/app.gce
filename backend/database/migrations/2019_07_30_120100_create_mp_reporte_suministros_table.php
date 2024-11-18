<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpReporteSuministrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_reporte_suministros', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('suministro_id')->index();
            $table->tinyInteger('UltEntrega');
            $table->tinyInteger('EntregaCompleta');
            $table->integer('CausaNoEntrega')->index()->nullable();
            $table->string('NoPrescripcionAsociada',20)->index();
            $table->tinyInteger('ConTecAsociada');
            $table->double('CantTotEntregada');
            $table->string('NoLote',25);
            $table->double('ValorEntregado');
            $table->tinyInteger('reportado');
            $table->softDeletes();
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
        Schema::dropIfExists('mp_reporte_suministros');
    }
}
