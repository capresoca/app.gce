<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpDispositivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_dispositivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->tinyInteger('ConOrden')->nullable();
            $table->tinyInteger('TipoPrest')->nullable();
            $table->tinyInteger('CausaS1')->nullable();
            $table->string('CodDisp',3)->nullable();
            $table->integer('CanForm')->nullable();
            $table->integer('CadaFreUso')->nullable();
            $table->tinyInteger('CodFreUso')->nullable();
            $table->integer('Cant')->nullable();
            $table->tinyInteger('CodPerDurTrat')->nullable();
            $table->integer('CantTotal')->nullable();
            $table->text('JustNoPBS')->nullable();
            $table->string('IndRec',160)->nullable();
            $table->tinyInteger('EstJM')->nullable();
            $table->timestamps();

            $table->foreign('mp_prescripcion_id')->references('id')->on('mp_prescripciones')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_dispositivos');
    }
}







