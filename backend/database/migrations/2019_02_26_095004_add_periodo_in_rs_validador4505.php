<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodoInRsValidador4505 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('rs_validador4505', function (Blueprint $table) {
			if (!Schema::hasColumn('rs_validador4505', 'rs_periodos4505_id')) {
				$table->integer('rs_periodos4505_id')->nullable();
				$table->foreign('rs_periodos4505_id')->references('id')->on('rs_periodos4505')->onDelete('restrict');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('rs_validador4505', function (Blueprint $table) {
			$table->dropForeign('rs_validador4505_rs_periodos4505_id_foreign');
			$table->dropColumn('rs_periodos4505_id');
		});
	}
}
