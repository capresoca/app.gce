<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFourForeignInGnEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `gn_empresas` 
                            ADD COLUMN `nf_niifdebitoanterior_id` INT(11) NULL DEFAULT NULL AFTER `nf_comdiarioprovisionalsincontrato_id`,
                            ADD COLUMN `nf_niifcreditoanterior_id` INT(11) NULL DEFAULT NULL AFTER `nf_niifdebitoanterior_id`,
                            ADD COLUMN `nf_niifdebitoanteriorsincontrato_id` INT(11) NULL DEFAULT NULL AFTER `nf_niifcreditoanterior_id`,
                            ADD COLUMN `nf_niifcreditoanteriorsincontrato_id` INT(11) NULL DEFAULT NULL AFTER `nf_niifdebitoanteriorsincontrato_id`,
                            ADD INDEX `gn_empresas_nf_niifdebitoanteriorsincontrato_id_foreign_idx` (`nf_niifdebitoanteriorsincontrato_id` ASC),
                            ADD INDEX `gn_empresas_nf_niifcreditoanteriorsincontrato_id_idx` (`nf_niifcreditoanteriorsincontrato_id` ASC),
                            ADD INDEX `gn_empresas_nf_niifdebitoanterior_id_idx` (`nf_niifdebitoanterior_id` ASC),
                            ADD INDEX `gn_empresas_nf_niifcreditoanterior_id_idx` (`nf_niifcreditoanterior_id` ASC)");
        DB::statement("ALTER TABLE `gn_empresas` 
                            ADD CONSTRAINT `gn_empresas_nf_niifdebitoanteriorsincontrato_id_foreign`
                              FOREIGN KEY (`nf_niifdebitoanteriorsincontrato_id`)
                              REFERENCES `nf_niifs` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `gn_empresas_nf_niifcreditoanteriorsincontrato_id`
                              FOREIGN KEY (`nf_niifcreditoanteriorsincontrato_id`)
                              REFERENCES `nf_niifs` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `gn_empresas_nf_niifdebitoanterior_id`
                              FOREIGN KEY (`nf_niifdebitoanterior_id`)
                              REFERENCES `nf_niifs` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `gn_empresas_nf_niifcreditoanterior_id`
                              FOREIGN KEY (`nf_niifcreditoanterior_id`)
                              REFERENCES `nf_niifs` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
