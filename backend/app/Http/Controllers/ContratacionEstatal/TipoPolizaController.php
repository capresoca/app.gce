<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\TipoPolizaRequest;
use App\Http\Resources\ContratacionEstatal\TipoPolizaResource;
use App\Models\ContratacionEstatal\CeTipoPoliza;
use Illuminate\Support\Facades\Input;

class TipoPolizaController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return TipoPolizaResource::collection(
				CeTipoPoliza::pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return TipoPolizaResource::collection(CeTipoPoliza::pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(TipoPolizaRequest $request) {
		$tipoPoliza = CeTipoPoliza::create($request->all());
		return response()->json([
			'message' => 'El tipo de poliza fue creado con exito',
			'data' => CeTipoPoliza::where('id', $tipoPoliza->id)->first(),
		]);
	}

	public function show(CeTipoPoliza $tipoPoliza) {
		return new TipoPolizaResource($tipoPoliza);
	}

	public function update(TipoPolizaRequest $request, $id) {
		try {
			$tipoPoliza = CeTipoPoliza::find($id);
			$tipoPoliza->update($request->all());
			$tipoPoliza = $tipoPoliza->where('id', $id)->first();
			return response()->json([
				'message' => 'El tipo de poliza fue editado con exito',
				'data' => $tipoPoliza,
			]);
		} catch (Exception $e) {
			return response()->json($e->getMessage());
		}

	}

	public function findByCodigo($codigo) {
		$tipoPoliza = CeTipoPoliza::where('codigo', $codigo)->first();
		if ($tipoPoliza) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del tipo de poliza ya se encuentra registrado',
				'tipoPoliza' => $tipoPoliza,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El tipo de poliza no existe',
		], 204);
	}
	public function findById($id) {
		$tipoPoliza = CeTipoPoliza::where('id', $id)->first();
		if ($tipoPoliza) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del tipo de poliza ya se encuentra registrado',
				'data' => $tipoPoliza,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El tipo de poliza no existe',
		], 204);
	}
}
