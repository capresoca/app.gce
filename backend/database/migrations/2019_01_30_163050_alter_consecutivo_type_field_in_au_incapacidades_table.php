<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterConsecutivoTypeFieldInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_incapacidades', function (Blueprint $table) {
            DB::statement("ALTER TABLE `au_incapacidades` 
CHANGE COLUMN `consecutivo` `consecutivo` INT NOT NULL ");
        });
    }

}
