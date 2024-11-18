<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCeProComMinutaUPCEdadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ce_proconminutaupcedades', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ce_proconminutageo_id');
            $table->integer('rs_ranupc_id');
            $table->integer('proporcion');
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
        Schema::dropIfExists('ce_proconminutaupcedades');
    }
}
