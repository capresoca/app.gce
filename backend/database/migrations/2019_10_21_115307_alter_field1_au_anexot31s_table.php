<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField1AuAnexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s`
                CHANGE COLUMN `dRel1` `dRel1` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Diagnostico Relacionado 1' ,
                CHANGE COLUMN `dRel2` `dRel2` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Diagnostico Relacionado 2'");
        DB::statement("ALTER TABLE `au_anexot31s` 
                            DROP FOREIGN KEY `au_anexot31s_cont_foreign`,
                            DROP FOREIGN KEY `au_anexot31s_paut_foreign`");
        DB::statement("ALTER TABLE `au_anexot31s` 
                            CHANGE COLUMN `cAut` `cAut` TINYINT(4) NULL DEFAULT NULL COMMENT 'cantidad autorizada' ,
                            CHANGE COLUMN `fS` `fS` DATETIME NULL DEFAULT NULL COMMENT 'fecha de autorizacion' ,
                            CHANGE COLUMN `pAut` `pAut` INT(11) NULL DEFAULT NULL COMMENT 'prestador autorizado' ,
                            CHANGE COLUMN `fCad` `fCad` DATE NULL DEFAULT NULL COMMENT 'fecha vencimiento de la uatorizacion' ,
                            CHANGE COLUMN `valor` `valor` DOUBLE(12,2) NOT NULL DEFAULT 0 COMMENT 'valor autorizacion' ,
                            CHANGE COLUMN `copago` `copago` DOUBLE(8,2) NOT NULL DEFAULT 0 COMMENT 'copago' ,
                            CHANGE COLUMN `cont` `cont` INT(11) NULL DEFAULT NULL COMMENT 'id contrato ' ,
                            CHANGE COLUMN `aCond` `aCond` CHAR(191) NULL DEFAULT NULL COMMENT 'si la autorizacion es condicionada o no tabla ref. refSiNo' ,
                            CHANGE COLUMN `reco` `reco` CHAR(191) NULL DEFAULT NULL COMMENT 'Opcion de recobro tabal ref. refRecobro' ,
                            CHANGE COLUMN `ind` `ind` INT(2) NULL DEFAULT NULL COMMENT 'indicador si esta anulada o no. 1= activa 2=anulada' ,
                            CHANGE COLUMN `imp` `imp` TINYINT NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE `au_anexot31s` 
                            ADD CONSTRAINT `au_anexot31s_cont_foreign`
                              FOREIGN KEY (`cont`)
                              REFERENCES `rs_planescoberturas` (`id`),
                            ADD CONSTRAINT `au_anexot31s_paut_foreign`
                              FOREIGN KEY (`pAut`)
                              REFERENCES `rs_entidades` (`id`)");
    }
}
