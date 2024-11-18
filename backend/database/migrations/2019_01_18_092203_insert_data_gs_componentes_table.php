<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 133,
        ],[
            'id' => 133,
            'nombre' => 'Cuentas Médicas',
            'gs_modulo_id' => 19,
            'documento' => 0,
            'ruta_router' => 'cmradicadas',
            'icono' => 'assignment_turned_in',
            'favorito' => null,
            'descripcion' => 'Radicación de cuentas',
            'ruta_componente' => '@/components/misional/radicacionCuentas/CmRadicada',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'registroRadicada',
            'titulo_registro' => 'Registro de Cuenta a Radicar',
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
        DB::statement("delete from gs_componentes value (133, 'Cuentas Médicas', 20, 0, 'cmradicadas', 'assignment_turned_in', NULL, 'Radicación de cuentas', '@/components/misional/radicacionCuentas/CmRadicada', NULL, NULL, 'registroRadicada', 'Registro de Cuenta a Radicar', 'ver,agregar,confirmar', 1)");
    }
}
