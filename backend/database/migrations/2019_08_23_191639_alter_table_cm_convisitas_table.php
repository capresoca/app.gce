<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCmConvisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `cm_convisitas` ALTER `fio2` DROP DEFAULT, ALTER `peep` DROP DEFAULT, ALTER `frec_ve` DROP DEFAULT, ALTER `modalidad` DROP DEFAULT');
        DB::statement('ALTER TABLE `cm_convisitas` CHANGE COLUMN `fio2` `fio2` DOUBLE(4,2) NULL AFTER `updated_at`, CHANGE COLUMN `peep` `peep` DOUBLE(4,2) NULL AFTER `fio2`,
CHANGE COLUMN `frec_ve` `frec_ve` DOUBLE(4,2) NULL AFTER `peep`, CHANGE COLUMN `modalidad` `modalidad` VARCHAR(191) NULL COLLATE \'utf8mb4_unicode_ci\' AFTER `frec_ve`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
