<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToExpediendeInRsCumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE `rs_cums`
	          ADD INDEX `expediente_cum_consecutivo` (`expediente_cum`, `consecutivo`)");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `rs_cums`
	          DROP INDEX `expediente_cum_consecutivo`");
    }

}
