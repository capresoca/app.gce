<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnumInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `au_incapacidades`
                            CHANGE COLUMN `estado` `estado` ENUM("Registrada","Liquidada","Aprobada","Negada","Pagada")');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement('ALTER TABLE `au_incapacidades`
                            CHANGE COLUMN `estado` `estado` ENUM("Liquidada","Aprobada","Negada","Pagada")');

    }
}
