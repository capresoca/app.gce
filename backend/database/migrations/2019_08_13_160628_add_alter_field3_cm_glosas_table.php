<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterField3CmGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_glosas` 
                    ADD COLUMN `tipo` ENUM('Valor', 'Porcentaje') NULL AFTER `ac_auditor_id`,
                    ADD COLUMN `cm_factura_id` INT(11) NULL DEFAULT NULL AFTER `tipo`,
                    ADD INDEX `cm_facservicios_glosas_cm_factura_id_foreign_idx` (`cm_factura_id` ASC)");
        DB::statement("ALTER TABLE `cm_glosas` 
                    ADD CONSTRAINT `cm_facservicios_glosas_cm_factura_id_foreign`
                      FOREIGN KEY (`cm_factura_id`)
                      REFERENCES `cm_facturas` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION");
    }
}
