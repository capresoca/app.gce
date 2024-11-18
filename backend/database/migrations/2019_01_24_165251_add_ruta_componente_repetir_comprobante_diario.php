<?php

use App\GsComponente;
use Illuminate\Database\Migrations\Migration;

class AddRutaComponenteRepetirComprobanteDiario extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		GsComponente::updateOrCreate([
			'id' => 49,
		], [
			'id' => '49',
			'nombre' => 'Repetir Comprobante de Diario',
			'gs_modulo_id' => '1',
			'documento' => '1',
			'ruta_router' => 'repetirComprobanteDiario',
			'icono' => 'receipt',
			'favorito' => NULL,
			'descripcion' => 'Repetir Comprobante de Diario',
			'ruta_componente' => '@/components/administrativo/niif/RepetirComprobanteDiario/RepetirComprobanteDiario',
			'created_at' => NULL,
			'updated_at' => NULL,
			'ruta_router_registro' => NULL,
			'titulo_registro' => 'Repetir comprobante de diario',
			'permisos' => 'ver,agregar,confirmar,anular,imprimir',
			'view_menu' => '1',
		]);
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
