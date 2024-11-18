<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInterfazContableGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Interfaz Contable',
        ],[
            'nombre' => 'Interfaz Contable',
            'gs_modulo_id' => 1,
            'documento' => 0,
            'ruta_router' => 'contabilidadInterfazContable',
            'icono' => 'file_copy',
            'favorito' => null,
            'descripcion' => 'Contabilidad',
            'ruta_componente' => '@/components/administrativo/niif/contabilidadInterfazContable/ContabilidadInterfazContable',
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
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Interfaz Contable'");
    }
}
