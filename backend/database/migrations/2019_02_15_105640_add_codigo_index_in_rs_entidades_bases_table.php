<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodigoIndexInRsEntidadesBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `rs_entidades_bases`
	                    ADD INDEX `codigo` (`codigo`)");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `rs_entidades_bases`
	                    DROP INDEX `codigo`");

    }


}
