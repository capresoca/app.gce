<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixEnumAutsolicitudesEstadoField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_autsolicitudes`
	        CHANGE COLUMN `estado` `estado` ENUM('Registrada','Negada','Autorizada','Autorizada Parcialmente','Confirmada','Anulada') NULL DEFAULT NULL;
        ");

        DB::statement("ALTER TABLE `au_autorizaciones`
	        CHANGE COLUMN `estado` `estado` ENUM('Registrada','Confirmada','Negada','Autorizada','Autorizada Parcialmente','Anulada') NULL DEFAULT NULL;
        ");
    }


}
