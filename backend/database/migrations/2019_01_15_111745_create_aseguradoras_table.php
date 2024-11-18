<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAseguradorasTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ce_aseguradoras', function (Blueprint $table) {
			$table->increments('id');
			$table->string('codigo', 16)->unique();
			$table->string('nombre', 255);
			$table->integer('gn_tercero_id')->nullable();
			$table->foreign('gn_tercero_id')->references('id')->on('gn_terceros')->onDelete('restrict');
			$table->string('direccion', 255);
			$table->string('telefono', 255);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ce_aseguradoras');
	}
}
