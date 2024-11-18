<?php

use App\GsComponente;
use Illuminate\Database\Migrations\Migration;

class AddRutaComponenteCierreAperturaMes extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		GsComponente::updateOrCreate([
			'id' => '50',
			'nombre' => 'Cierre y Apertura Mes',
			'gs_modulo_id' => '1',
			'documento' => '0',
			'ruta_router' => 'cierreAperturaMes',
			'icono' => 'how_to_vote',
			'favorito' => NULL,
			'descripcion' => 'Cierre y Apertura Mes',
			'ruta_componente' => '@/components/administrativo/niif/CierreAperturaMes/RegistroCierreAperturaMes',
			'created_at' => NULL,
			'updated_at' => NULL,
			'ruta_router_registro' => NULL,
			'titulo_registro' => NULL,
			'permisos' => 'ver,agregar,confirmar,anular,imprimir',
			'view_menu' => '1',
		], [
			'id' => '50',
			'nombre' => 'Cierre y Apertura Mes',
			'gs_modulo_id' => '1',
			'documento' => '0',
			'ruta_router' => 'cierreAperturaMes',
			'icono' => 'how_to_vote',
			'favorito' => NULL,
			'descripcion' => 'Cierre y Apertura Mes',
			'ruta_componente' => '@/components/administrativo/niif/CierreAperturaMes/RegistroCierreAperturaMes',
			'created_at' => NULL,
			'updated_at' => NULL,
			'ruta_router_registro' => NULL,
			'titulo_registro' => NULL,
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
