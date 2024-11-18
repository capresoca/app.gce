<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorToCmConcurrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `cm_concurrencias` ALTER `rs_servicio_id` DROP DEFAULT, ALTER `rs_especialidad_id` DROP DEFAULT

        ");
        DB::statement("
            ALTER TABLE `cm_concurrencias`
	        CHANGE COLUMN `rs_servicio_id` `rs_servicio_id` INT(11) NULL AFTER `rs_entidad_id`,
	        CHANGE COLUMN `rs_especialidad_id` `rs_especialidad_id` INT(11) NULL AFTER `rs_servicio_id`

        ");

        DB::statement("
            ALTER TABLE `cm_concurrencias`
	        CHANGE COLUMN `rs_cie10_ingreso` `rs_cie10_ingreso` INT(11) NULL AFTER `rs_entidad_id`,
	        CHANGE COLUMN `rs_especialidad_id` `rs_especialidad_id` INT(11) NULL AFTER `rs_cie10_ingreso`

        ");

        DB::statement("
            ALTER TABLE `cm_concurrencias`
	        CHANGE COLUMN `via_ingreso` `via_ingreso` INT(11) NULL AFTER `rs_entidad_id`
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
