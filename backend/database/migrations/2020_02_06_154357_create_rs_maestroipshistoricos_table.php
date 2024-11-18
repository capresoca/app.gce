<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsMaestroipshistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_maestroipshistoricos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_grupoips')->index()->comment('Id de la IPS');
            $table->integer('as_afiliado_id')->index()->comment('Id de la afiliado');
            $table->integer('rs_portabilidade_id')->index()->nullable()->comment('Id de la afiliado');
            $table->integer('gs_usuario_id')->index()->nullable()->comment('Id de la afiliado que realizo el registro');
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
        Schema::dropIfExists('rs_maestroipshistoricos');
    }
}
