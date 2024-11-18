<?php

namespace App\Http\Controllers\AuditoriaCuentas;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuditoriaCuentas\AuditorRequest;
use App\Http\Resources\AuditoriaCuentas\AuditorResource;
use App\Models\AuditorCuentas\AcAuditore;
use App\Traits\EnumsTrait;
use Illuminate\Support\Facades\Input;

class AuditorController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return AuditorResource::collection(
				AcAuditore::with('usuario')
					->pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return AuditorResource::collection(AcAuditore::with('usuario')->pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(AuditorRequest $request) {
	    try {
            $auditor = AcAuditore::create($request->all());
            return response()->json([
                'message' => 'El auditor fue creado con exito',
                'data' => AcAuditore::where('id', $auditor->id)->with('usuario')->first(),
            ]);
        } catch (\Exception $exception) {
	        return response()->json(['errors' => $exception->getMessage()],500);
        }
	}

	public function show(AcAuditore $auditor) {
		return new AuditorResource($auditor->load('usuario'));
	}

	public function update(AuditorRequest $request, $id) {
		try {
			$auditor = AcAuditore::find($id);
			$auditor->update($request->all());
			$auditor = $auditor->where('id', $id)->with('usuario')->first();
			return response()->json([
				'message' => 'El auditor fue editado con exito',
				'data' => $auditor,
			]);
		} catch (\Exception $e) {
			return response()->json($e->getMessage(), 500);
		}

	}

	function complementos() {
		try {
			$complemen = [
				'tipoAuditor' => EnumsTrait::getEnumValues('ac_auditores', 'tipo'),
			];
			return response()->json([
				'state' => true,
				'data' => $complemen,
			]);
		} catch (\Exception $e) {
			return response()->json($e->getMessage());
		}
	}

	public function findByCodigo($codigo) {
		$auditor = AcAuditore::where('codigo', $codigo)->with('usuario')->first();
		if ($auditor) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del auditor ya se encuentra registrado',
				'auditor' => $auditor,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El auditor no existe',
		], 204);
	}
	public function findById($id) {
		$auditor = AcAuditore::where('id', $id)->with('usuario')->first();
		if ($auditor) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del auditor ya se encuentra registrado',
				'data' => $auditor,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El auditor no existe',
		], 204);
	}
}
