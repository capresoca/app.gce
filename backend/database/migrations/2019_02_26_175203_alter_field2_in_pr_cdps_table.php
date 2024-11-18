<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField2InPrCdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_cdps` 
                    ADD COLUMN `pr_strgasto_id` INT(10) UNSIGNED NULL AFTER `objecto`,
                    ADD INDEX `pr_cdps_in_pr_strgasto_id_forein_idx` (`pr_strgasto_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_cdps` 
                        ADD CONSTRAINT `pr_cdps_in_pr_strgasto_id_forein`
                          FOREIGN KEY (`pr_strgasto_id`)
                          REFERENCES `pr_strgastos` (`id`)
                          ON DELETE NO ACTION
                          ON UPDATE NO ACTION");
    }

}
