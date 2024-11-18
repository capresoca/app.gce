<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldcieInCmConcie10sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_concie10s` 
                            DROP FOREIGN KEY `cm_concie10s_cm_manglosa_id_foreign`");
        DB::statement("ALTER TABLE `cm_concie10s` 
                            CHANGE COLUMN `fecha_desde` `fecha_desde` DATETIME NULL DEFAULT NULL ,
                            CHANGE COLUMN `cm_manglosa_id` `cm_manglosa_id` INT(11) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `cm_concie10s` 
                            ADD CONSTRAINT `cm_concie10s_cm_manglosa_id_foreign`
                              FOREIGN KEY (`cm_manglosa_id`)
                              REFERENCES `cm_manglosas` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
