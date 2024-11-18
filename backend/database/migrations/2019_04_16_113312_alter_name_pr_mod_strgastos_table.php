<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNamePrModStrgastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_mod_strgastos` RENAME TO  `pr_mod_presupuestales`");
        DB::statement("ALTER TABLE `pr_mod_det_strgastos` RENAME TO  `pr_mod_det_presupuestales`");
    }
}
