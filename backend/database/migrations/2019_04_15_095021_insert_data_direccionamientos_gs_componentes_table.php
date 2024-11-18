<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataDireccionamientosGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 153,
        ],[
            'nombre' => 'Direccionamientos',
            'gs_modulo_id' => 21,
            'documento' => 1,
            'ruta_router' => 'direccionamientos',
            'icono' => 'swap_horiz',
            'favorito' => 0,
            'descripcion' => 'Listado de direccionamientos',
            'ruta_componente' => '',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar,imprimir',
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
        \App\GsComponente::find(153)->delete();
    }
}
