<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldEstudiosSupervisorInEstudiospreviosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_proconestprevios` DROP FOREIGN KEY `ce_proconestprevios_supervisor_id_foreign`");
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        DB::statement("ALTER TABLE `ce_proconestprevios` 
                            CHANGE COLUMN `supervisor_id` `supervisor_id` INT(10) UNSIGNED NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `ce_proconestprevios` 
                            ADD CONSTRAINT `ce_proconestprevios_supervisor_id_foreign`
                              FOREIGN KEY (`supervisor_id`)
                              REFERENCES `ce_supervisores` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
        DB::statement("SET FOREIGN_KEY_CHECKS = 1");

    }
}
