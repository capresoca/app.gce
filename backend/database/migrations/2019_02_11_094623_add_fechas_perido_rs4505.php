<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechasPeridoRs4505 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('rs_validador4505', function (Blueprint $table) {
			if (!Schema::hasColumn('rs_validador4505', 'fecha_inicio_periodo')) {
				$table->date('fecha_inicio_periodo')->nullable();
			}
			if (!Schema::hasColumn('rs_validador4505', 'fecha_fin_periodo')) {
				$table->date('fecha_fin_periodo')->nullable();
			}
		});
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
