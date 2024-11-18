<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddUsuarioAnulaInCmFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facturas` 
                    ADD COLUMN `gs_usuario_avala_id` INT(11) NULL DEFAULT NULL AFTER `revision_finalizada`,
                    ADD INDEX `fk_cm_facturas_gs_usuario_aval_id_idx` (`gs_usuario_avala_id` ASC)");
        DB::statement("ALTER TABLE `cm_facturas` 
                    ADD CONSTRAINT `fk_cm_facturas_gs_usuario_aval_id`
                      FOREIGN KEY (`gs_usuario_avala_id`)
                      REFERENCES `gs_usuarios` (`id`)
                      ON DELETE RESTRICT
                      ON UPDATE NO ACTION");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_facturas', function (Blueprint $table) {
            $table->dropColumn('gs_usuario_avala_id');
        });
    }
}
