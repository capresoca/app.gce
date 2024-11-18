<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFildsetNSolicitudInAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s`
        CHANGE COLUMN `nSolicitud` `nSolicitud` BIGINT(15) NULL DEFAULT NULL COMMENT 'Número de solicitud prestador'");
    }
}
