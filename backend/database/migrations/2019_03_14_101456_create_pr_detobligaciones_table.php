<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetobligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_detobligaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_rp')->nullable();
            $table->double('saldo_por_obligar')->nullable()->default(0);
            $table->double('valor')->default(0);
            $table->double('valor_ejecutado')->default(0);
            $table->double('valor_debito')->default(0);
            $table->double('valor_credito')->default(0);
            $table->double('valor_giro')->default(0);
            $table->unsignedInteger('pr_obligacion_id');
            $table->foreign('pr_obligacion_id')->references('id')->on('pr_obligaciones')->onDelete('restrict');
            $table->integer('pr_tipo_gasto_id')->nullable();
            $table->foreign('pr_tipo_gasto_id')->references('id')->on('pr_tipos_gastos')->onDelete('restrict');
            $table->unsignedInteger('pr_rubro_id')->nullable();
            $table->foreign('pr_rubro_id')->references('id')->on('pr_conceptos')->onDelete('restrict');
            $table->unsignedInteger('pr_detrp_id')->nullable();
            $table->foreign('pr_detrp_id')->references('id')->on('pr_detrps')->onDelete('restrict');
            $table->integer('pr_detcdp_id')->nullable();
            $table->foreign('pr_detcdp_id')->references('id')->on('pr_detcdps')->onDelete('restrict');
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
        Schema::dropIfExists('pr_detobligaciones');
    }
}
