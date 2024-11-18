<?php

use App\GsComponente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataMedicosInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        GsComponente::updateOrCreate([
            'nombre' => 'Médicos',
        ],[
            'nombre' => 'Médicos',
            'gs_modulo_id' => 9,
            'documento' => 0,
            'ruta_router' => 'medicos',
            'icono' => 'fas fa-user-md',
            'favorito' => null,
            'descripcion' => 'Registro y gestión',
            'ruta_componente' => '',
            'ruta_router_registro' => null,
            'titulo_registro' => null,
            'permisos' => 'ver,agregar,anular',
            'view_menu' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("delete from gs_componentes where `gs_componentes`.`nombre` = 'Médicos'");
    }
}
