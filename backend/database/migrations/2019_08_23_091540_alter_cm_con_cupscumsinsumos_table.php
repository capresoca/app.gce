<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCmConCupscumsinsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_concums` ALTER `valor` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_concums` CHANGE COLUMN `valor` `valor` DOUBLE NULL AFTER `observaciones`');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_concups` ALTER `valor` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_concups` CHANGE COLUMN `valor` `valor` DOUBLE NULL AFTER `cantidad`');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_coninsumos` ALTER `valor` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_coninsumos` CHANGE COLUMN `valor` `valor` DOUBLE NULL AFTER `descripcion`');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_convisitas` ALTER `modalidad` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_convisitas` CHANGE COLUMN `modalidad` `modalidad` VARCHAR(191) NULL COLLATE \'utf8mb4_unicode_ci\' AFTER `frec_ve`');
        Schema::enableForeignKeyConstraints();
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
