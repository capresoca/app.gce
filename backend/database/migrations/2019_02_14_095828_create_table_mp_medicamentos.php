<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpMedicamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_medicamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->tinyInteger('ConOrden')->nullable()->unsigned();
            $table->tinyInteger('TipoMed')->nullable()->unsigned();
            $table->tinyInteger('TipoPrest')->nullable()->unsigned();
            $table->tinyInteger('CausaS1')->nullable()->unsigned();
            $table->tinyInteger('CausaS2')->nullable()->unsigned();
            $table->tinyInteger('CausaS3')->nullable()->unsigned();
            $table->string('MedPBSUtilizado',300)->nullable();
            $table->tinyInteger('RznCausaS31')->nullable()->unsigned();
            $table->string('DescRzn31',160)->nullable();
            $table->tinyInteger('RznCausaS32')->nullable()->unsigned();
            $table->string('DescRzn32',160)->nullable();
            $table->tinyInteger('CausaS4')->nullable()->unsigned();
            $table->string('MedPBSDescartado',300)->nullable();
            $table->tinyInteger('RznCausaS41')->nullable()->unsigned();
            $table->string('DescRzn41',160)->nullable();
            $table->tinyInteger('RznCausaS42')->nullable()->unsigned();
            $table->string('DescRzn42',160)->nullable();
            $table->tinyInteger('RznCausaS43')->nullable()->unsigned();
            $table->string('DescRzn43',160)->nullable();
            $table->tinyInteger('RznCausaS44')->nullable()->unsigned();
            $table->string('DescRzn44',160)->nullable();
            $table->tinyInteger('CausaS5')->nullable()->unsigned();
            $table->tinyInteger('RznCausaS5')->nullable()->unsigned();
            $table->tinyInteger('CausaS6')->nullable()->unsigned();
            $table->string('DescMedPrinAct',1000)->nullable();
            $table->string('CodFF',8)->nullable();
            $table->string('CodVA',3)->nullable();
            $table->string('JustNoPBS',500)->nullable();
            $table->float('Dosis')->nullable();
            $table->string('DosisUM',4)->nullable();
            $table->string('NoFAdmon',3)->nullable();
            $table->tinyInteger('CodFreAdmon')->nullable()->unsigned();
            $table->tinyInteger('IndEsp')->nullable()->unsigned();
            $table->integer('CanTrat')->nullable()->unsigned();
            $table->tinyInteger('DurTrat')->nullable()->unsigned();
            $table->float('CantTotalF')->nullable();
            $table->string('UFCantTotal',2)->nullable();
            $table->string('IndRec',160)->nullable();
            $table->tinyInteger('EstJM')->nullable()->unsigned();
            $table->foreign('mp_prescripcion_id')->references('id')->on('mp_prescripciones')->onDelete('restrict');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('mp_medicamentos');
    }
}

