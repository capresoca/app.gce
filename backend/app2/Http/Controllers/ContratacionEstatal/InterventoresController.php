<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\InterventoresRequest;
use App\Http\Resources\ContratacionEstatal\InterventoresResource;
use App\Models\ContratacionEstatal\CeInterventore;
use Illuminate\Support\Facades\Input;

class InterventoresController extends Controller {

	public function index() {

		if (Input::get('per_page')) {
			return InterventoresResource::collection(
				CeInterventore::with('tercero')
					->pimp()
					->orderBy('updated_at', 'desc')
					->paginate(Input::get('per_page'))
			);
		}
		return InterventoresResource::collection(CeInterventore::with('tercero')->pimp()->orderBy('updated_at', 'desc')->get());
	}

	public function store(InterventoresRequest $request) {
		$interventores = CeInterventore::create($request->all());
		return response()->json([
			'message' => 'El interventor fue creado con exito',
			'data' => CeInterventore::where('id', $interventores->id)->with('tercero')->first(),
		]);
	}

	public function show(CeInterventore $interventores) {
		return new InterventoresResource($interventores->load('tercero'));
	}

	public function update(InterventoresRequest $request, $id) {
		try {
			$interventores = CeInterventore::find($id);
			$interventores->update($request->all());
			$interventores = $interventores->where('id', $id)->with('tercero')->first();
			return response()->json([
				'message' => 'El interventor fue editado con exito',
				'data' => $interventores,
			]);
		} catch (Exception $e) {
			return response()->json($e->getMessage());
		}

	}

	public function findByCodigo($codigo) {
		$interventores = CeInterventore::where('codigo', $codigo)->with('tercero')->first();
		if ($interventores) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del interventor ya se encuentra registrado',
				'interventor' => $interventores,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El interventor no existe',
		], 204);
	}
	public function findById($id) {
		$interventores = CeInterventore::where('id', $id)->with('tercero')->first();
		if ($interventores) {
			return response()->json([
				'exists' => true,
				'message' => 'El codigo del interventor ya se encuentra registrado',
				'data' => $interventores,
			], 200);
		}
		return response()->json([
			'exists' => false,
			'message' => 'El interventor no existe',
		], 204);
	}
}
