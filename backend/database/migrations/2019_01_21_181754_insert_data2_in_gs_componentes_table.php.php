<?php

use App\GsComponente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData2InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'id' => 134,
        ],[
            'id' => 134,
            'nombre' => 'Reportes',
            'gs_modulo_id' => 19,
            'documento' => 0,
            'ruta_router' => 'reportes',
            'icono' => 'settings_input_composite',
            'favorito' => null,
            'descripcion' => 'Generación de reportes',
            'ruta_componente' => null,
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver',
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
        //
    }
}
