<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteDataInGsComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DELETE FROM `capresoca`.`gs_permiso_roles` WHERE (`id` = 33)');
        DB::statement("DELETE FROM `capresoca`.`gs_componentes` WHERE (`id` = 33)");
    }
}
