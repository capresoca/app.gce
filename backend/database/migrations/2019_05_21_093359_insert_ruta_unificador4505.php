<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRutaUnificador4505 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'ruta_router' => 'unificador4505'
        ],[
            'nombre' => 'Unificador archivos 4505',
            'gs_modulo_id' => 18,
            'documento' => 0,
            'ruta_router' => 'unificador4505',
            'icono' => 'file_copy',
            'favorito' => 0,
            'descripcion' => 'Unificador de archivos 4505',
            'ruta_componente' => '@/components/misional/validador4505/Unificador4505',
            'ruta_router_registro' => NULL,
            'titulo_registro' => NULL,
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
        //
    }
}
