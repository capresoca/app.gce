<?php

namespace App\Capresoca\Aseguramiento\Glosas;

use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsPagadore;
use Illuminate\Support\Facades\Log;

class GN0095 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		try {
			Log::info('Glosa a GN095');
			// Finalizar el trámite de novedad y actualizar en la Base de Datos  de Capresoca la información del pagador

			$afiliadoPagador = AsAfiliadoPagador::where('as_afiliado_id', $this->afiliado->id)->first();
			if (is_null($afiliadoPagador)) {
				return true;
			}
			$pagador = AsPagadore::find($afiliadoPagador->as_pagador_id);
			if (is_null($pagador)) {
				return true;
			}
			/// UPDATED PAGADARO $pagador
			return false;

		} catch (\Exception $e) {
			Log::error($e);
			return false;
		}

	}
}