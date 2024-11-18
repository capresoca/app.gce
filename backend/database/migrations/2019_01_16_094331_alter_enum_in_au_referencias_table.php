<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnumInAuReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `au_referencias`
                              CHANGE COLUMN `estado` `estado` ENUM("Iniciada","Presentada","Aceptada","Seleccionada","Traslación Solicitada","Traslación Aceptada","Salida","Cancelada","Fallecida","Suspendida","Finalizada")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `au_referencias`
                              CHANGE COLUMN `estado` `estado` ENUM("Iniciada","Aceptada","Cancelada","Fallecido","Salida","Suspendida")');

    }
}
