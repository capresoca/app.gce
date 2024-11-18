<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField02InPrModPresupuestalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement("ALTER TABLE `pr_mod_presupuestales` 
                            DROP FOREIGN KEY `pr_mod_strgastos_gs_strgasto_id_foreign`");
        DB::statement("ALTER TABLE `pr_mod_presupuestales` 
                            ADD COLUMN `pr_stringreso_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `estado`,
                            CHANGE COLUMN `gs_strgasto_id` `gs_strgasto_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `pr_stringreso_id`,
                            ADD INDEX `pr_mod_presupuestales_pr_stringreso_id_forein_idx` (`pr_stringreso_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_mod_presupuestales` 
                ADD CONSTRAINT `pr_mod_strgastos_gs_strgasto_id_foreign`
                  FOREIGN KEY (`gs_strgasto_id`)
                  REFERENCES `pr_strgastos` (`id`),
                ADD CONSTRAINT `pr_mod_presupuestales_pr_stringreso_id_forein`
                  FOREIGN KEY (`pr_stringreso_id`)
                  REFERENCES `pr_stringresos` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION");
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_mod_presupuestales', function (Blueprint $table) {
            //
        });
    }
}
