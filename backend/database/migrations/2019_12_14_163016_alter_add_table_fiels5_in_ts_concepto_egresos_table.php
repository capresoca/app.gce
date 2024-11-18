<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterAddTableFiels5InTsConceptoEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ts_concepto_egresos` 
                            ADD COLUMN `pr_entidad_resolucion_ing_id` INT(11) NULL DEFAULT NULL AFTER `pr_detstringreso_id`,
                            ADD COLUMN `pr_entidad_resolucion_egr_id` INT(11) NULL DEFAULT NULL AFTER `pr_entidad_resolucion_ing_id`,
                            CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `pr_entidad_resolucion_egr_id`,
                            CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`,
                            ADD INDEX `ts_conceptos_entidad_resolucion_ing_foreign_idx` (`pr_entidad_resolucion_ing_id` ASC),
                            ADD INDEX `ts_conceptos_entidad_resolucion_eg_foreign_idx` (`pr_entidad_resolucion_egr_id` ASC)");
        DB::statement("ALTER TABLE `ts_concepto_egresos` 
                            ADD CONSTRAINT `ts_conceptos_entidad_resolucion_ing_foreign`
                              FOREIGN KEY (`pr_entidad_resolucion_ing_id`)
                              REFERENCES `pr_entidad_resoluciones` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `ts_conceptos_entidad_resolucion_eg_foreign`
                              FOREIGN KEY (`pr_entidad_resolucion_egr_id`)
                              REFERENCES `pr_entidad_resoluciones` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
        DB::statement("ALTER TABLE `pg_cxps` 
                                CHANGE COLUMN `modulo` `modulo` ENUM('CXP', 'Inventarios', 'Saldo Inicial', 'Activos Fijos') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT 'CXP'");
        // CAMBIO EN DETALLE CONCETPRO
        DB::statement("ALTER TABLE `pg_anticipos` 
                            DROP FOREIGN KEY `pg_anticipos_ts_compegreso_id_foreign`");
        DB::statement("ALTER TABLE `pg_anticipos` 
                            CHANGE COLUMN `pg_saldoinicial_id` `pg_saldoinicial_id` INT(11) NULL DEFAULT NULL AFTER `valor`,
                            CHANGE COLUMN `origen` `origen` ENUM('Saldos Iniciales', 'Egreso') NULL DEFAULT NULL AFTER `pg_saldoinicial_id`,
                            CHANGE COLUMN `ts_compegreso_id` `ts_concepoegreso_detalle_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'FK - ID del detalle del concepto del comporbante de egreso' ,
                            DROP INDEX `pg_anticipos_ts_compegreso_id_foreign` ,
                            ADD INDEX `pg_anticipos_ts_compegreso_id_foreign_idx` (`ts_concepoegreso_detalle_id` ASC)");
        DB::statement("ALTER TABLE `pg_anticipos` 
                            ADD CONSTRAINT `pg_anticipos_ts_compegreso_id_foreign`
                              FOREIGN KEY (`ts_concepoegreso_detalle_id`)
                              REFERENCES `ts_conceptoegreso_detalles` (`id`)");
        // Agregando Plan de cobertura
        DB::statement("ALTER TABLE `ts_conceptoegreso_detalles` 
                            ADD COLUMN `rs_planescobertura_id` INT(11) NULL DEFAULT NULL AFTER `pg_cxp_id`,
                            ADD INDEX `fk_ts_conceptoegreso_detalles_rs_planescobertura_id_idx` (`rs_planescobertura_id` ASC)");
        DB::statement("ALTER TABLE `ts_conceptoegreso_detalles` 
                            ADD CONSTRAINT `fk_ts_conceptoegreso_detalles_rs_planescobertura_id`
                              FOREIGN KEY (`rs_planescobertura_id`)
                              REFERENCES `rs_planescoberturas` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
