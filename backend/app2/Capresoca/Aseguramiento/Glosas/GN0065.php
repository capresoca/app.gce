<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2018
 * Time: 8:36 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipnovedade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0065 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		Log::info('Glosa a GN065');
		return $this->generarNovedad();
	}
	public function generarNovedad() {
		try {
			///Generar una nueva novedad de tipo N08 para su posterior envío.
			$filasRespuesta=explode(',',$this->regRespuesta->respuesta);
			$tipoNovedad08 = AsTipnovedade::where('codigo', 'N08')->first();
			AsNovtramite::create([
				'as_tramite_id' => $this->regRespuesta->tramite->id,
				'as_tipnovedade_id' => $tipoNovedad08->id,
				'as_afiliado_id' => $this->afiliado->id,			
				'codigo_entidad' => $filasRespuesta[1],
				'gn_municipio_id' => $this->afiliado->gn_municipio_id,
				'gn_tipdocidentidad_id' => $this->afiliado->gn_tipdocidentidad_id,
				'identificacion' => $this->afiliado->identificacion,
				'apellido1' => $this->afiliado->apellido1,
				'apellido2' => $this->afiliado->apellido2,
				'nombre1' => $this->afiliado->nombre1,
				'nombre2' => $this->afiliado->nombre2,
				'fecha_nacimiento' => $this->afiliado->fecha_nacimiento,
			]);
			return true;
		} catch (\Exception $e) {
			Log::error($e);
			return false;
		}
	}
}