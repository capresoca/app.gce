<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterAddDataOfTipoInCmFacserviciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facservicios`
	                CHANGE COLUMN `tipo` `tipo` ENUM('Consultas','Estancias','Honorarios','Medicamentos NO PBS','Medicamentos PBS','Materiales','Procedimientos NO Quirurgicos','Procedimientos Quirurgicos','Traslados','Capita') NOT NULL COMMENT 'Tipo de Registros: AC(Consultas), AP( Procedimientos QX, Procedimientos NO QX), AT(Traslados, Estancias, Honorarios, Materiales), AM(Medicamentos NO PBS, Medicamentos PBS).' COLLATE 'utf8mb4_unicode_ci' AFTER `cm_factura_id`");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `cm_facservicios`
	                CHANGE COLUMN `tipo` `tipo` ENUM('Consultas','Estancias','Honorarios','Medicamentos NO PBS','Medicamentos PBS','Materiales','Procedimientos NO Quirurgicos','Procedimientos Quirurgicos','Traslados') NOT NULL COMMENT 'Tipo de Registros: AC(Consultas), AP( Procedimientos QX, Procedimientos NO QX), AT(Traslados, Estancias, Honorarios, Materiales), AM(Medicamentos NO PBS, Medicamentos PBS).' COLLATE 'utf8mb4_unicode_ci' AFTER `cm_factura_id`");

    }
}
