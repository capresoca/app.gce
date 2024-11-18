<?php

use App\GsComponente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData4InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'id' => 55,
        ],[
            'id' => 55,
            'nombre' => 'Parámetros',
            'gs_modulo_id' => 7,
            'documento' => 0,
            'ruta_router' => 'parametrosTesoreria',
            'icono' => 'list',
            'favorito' => null,
            'descripcion' => 'Configuración de parámetros',
            'ruta_componente' => '@/components/administrativo/tesoreria/Parametros',
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

    }
}
