<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFielRsServprotabilidadIdInRsAfiliadoServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `rs_afiliado_servicios` 
                            DROP FOREIGN KEY `FK_rs_afiliado_servicios_rs_servcontratados`");
        DB::statement("ALTER TABLE `rs_afiliado_servicios` 
                            DROP INDEX `FK_rs_afiliado_servicios_rs_servcontratados` ,
                            ADD INDEX `FK_rs_afiliado_servicios_rs_servcontratados_idx` (`rs_servportabilidad_id` ASC)");
        DB::statement("ALTER TABLE `rs_afiliado_servicios` 
                                ADD CONSTRAINT `FK_rs_afiliado_servicios_rs_servcontratados`
                                FOREIGN KEY (`rs_servportabilidad_id`)
                                REFERENCES `rs_servhabilitados` (`id`)");
    }
}
