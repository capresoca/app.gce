<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InserDataRespuestasInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Respuestas',
        ],[
            'nombre' => 'Respuestas',
            'gs_modulo_id' => 3,
            'documento' => 0,
            'ruta_router' => 'respuestas',
            'icono' => 'fas fa-file-signature',
            'favorito' => null,
            'descripcion' => 'Archivos BDUA',
            'ruta_componente' => '@/components/misional/aseguramiento/bdua/respuestas/Respuestas',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar,anular',
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
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Respuestas'");
    }
}
