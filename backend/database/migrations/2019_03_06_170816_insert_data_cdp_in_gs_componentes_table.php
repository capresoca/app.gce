<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataCdpInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\GsComponente::updateOrCreate([
            'id' => 148,
        ],[
            'id' => 148,
            'nombre' => 'CDP',
            'gs_modulo_id' => 15,
            'documento' => 0,
            'ruta_router' => 'CDP',
            'icono' => 'assessment',
            'favorito' => null,
            'descripcion' => 'Certificado Disponibilidad Presupuestal',
            'ruta_componente' => '@/components/administrativo/presupuesto/cdp/CDP',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'registroCDP',
            'titulo_registro' => 'Registro CDP',
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
        DB::statement("delete from gs_componentes value (148, 'CDP', 15, 0, 'CDP', 'assessment', NULL, 'Certificado Disponibilidad Presupuestal', '@/components/administrativo/presupuesto/cdp/CDP', NULL, NULL, 'registroCDP', 'Registro CDP', 'ver,agregar', 1)");
    }
}
