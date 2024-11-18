<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorToCmConcurreciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_concurrencias` CHANGE COLUMN `via_ingreso` `via_ingreso` ENUM(\'Consulta Externa\',\'Urgencias\',\'Remitido\') NULL DEFAULT NULL COLLATE \'utf8mb4_unicode_ci\' AFTER `rs_entidad_id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
