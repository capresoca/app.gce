<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuConsecutivoIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("ALTER TABLE `au_incapacidades`
                        ADD COLUMN `numeroDocumento` VARCHAR(191) NULL DEFAULT NULL AFTER `pr_solicitud_cp_id`");
    }
}
