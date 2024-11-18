<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterFieldNaturalezaPgCxpdetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pg_cxpdetalles` 
                        ADD COLUMN `naturaleza` TINYINT NULL DEFAULT NULL AFTER `nf_conrtf_id`");
    }
}
