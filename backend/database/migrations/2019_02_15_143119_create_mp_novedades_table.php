<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_novedades', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('TipoNov');
            $table->string('NoPrescripcion');
            $table->string('NoPrescripcionF')->nullable();
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->integer('mp_prescripcionFinal_id')->nullable()->unsigned();
            $table->dateTime('FNov');

            $table->foreign('mp_prescripcion_id')->references('id')
                ->on('mp_prescripciones')->onDelete('restrict');
            $table->foreign('mp_prescripcionFinal_id')->references('id')
                ->on('mp_prescripciones')->onDelete('restrict');

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
        Schema::dropIfExists('mp_novedades');
    }
}
