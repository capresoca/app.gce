<?php

use App\Models\Validador4505\RsPeriodos4505;
use Illuminate\Database\Migrations\Migration;

class Periodos450DefaultInsert extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		RsPeriodos4505::create(['periodo' => '01 | Enero a Marzo', 'year' => '2018', 'fecha_inicio_validacion' => '2018-04-01', 'fecha_fin_validacion' => '2018-04-30']);
		RsPeriodos4505::create(['periodo' => '02 | Abril a Junio', 'year' => '2018', 'fecha_inicio_validacion' => '2018-07-01', 'fecha_fin_validacion' => '2018-07-31']);
		RsPeriodos4505::create(['periodo' => '03 | Julio a Septiembre', 'year' => '2018', 'fecha_inicio_validacion' => '2018-10-01', 'fecha_fin_validacion' => '2018-10-31']);
		RsPeriodos4505::create(['periodo' => '04 | Octubre a Diciembre', 'year' => '2018', 'fecha_inicio_validacion' => '2019-01-01', 'fecha_fin_validacion' => '2019-01-31']);
		RsPeriodos4505::create(['periodo' => '01 | Enero a Marzo', 'year' => '2019', 'fecha_inicio_validacion' => '2019-04-01', 'fecha_fin_validacion' => '2019-04-30']);
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
