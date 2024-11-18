<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\AbogadoRequest;
use App\Http\Resources\ContratacionEstatal\AbogadoResource;
use App\Models\ContratacionEstatal\CeAbogado;
use Illuminate\Support\Facades\Input;

class AbogadoController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return AbogadoResource::collection(
				CeAbogado::with('tercero')
					->pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return AbogadoResource::collection(CeAbogado::with('tercero')->pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(AbogadoRequest $request) {
		$abogado = CeAbogado::create($request->all());
		return response()->json([
			'message' => 'El abogado fue creado con exito',
			'data' => CeAbogado::where('id', $abogado->id)->with('tercero')->first(),
		]);
	}

	public function show(CeAbogado $abogado) {
		return new AbogadoResource($abogado->load('tercero'));
	}

	public function update(AbogadoRequest $request, $id) {
		try {
			$abogado = CeAbogado::find($id);
			$abogado->update($request->all());
			$abogado = $abogado->where('id', $id)->with('tercero')->first();
			return response()->json([
				'message' => 'El abogado fue editado con exito',
				'data' => $abogado,
			]);
		} catch (Exception $e) {
			return response()->json($e->getMessage());
		}

	}

	public function findByCodigo($codigo) {
		$abogado = CeAbogado::where('codigo', $codigo)->with('tercero')->first();
		if ($abogado) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del abogado ya se encuentra registrado',
				'abogado' => $abogado,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El abogado no existe',
		], 204);
	}
	public function findById($id) {
		$abogado = CeAbogado::where('id', $id)->with('tercero')->first();
		if ($abogado) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del abogado ya se encuentra registrado',
				'data' => $abogado,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El abogado no existe',
		], 204);
	}
}
