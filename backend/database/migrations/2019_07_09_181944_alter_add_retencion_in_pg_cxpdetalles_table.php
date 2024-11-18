<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddRetencionInPgCxpdetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pg_cxpdetalles` 
                      ADD COLUMN `retencion` DOUBLE NULL DEFAULT 0 AFTER `updated_at`");
    }
}
