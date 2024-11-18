<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsIncpagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_copago_anexot3s` 
                    ADD COLUMN `cuota_moderadora` DOUBLE NULL DEFAULT 0 AFTER `copago`,
                    CHANGE COLUMN `copago` `copago` DOUBLE NULL DEFAULT '0' COMMENT 'Valor copago'");
    }
}
