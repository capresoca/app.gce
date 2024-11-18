<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataOrdenPagoInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 151,
        ],[
            'nombre' => 'Ordenes de Pago',
            'gs_modulo_id' => 15,
            'documento' => 0,
            'ruta_router' => 'ordenesDePago',
            'icono' => 'screen_share',
            'favorito' => 0,
            'descripcion' => 'Ordenes de Pago',
            'ruta_componente' => '@/components/administrativo/presupuesto/ordenesDePago/OrdenesDePago',
            'ruta_router_registro' => 'registroOrdenesDePago',
            'titulo_registro' => 'Registro Ordenes de Pago',
            'permisos' => 'ver,agregar,confirmar,imprimir',
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
        \App\GsComponente::find(151)->delete();
    }
}
