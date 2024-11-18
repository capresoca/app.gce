<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdataInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Asignación de Radicados',
        ],[
            'nombre' => 'Radicados Asignados',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'radicadosAsignados',
            'icono' => 'supervised_user_circle',
            'favorito' => null,
            'descripcion' => 'Asignación',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/radicadosAsignados/RadicadoAsignado',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'detalleFacturas',
            'titulo_registro' => 'Revisión de Cuenta',
            'permisos' => 'ver,agregar,anular,imprimir',
            'view_menu' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("delete from gs_componentes where nombre = 'Asignación de Radicados'");
    }
}
