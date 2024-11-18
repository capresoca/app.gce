<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterForeinRsRipRadicadoInCmRadicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_radicados` 
                            ADD COLUMN `rs_rip_radicado_id` INT(11) NULL DEFAULT NULL AFTER `observaciones`,
                            ADD INDEX `fk_cm_radicados_rp_rip_radicado_id_forein_idx` (`rs_rip_radicado_id` ASC)");
        DB::statement("ALTER TABLE `cm_radicados` 
                            ADD CONSTRAINT `fk_cm_radicados_rp_rip_radicado_id_forein`
                              FOREIGN KEY (`rs_rip_radicado_id`)
                              REFERENCES `rs_rips_radicados` (`id`)
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
        Schema::table('cm_radicados', function (Blueprint $table) {
            //
        });
    }
}
