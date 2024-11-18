<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegla4505Ipscervicouterino extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('rs_reglas_validacion_4505', function (Blueprint $table) {
			if (!Schema::hasColumn('rs_reglas_validacion_4505', 'codigoIpsTomaCitologiaCervicoUterinaDB')) {
				$table->tinyInteger('codigoIpsTomaCitologiaCervicoUterinaDB')->nullable();
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
