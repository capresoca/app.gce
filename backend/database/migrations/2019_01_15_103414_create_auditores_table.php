<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditoresTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        Schema::dropIfExists('ac_auditores');

        Schema::create('ac_auditores', function (Blueprint $table) {
			$table->increments('id');
			$table->string('codigo', 16)->unique();
			$table->integer('gs_usuario_id')->nullable();
			$table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
			$table->enum('tipo', ['Médico', 'Administrativo']);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ac_auditores');
	}
}
