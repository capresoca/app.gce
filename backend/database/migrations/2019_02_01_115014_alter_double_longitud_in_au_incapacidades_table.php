<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDoubleLongitudInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_incapacidades`
	      CHANGE COLUMN `total_a_pagar` `total_a_pagar` DOUBLE NULL DEFAULT NULL AFTER `dias_incapacidad_total`,
	      CHANGE COLUMN `ibc_final` `ibc_final` DOUBLE NULL DEFAULT NULL AFTER `total_a_pagar`,
	      CHANGE COLUMN `valor_a_reconocer_diario` `valor_a_reconocer_diario` DOUBLE NULL DEFAULT NULL AFTER `ibc_final`;");
    }


}
