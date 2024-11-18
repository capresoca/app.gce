<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsAfiservicambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_afiservicambios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('as_afiliado_id')->comment('FK - Id del afiliado.');
            $table->unsignedInteger('id_serv_anterior')->comment('Fk - Id del servicio habilitado anterior');
            $table->unsignedInteger('id_portabilidad')->comment('Fk - Id de la portabilidad que tuvo cambio')->nullable()->default(null);
            $table->integer('gs_usuario_id');
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
        Schema::dropIfExists('rs_afiservicambios');
    }
}
