<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldsThEncargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `th_encargos` ADD COLUMN `tipo_encargo` ENUM('Representante Legal', 'Jefe de Presupuesto', 'Jefe Financiero') NULL DEFAULT NULL AFTER `pr_entidad_resolucion_id`");
        DB::statement("ALTER TABLE `th_encargos` ADD COLUMN `estado` TINYINT NULL DEFAULT 1 AFTER `pr_entidad_resolucion_id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('th_encargos', function (Blueprint $table) {
            $table->dropColumn('tipo_encargo');
            $table->dropColumn('estado');
        });
    }
}
