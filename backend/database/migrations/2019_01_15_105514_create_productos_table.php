<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('in_productos', function (Blueprint $table) {
			$table->increments('id');
			$table->string('codigo', 16)->unique();
			$table->mediumText('descripcion');
			$table->integer('in_grupo_id')->nullable();
			$table->foreign('in_grupo_id')->references('id')->on('in_grupos')->onDelete('restrict');
			$table->integer('in_subgrupo_id')->nullable();
			$table->foreign('in_subgrupo_id')->references('id')->on('in_subgrupos')->onDelete('restrict');
			$table->integer('in_unidad_medida_id')->nullable();
			$table->foreign('in_unidad_medida_id')->references('id')->on('in_unidades_medidas')->onDelete('restrict');
			$table->string('costo_producto', 100);
			$table->string('codigo_iva', 100);
			$table->date('fecha_creacion');
			$table->date('estado', ['Activo', 'Inactivo']);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('in_productos');
	}
}
