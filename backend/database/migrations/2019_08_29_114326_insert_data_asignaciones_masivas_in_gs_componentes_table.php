<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataAsignacionesMasivasInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Asignación masiva',
        ],[
            'nombre' => 'Asignación masiva',
            'gs_modulo_id' => 5,
            'documento' => 0,
            'ruta_router' => 'asignacionmasiva',
            'icono' => 'fab fa-buromobelexperte',
            'favorito' => null,
            'descripcion' => 'Procesos de asignación masiva',
            'ruta_componente' => '',
            'ruta_router_registro' => 'registroAsignacionMasiva',
            'titulo_registro' => 'Registro de asignación masiva',
            'permisos' => 'ver,agregar,anular',
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
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Asignación masiva'");
    }
}
