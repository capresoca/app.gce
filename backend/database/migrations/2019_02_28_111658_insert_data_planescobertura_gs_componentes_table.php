<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataPlanescoberturaGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 147,
        ],[
            'nombre' => 'Planes de Cobertura',
            'gs_modulo_id' => 5,
            'documento' => 0,
            'ruta_router' => 'planesCobertura',
            'icono' => 'wifi_tethering',
            'favorito' => 0,
            'descripcion' => 'Listado de planes de cobertura',
            'ruta_componente' => '',
            'ruta_router_registro' => 'registroPlanCobertura',
            'titulo_registro' => 'Registro Plan Cobertura',
            'permisos' => 'ver,agregar,anular',
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
        App\GsComponente::find(147)->delete();
    }
}
