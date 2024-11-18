<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPrDetstringresoIdInPrSolicitudCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_solicitud_cps`
                            ADD INDEX `pr_solicitud_cps_pr_detstrgasto_id_foreign_idx` (`pr_detstrgasto_id` ASC) VISIBLE");

        DB::statement("ALTER TABLE `pr_solicitud_cps` 
                            ADD CONSTRAINT `pr_solicitud_cps_pr_detstrgasto_id_foreign`
                              FOREIGN KEY (`pr_detstrgasto_id`)
                              REFERENCES `pr_detstrgastos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION;");
    }

}
