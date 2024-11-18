<?php

use Illuminate\Database\Migrations\Migration;

class InsertDataPrescripcionesGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 140,
        ],[
            'nombre' => 'Prescripciones',
            'gs_modulo_id' => 21,
            'documento' => 1,
            'ruta_router' => 'prescripciones',
            'icono' => 'file_copy',
            'favorito' => 0,
            'descripcion' => 'Listado de prescripciones',
            'ruta_componente' => '',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,imprimir',
            'view_menu' => 1,
        ]);

        \App\GsComponente::updateOrCreate([
            'id' => 141,
        ],[
            'nombre' => 'Tutelas MIPRES',
            'gs_modulo_id' => 21,
            'documento' => 1,
            'ruta_router' => 'tutelasMipres',
            'icono' => 'file_copy',
            'favorito' => 0,
            'descripcion' => 'Listado de tutelas del MIPRES',
            'ruta_componente' => '',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,imprimir',
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
        \App\GsComponente::find(140)->delete();
        \App\GsComponente::find(141)->delete();
    }
}
