<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfMesesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('nf_meses', function (Blueprint $table) {
			$table->increments('id');
			$table->string('mes', 20);
			$table->string('year', 4);
			$table->enum('estado', ['Cerrado', 'Abierto']);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('nf_meses');
	}
}
