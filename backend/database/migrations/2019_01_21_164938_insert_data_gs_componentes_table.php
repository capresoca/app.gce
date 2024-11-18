<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\GsComponente;
use Illuminate\Support\Facades\DB;

class InsertData3GsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'id' => 132,
        ],[
            'id' => 132,
            'nombre' => 'Configuración Reportes',
            'gs_modulo_id' => 19,
            'documento' => 0,
            'ruta_router' => 'configuracionReportes',
            'icono' => 'settings',
            'favorito' => null,
            'descripcion' => 'Configuración de reportes',
            'ruta_componente' => null,
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => 'registroReporte',
            'titulo_registro' => 'Registro Reporte',
            'permisos' => 'ver,agregar,anular',
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
        DB::statement("delete from gs_componentes where id = 132");
    }
}
