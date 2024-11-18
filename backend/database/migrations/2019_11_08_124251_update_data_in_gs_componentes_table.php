<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `gs_componentes` SET `nombre` = 'Cuentas Asignadas',
                            `descripcion` = 'Listado de cuentas radicadas asignadas'
                            WHERE `ruta_router` = 'radicadosAsignados'");
    }
}
