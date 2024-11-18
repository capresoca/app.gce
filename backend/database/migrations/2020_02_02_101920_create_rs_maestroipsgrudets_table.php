<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsMaestroipsgrudetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_maestroipsgrudets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idd')->index()->comment('Id del grupo');
            $table->tinyInteger('servicio_id')->comment('Id del servicio primario');
            $table->integer('idIps')->index()->comment('Id de la IPS');
            $table->integer('gs_usuario_id')->comment('Id del usuario');
            $table->string('codigo',20)->nullable()->comment('Código del grupo')->default(null);
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
        Schema::dropIfExists('rs_maestroipsgrudets');
    }
}
