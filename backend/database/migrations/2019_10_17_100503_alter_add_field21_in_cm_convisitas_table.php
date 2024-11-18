<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddField21InCmConvisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_convisitas` 
                            ADD COLUMN `signos_vitales` TINYINT NOT NULL DEFAULT 0 AFTER `ventilacion_asistido`");
    }

}
