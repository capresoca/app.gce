<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInteligenciaNegociosPrincipalGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'I.N. Principal',
        ],[
            'nombre' => 'I.N. Principal',
            'gs_modulo_id' => 22,
            'documento' => 0,
            'ruta_router' => 'inteligencianegocioprincipal',
            'icono' => 'bar_chart',
            'favorito' => 0,
            'descripcion' => 'Panel principal',
            'ruta_componente' => '',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\GsComponente::where('nombre', '=', 'I.N. Principal')->first()->delete();
    }
}
