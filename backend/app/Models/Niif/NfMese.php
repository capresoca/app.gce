<?php

namespace App\Models\Niif;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NfMese extends Model implements Auditable {
	use \OwenIt\Auditing\Auditable;
	protected $table = 'nf_meses';

	public function poblarMeses() {
		$fecha = Carbon::now();
		$seCreoRegistro = false;
		for ($i = 0; $i < 3; $i++) {
			if ($fecha->month == 1) {
				$nfMes = NfMese::where('mes', ToolsTrait::monthTraslateSpanish($fecha->month))->where('year', $fecha->year)->first();
				if (is_null($nfMes)) {
					$mesCreate = new NfMese();
					$mesCreate->mes = ToolsTrait::monthTraslateSpanish($fecha->month);
					$mesCreate->year = $fecha->year;
					$mesCreate->estado = "Abierto";
					$mesCreate->save();
					$seCreoRegistro = true;
				}
				$nfMes = NfMese::where('mes', ToolsTrait::monthTraslateSpanish(2))->where('year', $fecha->year)->first();
				if (is_null($nfMes)) {
					$mesCreate = new NfMese();
					$mesCreate->mes = ToolsTrait::monthTraslateSpanish(2);
					$mesCreate->year = $fecha->year;
					$mesCreate->estado = "Abierto";
					$mesCreate->save();
					$seCreoRegistro = true;
				}
				$fecha->month = 3;
			} else {
				$nfMes = NfMese::where('mes', ToolsTrait::monthTraslateSpanish($fecha->month))->where('year', $fecha->year)->first();
				if (is_null($nfMes)) {
					$mesCreate = new NfMese();
					$mesCreate->mes = ToolsTrait::monthTraslateSpanish($fecha->month);
					$mesCreate->year = $fecha->year;
					$mesCreate->estado = "Abierto";
					$mesCreate->save();
					$seCreoRegistro = true;
				}
				$fecha->addMonth();
			}
		}
		return $seCreoRegistro;
	}
}
