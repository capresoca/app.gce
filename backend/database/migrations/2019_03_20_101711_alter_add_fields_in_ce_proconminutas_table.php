<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldsInCeProconminutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_proconminutas` 
                        ADD COLUMN `pr_cdp_id` INT(11) NULL DEFAULT NULL AFTER `gs_usuario_id`,
                        CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `pr_cdp_id`,
                        CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`,
                        ADD INDEX `ce_proconminutas_pr_cdp_di_foreign_idx` (`pr_cdp_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `ce_proconminutas` 
                            ADD CONSTRAINT `ce_proconminutas_pr_cdp_di_foreign`
                            FOREIGN KEY (`pr_cdp_id`)
                            REFERENCES `pr_cdps` (`id`)
                            ON DELETE NO ACTION
                            ON UPDATE NO ACTION");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            //
        });
    }
}
