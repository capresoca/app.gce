<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldAddInCmFacserviciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facservicios` 
        ADD COLUMN `recobro` ENUM('No PBS', 'Tutela', 'ITC') NULL DEFAULT NULL AFTER `avalado`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_facservicios', function (Blueprint $table) {
            $table->dropColumn('recobro');
        });
    }
}
