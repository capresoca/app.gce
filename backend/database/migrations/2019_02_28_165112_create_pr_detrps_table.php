<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrDetrpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_detrps', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha_ven');
            $table->integer('pr_rp_id');
            $table->foreign('pr_rp_id')->references('id')->on('pr_rps')->onDelete('restrict');
            $table->integer('pr_detcdp_id');
            $table->foreign('pr_detcdp_id')->references('id')->on('pr_detcdps')->onDelete('restrict');
            $table->unsignedInteger('pr_rubro_id');
            $table->foreign('pr_rubro_id')->references('id')->on('pr_conceptos')->onDelete('restrict');
            $table->double('valor_ven');
            $table->double('valor_debito')->nullable();
            $table->double('valor_credito')->nullable();
            $table->double('valor_ejecutado')->nullable();
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
        Schema::dropIfExists('pr_detrps');
    }
}
