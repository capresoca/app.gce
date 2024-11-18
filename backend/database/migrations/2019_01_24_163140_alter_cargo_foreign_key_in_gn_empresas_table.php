<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCargoForeignKeyInGnEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement("ALTER TABLE `gn_empresas`
	      DROP FOREIGN KEY `fk_gn_empresas_gn_cargos`;");

        DB::statement("ALTER TABLE `gn_empresas`
	ADD CONSTRAINT `FK_gn_empresas_gn_cargos` FOREIGN KEY (`gn_cargo_id`) REFERENCES `th_cargos_empleados` (`id`);");
        Schema::enableForeignKeyConstraints();
    }


}
