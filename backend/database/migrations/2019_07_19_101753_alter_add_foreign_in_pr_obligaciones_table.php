<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddForeignInPrObligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_obligaciones` 
                            ADD COLUMN `pg_cxp_id` INT(11) NULL DEFAULT NULL AFTER `gn_tercero_id`,
                            ADD INDEX `pr_obligaciones_pg_cxp_id_foreign_idx` (`pg_cxp_id` ASC)");
        DB::statement("ALTER TABLE `pr_obligaciones` 
                            ADD CONSTRAINT `pr_obligaciones_pg_cxp_id_foreign`
                              FOREIGN KEY (`pg_cxp_id`)
                              REFERENCES `pg_cxps` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_obligaciones', function (Blueprint $table) {
            $table->dropColumn('pg_cxp_id');
        });
    }
}
