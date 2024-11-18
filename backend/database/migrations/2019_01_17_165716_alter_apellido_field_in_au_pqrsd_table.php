<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApellidoFieldInAuPqrsdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_pqrsds` 
          CHANGE COLUMN `apellidos` `apellidos` VARCHAR(50) NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `au_pqrsds` 
          CHANGE COLUMN `apellidos` `apellidos` VARCHAR(50)  NOT NULL
        ");
    }
}
