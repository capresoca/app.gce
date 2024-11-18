<?php

use App\GsComponente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertNewDataInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `gs_componentes` SET `view_menu` = '0' WHERE (`ruta_router` = 'gruposinventarios') AND (`nombre` = 'Grupos de Inventarios');");
        DB::statement("UPDATE `gs_componentes` SET `view_menu` = '0' WHERE (`ruta_router` = 'subgruposinventarios') AND (`nombre` = 'Subgrupos de Inventarios');");
        DB::statement("UPDATE `gs_componentes` SET `view_menu` = '0' WHERE (`ruta_router` = 'almaceninventarios') AND (`nombre` = 'Almacen de Inventarios');");
        DB::statement("UPDATE `gs_componentes` SET `view_menu` = '0' WHERE (`ruta_router` = 'unidadmedidainventarios') AND (`nombre` = 'UMI');");
        GsComponente::updateOrCreate([
            'nombre' => 'Configuración inventarios',
        ],[
            'nombre' => 'Configuración Inventarios',
            'gs_modulo_id' => 11,
            'ruta_router' => 'configuracionInventarios',
            'documento' => 0,
            'icono' => 'all_inbox',
            'favorito' => 0,
            'descripcion' => 'Parametrización de inventarios',
            'ruta_componente' => '@/components/administrativo/ConfiguracionInventarios/ConfiguracionInventarios',
            'ruta_router_registro' => '',
            'titulo_registro' => 'Registro Traslado',
            'permisos' => 'ver,agregar',
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
        \App\GsComponente::whereNombre('Configuración inventarios')->where('ruta_router','configuracionInventarios')->delete();
    }
}
