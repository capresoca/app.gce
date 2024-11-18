<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFields3InCmGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_glosas` 
                            ADD COLUMN `valor_glosa` DOUBLE NULL DEFAULT 0 AFTER `cm_manglosa_id`");
        DB::statement("ALTER TABLE `cm_respuestas_glosas` 
                            ADD COLUMN `tipo` ENUM('Valor', 'Porcentaje') NULL DEFAULT NULL AFTER `observacion`,
                            ADD COLUMN `valor_respuesta_glosa` DOUBLE NULL DEFAULT 0 AFTER `tipo`");
        DB::statement("ALTER TABLE `cm_glosas_conciliadas` 
                            ADD COLUMN `tipo` ENUM('Valor', 'Porcentaje') NULL DEFAULT NULL AFTER `observacion`,
                            ADD COLUMN `valor_conciliado` DOUBLE NULL DEFAULT 0 AFTER `tipo`");
    }

}
