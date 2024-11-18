<?php

use App\GsComponente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData3InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'id' => 54,
        ],[
            'id' => 54,
            'nombre' => 'Conceptos Tesorería',
            'gs_modulo_id' => 7,
            'documento' => 0,
            'ruta_router' => 'conceptosTesoreria',
            'icono' => 'description',
            'favorito' => null,
            'descripcion' => 'Decribe conceptos técnicos',
            'ruta_componente' => '@/components/administrativo/tesoreria/Conceptos/Conceptos',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'conceptosTesoreriaRegistro',
            'titulo_registro' => 'Registro Concepto de Tesorería',
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

    }
}
