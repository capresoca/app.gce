<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnumInAuReftrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `au_reftraslados`
                              CHANGE COLUMN `estado` `estado` ENUM("Solicitado","Aceptado","En Camino","Terminado","Cancelado","Suspendido")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `au_reftraslados`
                              CHANGE COLUMN `estado` `estado` ENUM("Solicitado","En Camino","Terminado","Cancelado")');
    }
}