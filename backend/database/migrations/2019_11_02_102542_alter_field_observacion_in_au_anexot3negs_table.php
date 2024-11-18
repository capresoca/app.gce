<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldObservacionInAuAnexot3negsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3negs` 
                            CHANGE COLUMN `observacion` `observacion` LONGTEXT NULL DEFAULT NULL ");
        DB::statement("ALTER TABLE `au_anexot31s` 
                            CHANGE COLUMN `obs` `obs` TEXT NULL DEFAULT NULL COMMENT 'observacion' ;");
        DB::statement("ALTER TABLE `au_anexot3s` 
                            DROP FOREIGN KEY `au_anexot3s_gn_archivo_id_foreign`");
        DB::statement("ALTER TABLE `au_anexot3s` 
                            CHANGE COLUMN `gn_archivo_id` `gn_archivo_id` INT(11) NULL DEFAULT NULL COMMENT 'FK- Id de archivo del resumen de la HC'");
        DB::statement("ALTER TABLE `au_anexot3s` 
                            ADD CONSTRAINT `au_anexot3s_gn_archivo_id_foreign`
                              FOREIGN KEY (`gn_archivo_id`)
                              REFERENCES `gn_archivos` (`id`)");
    }
}
