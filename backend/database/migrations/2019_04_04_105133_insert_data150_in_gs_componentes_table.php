<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertData150InGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 150,
        ],[
            'id' => 150,
            'nombre' => 'Obligaciones',
            'gs_modulo_id' => 15,
            'documento' => 0,
            'ruta_router' => 'obligaciones',
            'icono' => 'all_inbox',
            'favorito' => null,
            'descripcion' => 'Obligaciones',
            'ruta_componente' => '@/components/administrativo/presupuesto/obligaciones/Obligaciones',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'registroObligaciones',
            'titulo_registro' => 'Registro de Obligación',
            'permisos' => 'ver,agregar,confirmar',
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
        DB::statement("delete from gs_componentes value (150, 'Obligaciones', 15, 0, 'obligaciones', 'all_inbox', NULL, 'Obligaciones', '@/components/administrativo/presupuesto/obligaciones/Obligaciones', NULL, NULL, 'registroObligaciones', 'Registro Obligaciones', 'ver,agregar', 1)");
    }
}
