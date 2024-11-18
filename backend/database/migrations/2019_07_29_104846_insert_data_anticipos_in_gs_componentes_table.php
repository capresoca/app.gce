<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataAnticiposInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Anticipos',
        ],[
            'nombre' => 'Anticipos',
            'gs_modulo_id' => 6,
            'documento' => 0,
            'ruta_router' => 'anticipos',
            'icono' => 'fas fa-file-invoice-dollar',
            'favorito' => null,
            'descripcion' => 'Listado de anticipos',
            'ruta_componente' => '@/components/administrativo/pagos/Anticipos',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar,imprimir',
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
