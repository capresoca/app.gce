<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFields2InPrDetobligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detobligaciones` 
                            DROP FOREIGN KEY `pr_detobligaciones_pr_detcdp_id_foreign`");
        DB::statement("ALTER TABLE `pr_detobligaciones` 
                            CHANGE COLUMN `pr_detcdp_id` `pr_cdp_id` INT(11) NULL DEFAULT NULL ,
                            DROP INDEX `pr_detobligaciones_pr_detcdp_id_foreign` ,
                            ADD INDEX `pr_detobligaciones_pr_detcdp_id_foreign_idx` (`pr_cdp_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_detobligaciones` 
                            ADD CONSTRAINT `pr_detobligaciones_pr_detcdp_id_foreign`
                              FOREIGN KEY (`pr_cdp_id`)
                              REFERENCES `pr_cdps` (`id`)");
    }
}
