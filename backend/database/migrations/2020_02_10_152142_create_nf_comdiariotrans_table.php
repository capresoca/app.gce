<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfComdiariotransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nf_comdiariotrans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nf_comdiario_id')->comment('Fk -Id del comprobante de diario');
            $table->integer('cm_radicado_id')->nullable()->default(null)->comment('Fk - ID de la cuenta médica');
            $table->integer('pg_cxp_id')->nullable()->default(null)->comment('Fk - ID de la cuenta por pagar.');
            $table->unsignedInteger('pr_obligacion_id')->nullable()->default(null)->comment('Fk - ID de la obligación presupuestal.');
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
        Schema::dropIfExists('nf_comdiariotrans');
    }
}
