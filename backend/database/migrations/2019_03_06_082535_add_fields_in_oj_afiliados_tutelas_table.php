<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInOjAfiliadosTutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `oj_afiliados_tutelas` 
                            ADD COLUMN `nombre1` VARCHAR(190) NULL DEFAULT NULL AFTER `nombre_completo`,
                            ADD COLUMN `nombre2` VARCHAR(190) NULL DEFAULT NULL AFTER `nombre1`,
                            ADD COLUMN `apellido1` VARCHAR(190) NULL DEFAULT NULL AFTER `nombre2`,
                            ADD COLUMN `apellido2` VARCHAR(190) NULL DEFAULT NULL AFTER `apellido1`
                            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `oj_afiliados_tutelas` 
                                DROP COLUMN `apellido2`,
                                DROP COLUMN `apellido1`,
                                DROP COLUMN `nombre2`,
                                DROP COLUMN `nombre1`
                            ");
    }
}
