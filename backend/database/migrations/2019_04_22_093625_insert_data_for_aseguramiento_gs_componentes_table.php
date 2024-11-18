<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataForAseguramientoGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'auditoriaMS'
        ],[
            'nombre' => 'Auditoría MS',
            'gs_modulo_id' => 3,
            'documento' => 0,
            'ruta_router' => 'auditoriaMS',
            'icono' => 'assignment_turned_in',
            'favorito' => 0,
            'descripcion' => 'Gestión de archivos MS',
            'ruta_componente' => '@/components/misional/aseguramiento/auditorias/AuditoriaMS',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar,confirmar,imprimir',
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
        \App\GsComponente::where('ruta_router', 'auditoriaMS')->delete();
    }
}
