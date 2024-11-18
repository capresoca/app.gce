<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpPrescripciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_prescripciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rs_entidad_id')->nullable();
            $table->string('NoPrescripcion',20);
            $table->dateTime('FPrescripcion');
            $table->time('HPrescripcion');
            $table->string('CodHabIPS',12);
            $table->string('TipoIDIPS',2)->nullable();
            $table->string('NroIDIPS',17)->nullable();
            $table->string('CodDANEMunIPS',5)->nullable();
            $table->string('DirSedeIPS',300)->nullable();
            $table->string('TelSedeIPS',70)->nullable();
            $table->string('TipoIDProf',2)->nullable();
            $table->string('NumIDProf',17)->nullable();
            $table->string('PNProfS',60)->nullable();
            $table->string('SNProfS',60)->nullable();
            $table->string('PAProfS',60)->nullable();
            $table->string('SAProfS',60)->nullable();
            $table->string('RegProfS',17)->nullable();
            $table->string('TipoIDPaciente',2)->nullable();
            $table->string('NroIDPaciente',17)->nullable();
            $table->integer('as_afiliado_id')->nullable();
            $table->string('PNPaciente',60)->nullable();
            $table->string('SNPaciente',60)->nullable();
            $table->string('PAPaciente',60)->nullable();
            $table->string('SAPaciente',60)->nullable();
            $table->integer('CodAmbAte')->nullable();
            $table->integer('EnfHuerfana')->nullable();
            $table->integer('CodEnfHuerfana')->nullable();
            $table->integer('EnfHuerfanaDX')->nullable();
            $table->string('CodDxPpal',4)->nullable();
            $table->string('CodDxRel1',4)->nullable();
            $table->string('CodDxRel2',4)->nullable();
            $table->integer('SopNutricional')->nullable();
            $table->string('CodEPS',6)->nullable();
            $table->string('TipoIDMadrePaciente',2)->nullable();
            $table->string('NroIDMadrePaciente',17)->nullable();
            $table->integer('TipoTransc')->nullable();
            $table->string('TipoIDDonanteVivo',2)->nullable();
            $table->string('NroIDDonanteVivo',17)->nullable();
            $table->integer('EstPres')->nullable();
            $table->enum('estado',['Revisión','Aprobado','Anulado'])->default('Revisión')->nullable();

            $table->foreign('as_afiliado_id')->references('id')->on('as_afiliados')->onDelete('restrict');
            $table->foreign('rs_entidad_id')->references('id')->on('rs_entidades')->onDelete('restrict');

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
        Schema::dropIfExists('mp_prescripciones');
    }
}
