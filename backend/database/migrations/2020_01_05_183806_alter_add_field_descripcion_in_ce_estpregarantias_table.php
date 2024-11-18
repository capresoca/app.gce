<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterAddFieldDescripcionInCeEstpregarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_estpregarantias` 
                    ADD COLUMN `descripcion` LONGTEXT NULL DEFAULT NULL AFTER `gn_archivo`,
                    CHANGE COLUMN `gn_archivo` `gn_archivo` INT(11) NULL DEFAULT NULL AFTER `ce_garantia_id`");
        DB::statement("ALTER TABLE `ce_proconestprevios` 
                        ADD COLUMN `descripgarantias` LONGTEXT NULL DEFAULT NULL AFTER `supervicion`,
                        CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `confirmado_at`,
                        CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`");
    }

}
