<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\GsComponente;

class UpdateDataCierreInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'nombre' => 'Repetir Comprobante de Diario'
        ],[
            'nombre' => 'Interfaz Contable',
            'gs_modulo_id' => 1,
            'documento' => 0,
            'ruta_router' => '',
            'icono' => 'file_copy',
            'favorito' => null,
            'descripcion' => 'Contabilidad',
            'ruta_componente' => '',
            'created_at' => null,
            'updated_at' => null,
            'ruta_router_registro' => '',
            'titulo_registro' => '',
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
        DB::statement("delete from gs_componentes where nombre = 'Repetir Comprobante de Diario'");
    }
}
