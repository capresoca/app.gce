<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMpDireccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `mp_direccionamientos`
	        CHANGE COLUMN `TipoIdProv` `TipoIDProv` VARCHAR(2) NOT NULL DEFAULT 'NI' COLLATE 'utf8mb4_unicode_ci' AFTER `NoSubEntrega`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `mp_direccionamientos`
	        CHANGE COLUMN `TipoIDProv` `TipoIdProv` VARCHAR(2) NOT NULL DEFAULT 'NI' COLLATE 'utf8mb4_unicode_ci' AFTER `NoSubEntrega`;");

    }
}
