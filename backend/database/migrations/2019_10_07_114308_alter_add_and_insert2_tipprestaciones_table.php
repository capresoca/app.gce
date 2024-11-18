<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddAndInsert2TipprestacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `au_tipprestaciones` SET `nomenclatura` = 'LM' WHERE (`codigo` = '1')");
        DB::statement("UPDATE `au_tipprestaciones` SET `nomenclatura` = 'EG' WHERE (`codigo` = '2')");
        DB::statement("UPDATE `au_tipprestaciones` SET `nomenclatura` = 'EL' WHERE (`codigo` = '5')");
        DB::statement("UPDATE `au_tipprestaciones` SET `nomenclatura` = 'LP' WHERE (`codigo` = '6')");
        DB::statement("UPDATE `au_tipprestaciones` SET `nomenclatura` = 'AT' WHERE (`codigo` = '7')");
        DB::statement("UPDATE `au_tipprestaciones` SET `nomenclatura` = 'AL' WHERE (`codigo` = '8')");
    }

}
