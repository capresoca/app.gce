<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFielCeTipocontratoIdInCeEstudioPreivioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_proconestprevios` 
                            ADD COLUMN `ce_tipocontrato_id` INT(11) NULL AFTER `pr_solicitud_cp_id`,
                            CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `confirmado_at`,
                            CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`,
                            ADD INDEX `fk_ce_proconestprevios_ce_tipocontrato_id_foreign_idx` (`ce_tipocontrato_id` ASC)");
        DB::statement("ALTER TABLE `ce_proconestprevios` 
                            ADD CONSTRAINT `fk_ce_proconestprevios_ce_tipocontrato_id_foreign`
                              FOREIGN KEY (`ce_tipocontrato_id`)
                              REFERENCES `ce_tipocontratos` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
