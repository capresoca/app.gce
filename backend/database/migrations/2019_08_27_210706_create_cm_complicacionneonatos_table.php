<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmComplicacionneonatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_complicacionneonatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('cm_complicacionneonatos')->insert([
            ['nombre' => 'AMBIGÜEDAD SEXUAL'],
            ['nombre' => 'ASPIRACIÓN LÍQUIDO AMNIÓTICO'],
            ['nombre' => 'DISTOCIA'],
            ['nombre' => 'ENF MEMBRANAS HIALINAS'],
            ['nombre' => 'EXPULSIVO PROLONGADO'],
            ['nombre' => 'HEMORRAGIA'],
            ['nombre' => 'HIPERTENSIÓN ARTERIAL'],
            ['nombre' => 'HIPOGLICEMIA NEONATAL'],
            ['nombre' => 'HIPOTIROIDISMO NEONATAL'],
            ['nombre' => 'HIPOXIA NEONATAL'],
            ['nombre' => 'INFECCIÓN SITIO OPERATORIO'],
            ['nombre' => 'MALFORMACIÓN CONGÉNITA'],
            ['nombre' => 'MASTITIS'],
            ['nombre' => 'SEPSIS PUERPERAL'],
            ['nombre' => 'SUFRIMIENTO FETAL AGUDO'],
            ['nombre' => 'VIH-SIDA MATERNO'],
            ['nombre' => 'SIFILIS CONGENITA'],
            ['nombre' => 'CHAGAS CONGENITO']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_complicacionneonatos');
    }
}
