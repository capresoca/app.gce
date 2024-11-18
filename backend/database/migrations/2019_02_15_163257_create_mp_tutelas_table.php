<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpTutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_tutelas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('NoTutela',20);
            $table->date('FTutela');
            $table->time('HTutela')->nullable();
            $table->string('CodEPS',6);
            $table->string('TipoIDEPS',2)->nullable();
            $table->string('NroIDEPS',17)->nullable();
            $table->string('TipoIDProf',2)->nullable();
            $table->string('NumIDProf',17)->nullable();
            $table->string('PNProfS',60)->nullable();
            $table->string('SNProfS',60)->nullable();
            $table->string('PAProfS',60)->nullable();
            $table->string('SAProfS',60)->nullable();
            $table->string('RegProfS',17)->nullable();
            $table->string('TipoIDPaciente',2)->nullable();
            $table->string('NroIDPaciente',17)->nullable();
            $table->string('PNPaciente',60)->nullable();
            $table->string('SNPaciente',60)->nullable();
            $table->string('PAPaciente',60)->nullable();
            $table->string('SAPaciente',60)->nullable();
            $table->string('NroFallo',50)->nullable();
            $table->date('FFalloTutela')->nullable();
            $table->date('F1Instan')->nullable();
            $table->date('F2Instan')->nullable();
            $table->date('FCorte')->nullable();
            $table->date('FDesacato')->nullable();
            $table->tinyInteger('EnfHuerfana')->nullable();
            $table->string('CodEnfHuerfana',4)->nullable();
            $table->tinyInteger('EnfHuerfanaDX')->nullable();
            $table->string('CodDxPpal',4)->nullable();
            $table->string('CodDxRel1',4)->nullable();
            $table->string('CodDxRel2',4)->nullable();
            $table->string('AclFalloTut',160)->nullable();
            $table->string('CodDxMotS1',4)->nullable();
            $table->string('CodDxMotS2',4)->nullable();
            $table->string('CodDxMotS3',4)->nullable();
            $table->text('JustifMed')->nullable();
            $table->tinyInteger('CritDef1CC')->nullable();
            $table->tinyInteger('CritDef2CC')->nullable();
            $table->tinyInteger('CritDef3CC')->nullable();
            $table->tinyInteger('CritDef4CC')->nullable();
            $table->string('TipoIDMadrePaciente',2)->nullable();
            $table->string('NroIDMadrePaciente',17)->nullable();
            $table->tinyInteger('EstTut')->nullable();
            $table->string('paciente')->nullable();
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
        Schema::dropIfExists('mp_tutelas');
    }
}
