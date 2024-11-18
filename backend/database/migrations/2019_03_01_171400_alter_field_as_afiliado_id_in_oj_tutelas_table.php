<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldAsAfiliadoIdInOjTutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `oj_tutelas` DROP FOREIGN KEY `fk_afiliado_tutela`");
        DB::statement("ALTER TABLE `oj_tutelas` 
                                  CHANGE COLUMN `as_afiliado_id` `as_afiliado_id` INT(11) NULL DEFAULT NULL COMMENT 'Afiliado Protegido'");
        DB::statement("ALTER TABLE `oj_tutelas` 
                                ADD CONSTRAINT `fk_afiliado_tutela`
                                  FOREIGN KEY (`as_afiliado_id`)
                                  REFERENCES `as_afiliados` (`id`)");
    }

}
