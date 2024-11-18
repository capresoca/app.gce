<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField124InAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s` 
                    CHANGE COLUMN `tInd` `tInd` CHAR(1) NULL DEFAULT NULL COMMENT 'Indicativo del telefono de la persona que reporta' ,
                    CHANGE COLUMN `tNum` `tNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Número de telefono de la persona que reporta' ,
                    CHANGE COLUMN `tExt` `tExt` VARCHAR(6) NULL DEFAULT NULL COMMENT 'Número de Extensión' ,
                    CHANGE COLUMN `tCel` `tCel` VARCHAR(16) NULL DEFAULT NULL COMMENT 'Telefono Celular',
                    CHANGE COLUMN `lesp` `lesp` INT(11) NOT NULL COMMENT 'Especialidad del Médico que autorizo el servicio'");
    }
}
