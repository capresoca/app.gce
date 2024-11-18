<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataThEncargoInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Encargos',
        ],[
            'nombre' => 'Encargos',
            'gs_modulo_id' => 12,
            'documento' => 0,
            'ruta_router' => 'encargos',
            'icono' => 'file_copy',
            'favorito' => 0,
            'descripcion' => 'Encargos',
            'ruta_componente' => '@/components/administrativo/TalentoHumano/encargos/Encargos',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
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
        \App\GsComponente::where('nombre','Encargos')->delete();
    }
}
