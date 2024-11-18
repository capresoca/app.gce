<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataModificacionPresupuestalInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 144,
        ],[
            'nombre' => 'Modificación Presupuestal',
            'gs_modulo_id' => 15,
            'documento' => 0,
            'ruta_router' => 'modificacionPresupuestal',
            'icono' => 'flip_to_front',
            'favorito' => 0,
            'descripcion' => 'Modificaciones presupuestales de ingresos y gastos',
            'ruta_componente' => '@/components/administrativo/presupuesto/modificacionPresupuestal/ModificacionPresupuestal',
            'ruta_router_registro' => 'registroModificacionPresupuestal',
            'titulo_registro' => 'Registro Modificación',
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
        \App\GsComponente::find(144)->delete();
    }
}
