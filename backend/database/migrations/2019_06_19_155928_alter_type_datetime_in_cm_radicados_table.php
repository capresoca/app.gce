<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypeDatetimeInCmRadicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_radicados` 
                        CHANGE COLUMN `periodo_inicio` `periodo_inicio` DATE NULL DEFAULT NULL ,
                        CHANGE COLUMN `periodo_fin` `periodo_fin` DATE NULL DEFAULT NULL ,
                        CHANGE COLUMN `fecha_radicado` `fecha_radicado` DATE NULL DEFAULT NULL");
    }
}
