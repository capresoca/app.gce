<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mp_procedimientos');
        Schema::create('mp_procedimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->integer('rs_cups_id');
            $table->tinyInteger('ConOrden')->nullable();
            $table->tinyInteger('TipoPrest')->nullable();
            $table->tinyInteger('CausaS11')->nullable();
            $table->tinyInteger('CausaS12')->nullable();
            $table->tinyInteger('CausaS2')->nullable();
            $table->tinyInteger('CausaS3')->nullable();
            $table->tinyInteger('CausaS4')->nullable();
            $table->string('ProPBSUtilizado',6)->nullable();
            $table->tinyInteger('CausaS5')->nullable();
            $table->string('ProPBSDescartado',6)->nullable();
            $table->tinyInteger('RznCausaS51')->nullable();
            $table->string('DescRzn51',160)->nullable();
            $table->tinyInteger('RznCausaS52')->nullable();
            $table->string('DescRzn52')->nullable();
            $table->tinyInteger('CausaS6')->nullable();
            $table->tinyInteger('CausaS7')->nullable();
            $table->string('CodCUPS',6)->nullable();
            $table->integer('CanForm')->nullable();
            $table->integer('CadaFreUso')->nullable();
            $table->tinyInteger('CodFreUso')->nullable();
            $table->integer('Cant')->nullable();
            $table->integer('CantTotal')->nullable();
            $table->tinyInteger('CodPerDurTrat')->nullable();
            $table->foreign('mp_prescripcion_id')->references('id')->on('mp_prescripciones')->onDelete('restrict');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `mp_procedimientos`
	                        CHANGE COLUMN `rs_cups_id` `rs_cups_id` INT(11);");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_procedimientos');
    }
}
