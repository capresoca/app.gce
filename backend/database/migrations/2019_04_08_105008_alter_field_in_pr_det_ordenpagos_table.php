<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInPrDetOrdenpagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_det_ordenpagos` 
                        ADD COLUMN `pr_obligacion_id` INT(10) UNSIGNED NOT NULL AFTER `pr_cdp_id`,
                        ADD INDEX `pr_det_ordenpagos_pr_obligacion_id_foreign_idx` (`pr_obligacion_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_det_ordenpagos` 
                            ADD CONSTRAINT `pr_det_ordenpagos_pr_obligacion_id_foreign`
                              FOREIGN KEY (`pr_obligacion_id`)
                              REFERENCES `pr_obligaciones` (`id`)
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
        Schema::table('pr_det_ordenpagos', function (Blueprint $table) {
            //
        });
    }
}
