<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataAsFacturasRadicadasInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Facturas radicadas',
        ],[
            'nombre' => 'Facturas radicadas',
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'facturasradicadas',
            'icono' => 'fas fa-clipboard-list',
            'favorito' => null,
            'descripcion' => 'Listado de facturas radicadas',
            'ruta_componente' => '',
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
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Facturas radicadas'");
    }
}
