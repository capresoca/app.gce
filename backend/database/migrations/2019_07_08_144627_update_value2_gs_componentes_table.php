<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateValue2GsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `gs_componentes` SET `ruta_router` = 'pg_proveedores', `ruta_router_registro` = 'pg_proveedores' WHERE (`nombre` = 'Proveedores')");
    }

}
