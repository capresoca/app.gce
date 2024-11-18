<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPgAnticiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pg_anticipos` 
                            ADD COLUMN `origen` ENUM('Saldos Iniciales', 'Egreso') NULL DEFAULT NULL AFTER `updated_at`,
                            ADD COLUMN `pg_saldoinicial_id` INT(11) NULL AFTER `origen`,
                            CHANGE COLUMN `estado_documento` `estado_documento` ENUM('Registrado', 'Anulado', 'Confirmado') NULL DEFAULT NULL COMMENT 'Estado del anticipo' ,
                            ADD INDEX `pg_anticipos_pg_saldoicial_id_foreign_idx` (`pg_saldoinicial_id` ASC)");
        DB::statement("ALTER TABLE `pg_anticipos` 
                            ADD CONSTRAINT `pg_anticipos_pg_saldoicial_id_foreign`
                              FOREIGN KEY (`pg_saldoinicial_id`)
                              REFERENCES `pg_saliniciales` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }

}
