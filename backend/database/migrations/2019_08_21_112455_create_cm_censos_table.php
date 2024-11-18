<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmCensosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_censos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->integer('ips_id');
            $table->integer('archivo_id');
            $table->integer('user_id');
            $table->foreign('archivo_id')->references('id')->on('gn_archivos');
            $table->foreign('user_id')->references('id')->on('gs_usuarios');
            $table->foreign('ips_id')->references('id')->on('rs_entidades');
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
        Schema::dropIfExists('cm_censos');
    }
}
