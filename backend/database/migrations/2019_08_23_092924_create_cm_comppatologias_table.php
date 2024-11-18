<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmComppatologiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_comppatologias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion',300)->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('cm_comppatologias')->insert([
            ['nombre' => 'DM COMPLICADA'],
            ['nombre' => 'DM DESCOMPENSADA'],
            ['nombre' => 'ECV'],
            ['nombre' => 'EPOC DESCOMPENSADO'],
            ['nombre' => 'FALLA RENAL AGUDA'],
            ['nombre' => 'HEMORRAGIA POSTPARTO'],
            ['nombre' => 'HIPERTENSIÓN ARTERIAL'],
            ['nombre' => 'HTA ASOCIADA AL EMBARAZO'],
            ['nombre' => 'HTA NO CONTROLADA'],
            ['nombre' => 'IAM'],
            ['nombre' => 'INFECCIÓN NOSOCOMIAL'],
            ['nombre' => 'INFECCIÓN SITIO OPERATORIO'],
            ['nombre' => 'REACCIÓN ADVERSA A MEDICAMENTO'],
            ['nombre' => 'SEPSIS PUERPERAL'],
            ['nombre' => 'SIFILIS MATERNA/CONGENITA'],
            ['nombre' => 'TRASMISIÓN VERTICAL VIH'],
            ['nombre' => 'URGENCIA O EMERGENCIA HIPERTENSIVA']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_comppatologias');
    }
}
