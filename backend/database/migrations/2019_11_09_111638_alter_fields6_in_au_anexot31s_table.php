<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFields6InAuAnexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot31s` 
                    DROP FOREIGN KEY `au_anexot31s_espec_foreign`");
        DB::statement("ALTER TABLE `au_anexot31s` 
        CHANGE COLUMN `espec` `espec` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'especialidad codigo especialidad'");
        DB::statement("ALTER TABLE `au_anexot31s` 
                    ADD CONSTRAINT `au_anexot31s_espec_foreign`
                      FOREIGN KEY (`espec`)
                      REFERENCES `au_especialidads` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION");
        DB::statement("ALTER TABLE `au_anexot31s` 
                        CHANGE COLUMN `nivel` `nivel` CHAR(191) NULL DEFAULT NULL COMMENT 'nivel de complejidad' ,
                        CHANGE COLUMN `modSer` `modSer` CHAR(191) NULL DEFAULT NULL COMMENT 'modalidad de servicio tabla ref refModalidadServicio' ,
                        CHANGE COLUMN `tipModSer` `tipModSer` CHAR(191) NULL DEFAULT NULL COMMENT 'tipo modalidad servicio tabla ref refQx' ,
                        CHANGE COLUMN `cober` `cober` CHAR(2) NULL DEFAULT NULL COMMENT 'coberturas tabla referencia refCobertura'");
    }
}
