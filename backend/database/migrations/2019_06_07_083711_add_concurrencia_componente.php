<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConcurrenciaComponente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Concurrencias',
        ],[
            'gs_modulo_id' => 13,
            'documento' => 0,
            'ruta_router' => 'concurrencias',
            'icono' => 'input',
            'favorito' => null,
            'descripcion' => 'Concurrencias',
            'ruta_componente' => '@/components/administrativo/auditoriaCuentas/concurrencias/Concurrencias',
            'ruta_router_registro' => 'registroConcurrencias',
            'titulo_registro' => 'Registro de concurrencias',
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
        //
    }
}
