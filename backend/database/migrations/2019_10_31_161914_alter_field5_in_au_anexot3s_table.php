<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField5InAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s` 
                            ADD COLUMN `registrador` VARCHAR(191) NULL COMMENT 'Nombre de quien registra' AFTER `tCel`,
                            CHANGE COLUMN `lesp` `lesp` INT(11) NOT NULL COMMENT 'Especialidad del Médico que autorizo el servicio' AFTER `au_medico_id`,
                            CHANGE COLUMN `docs` `docs` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Número identificación quien registra'");
    }
}
