<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataCompensacionesInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Compensaciones',
        ],[
            'nombre' => 'Compensaciones',
            'gs_modulo_id' => 3,
            'documento' => 0,
            'ruta_router' => 'compensaciones',
            'icono' => 'fas fa-file-upload',
            'favorito' => null,
            'descripcion' => 'Carga de archivos',
            'ruta_componente' => null,
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar',
            'view_menu' => 1
        ]);

        \App\GsComponente::updateOrCreate([
            'nombre' => 'Aportes y Compensaciones',
        ],[
            'nombre' => 'Aportes',
            'gs_modulo_id' => 3,
            'documento' => 0,
            'ruta_router' => 'aportes',
            'icono' => 'fas fa-file-upload',
            'favorito' => null,
            'descripcion' => 'Carga de archivos',
            'ruta_componente' => null,
            'created_at' => null,
            'updated_at' => null,
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
//        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Compensaciones'");
//        \App\GsComponente::updateOrCreate([
//            'nombre' => 'Aportes',
//        ],[
//            'nombre' => 'Aportes y Compensaciones',
//            'gs_modulo_id' => 3,
//            'documento' => 0,
//            'ruta_router' => 'aportesCompensaciones',
//            'icono' => 'ballot',
//            'favorito' => null,
//            'descripcion' => 'Aportes y compensaciones',
//            'ruta_componente' => null,
//            'created_at' => null,
//            'updated_at' => null,
//            'ruta_router_registro' => '',
//            'titulo_registro' => '',
//            'permisos' => 'ver',
//            'view_menu' => 1
//        ]);
    }
}
