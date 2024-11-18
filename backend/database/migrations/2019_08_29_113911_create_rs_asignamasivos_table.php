<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsAsignamasivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_asignamasivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gs_usuario_id');
            $table->string('observacion',300)->nullable();
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict')->onUpdate('no action');
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
        Schema::dropIfExists('rs_asignamasivos');
    }
}
