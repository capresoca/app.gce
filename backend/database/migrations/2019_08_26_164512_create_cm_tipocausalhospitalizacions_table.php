<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmTipocausalhospitalizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_tipocausalhospitalizacions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',500);
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::table('cm_tipocausalhospitalizacions')->insert([
            ['nombre' => 'AUTORIZACIÓN DE SERVICIOS'],
            ['nombre' => 'COMPLETAR MANEJO ANTIBIÓTICO'],
            ['nombre' => 'EN TRÁMITE DE REMISIÓN'],
            ['nombre' => 'PROGRAMACIÓN DE CIRUGÍA'],
            ['nombre' => 'REALIZACIÓN DE PROCEDIMIENTO'],
            ['nombre' => 'OXÍGENO REQUIRIENTE'],
            ['nombre' => 'INICIO MANEJO ANTIBIÓTICO DE AMPLIO ESPECTRO'],
            ['nombre' => 'ABANDONO SOCIAL'],
            ['nombre' => 'VALORACIÓN POR ESPECIALISTA'],
            ['nombre' => 'COMPLICACIÓN PATOLOGIA']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_tipocausalhospitalizacions');
    }
}
