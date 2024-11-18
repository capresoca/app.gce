<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddAndInsertTipprestacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_tipprestaciones` 
                            ADD COLUMN `nomenclatura` CHAR(2) NULL AFTER `tipo`");
        DB::statement("INSERT INTO `au_tipprestaciones` (`codigo`, `tipo`) 
                                VALUES ('5', 'ENFERMEDAD LABORAL')");
        DB::statement("INSERT INTO `au_tipprestaciones` (`codigo`, `tipo`) 
                                VALUES ('6', 'LICENCIA DE PATERNIDAD')");
        DB::statement("INSERT INTO `au_tipprestaciones` (`codigo`, `tipo`) 
                                VALUES ('7', 'ACCIDENTE DE TRANSITO')");
        DB::statement("INSERT INTO `au_tipprestaciones` (`codigo`, `tipo`) 
                                VALUES ('8', 'ACCIDENTE LABORAL')");
    }
}
