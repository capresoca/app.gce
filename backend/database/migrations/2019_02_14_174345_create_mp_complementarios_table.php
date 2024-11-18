<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpComplementariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_complementarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_prescripcion_id')->unsigned();

            $table->tinyInteger('ConOrden')->nullable();
            $table->tinyInteger('TipoPrest')->nullable();
            $table->tinyInteger('CausaS1')->nullable();
            $table->tinyInteger('CausaS2')->nullable();
            $table->tinyInteger('CausaS3')->nullable();
            $table->tinyInteger('CausaS4')->nullable();
            $table->string('DescCausaS4',160)->nullable();
            $table->tinyInteger('CausaS5')->nullable();
            $table->tinyInteger('CodSerComp')->nullable();
            $table->string('DescSerComp',160)->nullable();
            $table->integer('CanForm')->nullable();
            $table->integer('CadaFreUso')->nullable();
            $table->tinyInteger('CodFreUso')->nullable();
            $table->integer('Cant')->nullable();
            $table->integer('CantTotal')->nullable();
            $table->tinyInteger('CodPerDurTrat')->nullable();
            $table->string('JustNoPBS',500)->nullable();
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
        Schema::dropIfExists('mp_complementarios');
    }
}

