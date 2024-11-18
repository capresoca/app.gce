<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsNullableInCmFacnacimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facnacimientos` CHANGE COLUMN `as_afiliados_id` `as_afiliados_id` INT(11) NULL COMMENT 'FK - Afiliado quien recibe el servicio' ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement("ALTER TABLE `cm_facnacimientos` CHANGE COLUMN `as_afiliados_id` `as_afiliados_id` INT(11) NOT NULL COMMENT 'FK - Afiliado quien recibe el servicio' ;");

    }
}
