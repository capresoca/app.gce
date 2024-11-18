<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableToTsConceptoEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ts_concepto_egresos`
            CHANGE COLUMN `facturacion` `facturacion` TINYINT(1) NULL DEFAULT '0' AFTER `updated_at`,
            CHANGE COLUMN `anticipos` `anticipos` TINYINT(1) NULL DEFAULT '0' AFTER `facturacion`,
            CHANGE COLUMN `descuentos` `descuentos` TINYINT(1) NULL AFTER `anticipos`,
            CHANGE COLUMN `presupuesto` `presupuesto` TINYINT(1) NULL AFTER `descuentos`,
            CHANGE COLUMN `acreedores` `acreedores` TINYINT(1) NULL AFTER `presupuesto`,
            CHANGE COLUMN `concepto_cajamenor` `concepto_cajamenor` TINYINT(1) NULL AFTER `acreedores`,
            CHANGE COLUMN `orden_pago` `orden_pago` TINYINT(1) NULL AFTER `concepto_cajamenor`,
            CHANGE COLUMN `reembolso_cajamenor` `reembolso_cajamenor` TINYINT(1) NULL AFTER `orden_pago`,
            CHANGE COLUMN `descuento_cajamenor` `descuento_cajamenor` TINYINT(1) NULL AFTER `reembolso_cajamenor`,
            CHANGE COLUMN `cdt` `cdt` TINYINT(1) NULL AFTER `descuento_cajamenor`,
            CHANGE COLUMN `devolucion_paciente` `devolucion_paciente` TINYINT(1) NULL AFTER `cdt`,
            CHANGE COLUMN `bancos` `bancos` TINYINT(1) NULL AFTER `devolucion_paciente`,
            CHANGE COLUMN `maneja_contrato` `maneja_contrato` TINYINT(1) NULL AFTER `bancos`,
            CHANGE COLUMN `maneja_incapacidad` `maneja_incapacidad` TINYINT(1) NULL AFTER `maneja_contrato`,
            CHANGE COLUMN `maneja_cuentamedica` `maneja_cuentamedica` TINYINT(1) NULL AFTER `maneja_incapacidad`,
            CHANGE COLUMN `pr_detstrgasto_id` `pr_detstrgasto_id` INT(10) UNSIGNED NULL AFTER `maneja_cuentamedica`,
            CHANGE COLUMN `ts_cuenta_id` `ts_cuenta_id` INT(11) NULL AFTER `pr_detstrgasto_id`,
            CHANGE COLUMN `ts_caja_id` `ts_caja_id` INT(11) NULL AFTER `ts_cuenta_id`,
            CHANGE COLUMN `pr_detstringreso_id` `pr_detstringreso_id` INT(10) UNSIGNED NULL AFTER `ts_caja_id`");
    }

//
}
