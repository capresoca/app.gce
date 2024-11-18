<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField019InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 33,
        ],[
            'id' => 33,
            'nombre' => 'Ciudades Bancarías',
            'gs_modulo_id' => 7,
            'documento' => 0,
            'ruta_router' => 'ciudadesBancarias',
            'icono' => 'folder_shared',
            'favorito' => null,
            'descripcion' => 'Ubicación bancaría',
            'ruta_componente' => '@/components/administrativo/tesoreria/CiudadesBancarias/CiudadesBancarias',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => 'Registro de Ciudad Bancaría',
            'permisos' => 'ver,agregar,confirmar',
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
        DB::statement("delete from gs_componentes value (33, 'Ciudades Bancarías', 7, 0, 'ciudadesBancarias', 'folder_shared', NULL, 'Ubicación bancaría', '@/components/administrativo/tesoreria/CiudadesBancarias/CiudadesBancarias', NULL, NULL, '', 'Registro de Ciudad Bancaría', 'ver,agregar', 1)");
    }
}
