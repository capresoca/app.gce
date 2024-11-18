<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldFechaTramiteInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_incapacidades`
	                    CHANGE COLUMN `fecha_tramite` `fecha_tramite` DATE NULL DEFAULT NULL AFTER `usutramita_id`"
                    );
    }

}
