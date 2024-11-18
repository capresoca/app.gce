<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateValueAutorizacionesGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `gs_componentes` SET `descripcion` = 'Procesos de autorización', `ruta_router_registro` = 'registroSolicitudAutorizacion', `titulo_registro` = 'Registro de solicitud de autorización' WHERE (`nombre` = 'Autorizaciones')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
