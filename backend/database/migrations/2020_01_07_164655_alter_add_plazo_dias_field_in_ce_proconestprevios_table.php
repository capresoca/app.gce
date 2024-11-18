<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPlazoDiasFieldInCeProconestpreviosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_proconestprevios`
	                        ADD COLUMN `desc_plazo` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `plazo_dias`;");

        DB::statement("ALTER TABLE `ce_proconestprevios`
                                CHANGE COLUMN `plazo_meses` `plazo_meses` INT(11) NULL AFTER `productos_entregar`,
                                CHANGE COLUMN `plazo_dias` `plazo_dias` INT(11) NULL AFTER `plazo_meses`");
    }
}
