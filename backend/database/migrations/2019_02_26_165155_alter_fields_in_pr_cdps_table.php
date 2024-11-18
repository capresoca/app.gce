<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInPrCdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_cdps` 
                        DROP FOREIGN KEY `fk_uniapoyo_cdp`");
        DB::statement("ALTER TABLE `pr_cdps` 
                        DROP COLUMN `pg_uniapoyo_id`,
                        ADD COLUMN `vigente` VARCHAR(15) NULL AFTER `fecha_vence`,
                        CHANGE COLUMN `consecutivo` `consecutivo` INT(15) NULL DEFAULT NULL ,
                        DROP INDEX `fk_uniapoyo_cdp`");
    }

}
