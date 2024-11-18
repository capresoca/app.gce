<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsCmConvisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('cm_convisitas', function (Blueprint $table) {
            $table->string('frec_cardiaca')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('tension_arterial')->nullable();
            $table->string('frec_respiratoria')->nullable();
            $table->string('glasgow')->nullable();
            $table->enum('uci',['VENTILACIÓN', 'ASISTIDO'])->nullable();
            $table->string('habitacion_hospitalaria');
            $table->boolean('estancia_pertinente');
            $table->boolean('requiere_phd')->nullable();
            $table->integer('rs_especialidadtratante_id');
            $table->integer('rs_especialidadinterconsultante_id')->nullable();

            $table->foreign('rs_especialidadtratante_id')->references('id')->on('rs_servicios')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_especialidadinterconsultante_id')->references('id')->on('rs_servicios')->onUpdate('no action')->onDelete('restrict');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_convisitas', function (Blueprint $table) {
            //
        });
    }
}
