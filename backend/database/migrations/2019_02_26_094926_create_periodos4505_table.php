<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodos4505Table extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('rs_periodos4505', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->enum('periodo', ['01 | Enero a Marzo', '02 | Abril a Junio', '03 | Julio a Septiembre', '04 | Octubre a Diciembre']);
			$table->year('year');
			$table->date('fecha_inicio_validacion');
			$table->date('fecha_fin_validacion');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('rs_periodos4505');
	}
}
