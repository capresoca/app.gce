<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataAsAltocostosInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Alto costos',
        ],[
            'nombre' => 'Alto costos',
            'gs_modulo_id' => 3,
            'documento' => 0,
            'ruta_router' => 'altocostos',
            'icono' => 'local_atm',
            'favorito' => null,
            'descripcion' => 'Alto costos',
            'ruta_componente' => '@/components/misional/aseguramiento/altocostos/Altocosto',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar',
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
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Anticipos' AND `gs_componentes`.`ruta_componente` = '@/components/administrativo/pagos/Anticipos'");
    }
}
