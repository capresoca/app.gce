<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInSomebodyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE pr_strgastos ADD concepto_anulacionint longtext DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_strgastos ADD fecha_anulacion DATE DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_cdps ADD concepto_anulacionint longtext DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_cdps ADD fecha_anulacion DATE DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_rps ADD concepto_anulacionint longtext DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_rps ADD fecha_anulacion DATE DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_obligaciones ADD concepto_anulacionint longtext DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_obligaciones ADD fecha_anulacion DATE DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_ordenes_de_pagos ADD concepto_anulacionint longtext DEFAULT NULL  NULL");
        DB::statement("ALTER TABLE pr_ordenes_de_pagos ADD fecha_anulacion DATE DEFAULT NULL  NULL");
    }

}
