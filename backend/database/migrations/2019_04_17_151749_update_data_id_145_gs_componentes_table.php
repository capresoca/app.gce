<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataId145GsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'trasladoPresupuestal',
        ],[
            'nombre' => 'Traslado Presupuestal',
            'gs_modulo_id' => 15,
            'documento' => 0,
            'icono' => 'flip_to_back',
            'favorito' => 0,
            'descripcion' => 'Traslados presupuestales en ingresos y gastos',
            'ruta_componente' => '@/components/administrativo/presupuesto/trasladoPresupuestal/TrasladoPresupuestal',
            'ruta_router_registro' => 'registroTrasladoPresupuestal',
            'titulo_registro' => 'Registro Traslado',
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
        \App\GsComponente::find(145)->delete();
    }
}
