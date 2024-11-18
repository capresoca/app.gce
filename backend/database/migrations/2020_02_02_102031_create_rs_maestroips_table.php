<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsMaestroipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_maestroips', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_grupoips')->index()->comment('Id de la Ips');
            $table->integer('as_afiliado_id')->index()->comment('Id del afiliado');
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
        Schema::dropIfExists('rs_maestroips');
    }
}
