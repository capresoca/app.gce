<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNameTableToCmGlosasTable extends Migration
{
    /**
     * Run the migra
     * tions.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE cm_facservicios_glosas RENAME TO  cm_glosas");
    }

}
