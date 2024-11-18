<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInAsS1vidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `as_s1vids`
                        ADD COLUMN `ruta_archivo` VARCHAR(191) NULL DEFAULT NULL AFTER `nombreArchivo`");
    }
}
