<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFields12InAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s` 
                            CHANGE COLUMN `serv` `serv` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Servicio donde esta el paciente' ,
                            CHANGE COLUMN `cama` `cama` VARCHAR(15) NULL DEFAULT NULL COMMENT '# Cama'");
    }
}
