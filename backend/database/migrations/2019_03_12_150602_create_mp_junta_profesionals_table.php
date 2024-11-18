<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpJuntaProfesionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_junta_profesionals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('NoPrescripcion',20);
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->date('FPrescripcion');
            $table->string('TipoTecnologia',1);
            $table->string('Consecutivo');
            $table->string('EstJM');
            $table->string('CodEntProc')->nullable();
            $table->string('Observaciones')->nullable();
            $table->string('JustificacionTecnica')->nullable();
            $table->string('Modalidad')->nullable();
            $table->string('NoActa')->nullable();
            $table->string('FechaActa')->nullable();
            $table->string('FProceso')->nullable();
            $table->string('TipoIDPaciente');
            $table->string('NroIDPaciente');
            $table->string('CodEntJM')->nullable();

            $table->integer('mp_medicamento_id')->unsigned()->nullable();
            $table->foreign('mp_medicamento_id')->references('id')->on('mp_medicamentos');

            $table->integer('mp_complementario_id')->unsigned()->nullable();
            $table->foreign('mp_complementario_id')->references('id')->on('mp_complementarios');

            $table->integer('mp_nutricional_id')->unsigned()->nullable();
            $table->foreign('mp_nutricional_id')->references('id')->on('mp_nutricionals');

            $table->foreign('mp_prescripcion_id')->references('id')->on('mp_prescripciones');

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
        Schema::dropIfExists('mp_junta_profesionals');
    }
}

