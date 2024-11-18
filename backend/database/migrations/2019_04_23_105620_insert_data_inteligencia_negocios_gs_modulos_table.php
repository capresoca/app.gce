<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInteligenciaNegociosGsModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsModulo::updateOrCreate([
            'nombre' => 'INTELIGENCIA DE NEGOCIOS',
        ],[
            'nombre' => 'INTELIGENCIA DE NEGOCIOS',
            'color' => 'indigo darken-3'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\GsComponente::where('nombre', '=', 'INTELIGENCIA DE NEGOCIOS')->first()->delete();
    }
}
