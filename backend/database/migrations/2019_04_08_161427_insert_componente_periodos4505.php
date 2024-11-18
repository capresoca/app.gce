<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\GsComponente;

class InsertComponentePeriodos4505 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::create([
            'nombre' => 'Configuracion periodos validador 4505',
            'gs_modulo_id' => '18',
            'documento' => '0',
            'ruta_router' => 'periodos4505', 
            'icono' => 'settings',
            'descripcion' => 'Configuracion de periodos del validador 4505',
            'permisos' => 'ver,agregar',
            'view_menu'=>'1',
            'ruta_router_registro' => 'registroPeriodos4505',
            'titulo_registro' => 'Registro de peridos del validador 4505'
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
