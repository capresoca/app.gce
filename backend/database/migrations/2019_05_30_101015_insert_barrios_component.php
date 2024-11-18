<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertBarriosComponent extends Migration
{
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'nombre' => 'Barrios',
        ],[
            'gs_modulo_id' => 4,
            'documento' => 0,
            'ruta_router' => 'barrios',
            'icono' => 'settings',
            'favorito' => 0,
            'descripcion' => 'Barrios',
            'ruta_componente' => '@/components/administrativo/generales/Barrios',
            'ruta_router_registro' => '',
            'titulo_registro' => '',
            'permisos' => 'ver,agregar',
            'view_menu' => 1,
        ]);
    }


}
