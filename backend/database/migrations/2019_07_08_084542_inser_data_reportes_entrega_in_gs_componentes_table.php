<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InserDataReportesEntregaInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Reportes de Entrega',
        ],[
            'nombre' => 'Reportes de Entrega',
            'gs_modulo_id' => 21,
            'documento' => 0,
            'ruta_router' => 'reportesentrega',
            'icono' => 'fas fa-people-carry',
            'favorito' => 0,
            'descripcion' => 'Reportes de entrega',
            'ruta_componente' => 'reportesEntrega',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar,anular',
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
        \App\GsComponente::where('nombre','Reportes de Entrega')->delete();
    }
}
