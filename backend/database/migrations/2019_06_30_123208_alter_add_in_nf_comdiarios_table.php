<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddInNfComdiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `nf_comdiarios` 
                      ADD COLUMN `documento` LONGTEXT NULL DEFAULT NULL AFTER `detalle_anulacion`");
    }

}
