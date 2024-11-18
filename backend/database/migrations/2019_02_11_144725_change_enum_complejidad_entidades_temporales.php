<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeEnumComplejidadEntidadesTemporales extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		//
		DB::statement("ALTER TABLE `rs_entidad_temporales`
            MODIFY COLUMN `complejidad`  enum('Baja','Media','Alta')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		//
	}
}
