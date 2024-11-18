<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\GsComponente;


class UpdateData20InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'nombre' => 'Asignación',
        ],[
            'nombre' => 'Asignación de Radicados',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'radicadosAsignados',
            'icono' => 'supervised_user_circle',
            'favorito' => null,
            'descripcion' => 'Asignación',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/radicadosAsignados/RadicadoAsignado',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'registroAsignado',
            'titulo_registro' => 'Revisión de Radicados',
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
        DB::statement("delete from gs_componentes where nombre = 'Asignación'");
    }
}
