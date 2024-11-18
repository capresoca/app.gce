<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldCapitaInCmManglosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_manglosas` 
                            ADD COLUMN `capita` CHAR(2) NULL DEFAULT 0 AFTER `ayuda`");
        DB::statement("ALTER TABLE `cm_facservicios` 
                            CHANGE COLUMN `recobro` `recobro` ENUM('No PBS', 'Tutela', 'ITC', 'IPS baja complejidad (capitado)', 'IPS mediana complejidad', 'Poliza AltoCosto') NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `cm_manglosas` 
                            DROP COLUMN `capita`");
        DB::statement("ALTER TABLE `cm_facservicios` 
                            CHANGE COLUMN `recobro` `recobro` ENUM('No PBS', 'Tutela', 'ITC') NULL DEFAULT NULL");
    }
}
