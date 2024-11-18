<?php

use Illuminate\Database\Migrations\Migration;

class InsertUnificador4505GsComponentesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		\App\GsComponente::updateOrCreate([
			'id' => 142,
		], [
			'nombre' => 'Unificador de archivos 4505',
			'gs_modulo_id' => 18,
			'documento' => 0,
			'ruta_router' => 'unificador4505',
			'icono' => 'file_copy',
			'favorito' => 0,
			'descripcion' => 'Unificador archivos 4505',
			'ruta_componente' => '@/components/misional/validador4505/Unificador4505',
			'ruta_router_registro' => '',
			'titulo_registro' => '',
			'permisos' => 'ver,agregar',
			'view_menu' => 1,
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		\App\GsComponente::find(142)->delete();
	}
}
