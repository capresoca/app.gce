<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpNutricionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_nutricionals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->tinyInteger('ConOrden')->nullable();
            $table->tinyInteger('TipoPrest')->nullable();
            $table->tinyInteger('CausaS1')->nullable();
            $table->tinyInteger('CausaS2')->nullable();
            $table->tinyInteger('CausaS3')->nullable();
            $table->tinyInteger('CausaS4')->nullable();
            $table->string('ProNutUtilizado',160)->nullable();
            $table->tinyInteger('RznCausaS41')->nullable();
            $table->string('DescRzn41',160)->nullable();
            $table->tinyInteger('RznCausaS42')->nullable();
            $table->string('DescRzn42',160)->nullable();
            $table->tinyInteger('CausaS5')->nullable();
            $table->string('ProNutDescartado',160)->nullable();
            $table->tinyInteger('RznCausaS51')->nullable();
            $table->string('DescRzn51',160)->nullable();
            $table->tinyInteger('RznCausaS52')->nullable();
            $table->string('DescRzn52',160)->nullable();
            $table->tinyInteger('RznCausaS53')->nullable();
            $table->string('DescRzn53',160)->nullable();
            $table->tinyInteger('RznCausaS54')->nullable();
            $table->string('DescRzn54',160)->nullable();
            $table->tinyInteger('DXEnfHuer')->nullable();
            $table->tinyInteger('DXVIH')->nullable();
            $table->tinyInteger('DXCaPal')->nullable();
            $table->tinyInteger('DXEnfRCEV')->nullable();
            $table->string('TippProNut',4)->nullable();
            $table->string('DescProdNutr',6)->nullable();
            $table->string('CodForma',2)->nullable();
            $table->tinyInteger('CodViaAdmon')->nullable();
            $table->string('JustNoPBS',500)->nullable();
            $table->float('Dosis')->nullable();
            $table->string('DosisUM',4)->nullable();
            $table->integer('NoFAdmon')->nullable();
            $table->tinyInteger('CodFreAdmon')->nullable();
            $table->tinyInteger('IndEsp')->nullable();
            $table->string('CanTrat',3)->nullable();
            $table->tinyInteger('DurTrat')->nullable();
            $table->float('CantTotalF')->nullable();
            $table->string('UFCantTotal',2)->nullable();
            $table->string('IndRec',160)->nullable();
            $table->string('NoPrescAso',20)->nullable();
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
        Schema::dropIfExists('mp_nutricionals');
    }
}


