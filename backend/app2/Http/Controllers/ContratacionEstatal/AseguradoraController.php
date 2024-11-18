<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\AseguradoraRequest;
use App\Http\Resources\ContratacionEstatal\AseguradoraResource;
use App\Models\ContratacionEstatal\CeAseguradora;
use Illuminate\Support\Facades\Input;

class AseguradoraController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return AseguradoraResource::collection(
				CeAseguradora::with('tercero')
					->pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return AseguradoraResource::collection(CeAseguradora::with('tercero')->pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(AseguradoraRequest $request) {
		$aseguradora = CeAseguradora::create($request->all());
		return response()->json([
			'message' => 'El aseguradora fue creado con exito',
			'data' => CeAseguradora::where('id', $aseguradora->id)->with('tercero')->first(),
		]);
	}

	public function show(CeAseguradora $aseguradora) {
		return new AseguradoraResource($aseguradora->load('tercero'));
	}

	public function update(AseguradoraRequest $request, $id) {
		try {
			$aseguradora = CeAseguradora::find($id);
			$aseguradora->update($request->all());
			$aseguradora = $aseguradora->where('id', $id)->with('tercero')->first();
			return response()->json([
				'message' => 'El aseguradora fue editado con exito',
				'data' => $aseguradora,
			]);
		} catch (Exception $e) {
			return response()->json($e->getMessage());
		}

	}

	public function findByCodigo($codigo) {
		$aseguradora = CeAseguradora::where('codigo', $codigo)->with('tercero')->first();
		if ($aseguradora) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del aseguradora ya se encuentra registrado',
				'aseguradora' => $aseguradora,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El aseguradora no existe',
		], 204);
	}
	public function findById($id) {
		$aseguradora = CeAseguradora::where('id', $id)->with('tercero')->first();
		if ($aseguradora) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del aseguradora ya se encuentra registrado',
				'data' => $aseguradora,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El aseguradora no existe',
		], 204);
	}
}
