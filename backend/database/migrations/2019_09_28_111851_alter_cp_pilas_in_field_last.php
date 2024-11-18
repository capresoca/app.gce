<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCpPilasInFieldLast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `cp_pilas` 
                        ADD COLUMN `as_cargas_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `fecha_archivo`,
                        ADD INDEX `cp_pilas_as_cargas_id_foreign_idx` (`as_cargas_id` ASC)');
        DB::statement('ALTER TABLE `cp_pilas` 
                                    ADD CONSTRAINT `cp_pilas_as_cargas_id_foreign`
                                      FOREIGN KEY (`as_cargas_id`)
                                      REFERENCES `as_cargas_recaudos` (`id`)
                                      ON DELETE RESTRICT
                                      ON UPDATE NO ACTION');
    }

}
