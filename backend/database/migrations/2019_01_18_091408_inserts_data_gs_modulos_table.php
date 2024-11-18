<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertsDataGsModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsModulo::updateOrCreate([
            'id' => 20,
        ],[
            'id' => 20,
            'nombre' => 'RADICACIÓN DE CUENTAS',
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
        // DB::statement("delete from gs_modulos value (20, 'RADICACIÓN DE CUENTAS', 'indigo darken-3', NULL, NULL)");
    }
}
