<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldPgCxpdetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement("ALTER TABLE `pg_cxpdetalles` 
                            DROP FOREIGN KEY `fk_pg_cxpdetalles_pg_conpagos1`,
                            DROP FOREIGN KEY `fk_pg_cxpdetalles_pg_uniapoyos1`");
        DB::statement("ALTER TABLE `pg_cxpdetalles` 
                            CHANGE COLUMN `pg_conpago_id` `pg_conpago_id` INT(11) NULL DEFAULT NULL COMMENT 'FK - Concepto pago.' ,
                            CHANGE COLUMN `pg_uniapoyo_id` `pg_uniapoyo_id` INT(11) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `pg_cxpdetalles` 
                            ADD CONSTRAINT `fk_pg_cxpdetalles_pg_conpagos1`
                            FOREIGN KEY (`pg_conpago_id`)
                            REFERENCES `pg_conpagos` (`id`),
                            ADD CONSTRAINT `fk_pg_cxpdetalles_pg_uniapoyos1`
                            FOREIGN KEY (`pg_uniapoyo_id`)
                            REFERENCES `pg_uniapoyos` (`id`)");
        Schema::enableForeignKeyConstraints();
        DB::statement("ALTER TABLE `pg_cxps` 
                            CHANGE COLUMN `modulo` `modulo` ENUM('CXP', 'Inventarios', 'Saldo Inicial', 'Activos Fijos') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
