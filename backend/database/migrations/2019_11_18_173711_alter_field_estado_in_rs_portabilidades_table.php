<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldEstadoInRsPortabilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `rs_portabilidades` 
        CHANGE COLUMN `estado` `estado` ENUM('Registrado', 'Negado', 'Aceptado', 'Terminada') NOT NULL DEFAULT 'Registrado'");
    }
}
