<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataGrupoIpsGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Grupos IPS',
        ],[
            'nombre' => 'Grupos IPS',
            'gs_modulo_id' => 5,
            'documento' => 0,
            'ruta_router' => 'gruposIPS',
            'icono' => 'fas fa-clinic-medical',
            'favorito' => null,
            'descripcion' => 'Gestión de grupos de IPS',
            'ruta_componente' => 'null',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
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
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Interfaz Contable'");
    }
}
