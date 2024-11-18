<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_medicamento_id')->unsigned()->nullable();
            $table->integer('mp_nutricional_id')->unsigned()->nullable();
            $table->integer('mp_procedimiento_id')->unsigned()->nullable();
            $table->integer('mp_complementario_id')->unsigned()->nullable();
            $table->integer('mp_dispositivo_id')->unsigned()->nullable();
            $table->date('fecha_aprobada');
            $table->dateTime('fecha_entrega')->nullable();
            $table->float('cantidad_aprobada');
            $table->float('cantidad_entregada')->nullable();
            $table->enum('estado',['Aprobada','Enviada','Entregada','No Entregada','Entrega Parcial']);
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
        Schema::dropIfExists('mp_entregas');
    }
}

